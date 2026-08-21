<?php

namespace App\Services;

class FaceDuplicateGuard
{
    /**
     * Confidence (%) minimum dari recognize.py untuk dianggap "wajah yang sama",
     * bukan sekadar mirip. Dibuat lebih tinggi dari threshold login (20%) karena
     * di sini tujuannya menolak duplikat yang jelas, bukan mengenali wajah.
     */
    private const CONFLICT_THRESHOLD = 50;

    /**
     * Cek apakah $base64Image sudah dikenali sebagai user LAIN (bukan $excludeUserId)
     * oleh model wajah yang sedang aktif. Dipakai untuk mencegah 1 wajah fisik
     * terdaftar di 2 akun berbeda (misal siswa & guru) akibat human error saat input.
     *
     * @return array{user_id: int, confidence: float}|null null jika tidak ada konflik
     */
    public static function findConflict(string $base64Image, int $excludeUserId): ?array
    {
        if (! file_exists(storage_path('app/public/trainer.yml'))) {
            // Belum pernah ada training sama sekali -> tidak ada apa pun untuk dibandingkan.
            return null;
        }

        $imageParts = explode(';base64,', $base64Image);
        if (count($imageParts) !== 2) {
            return null;
        }

        $tempDir = storage_path('app/temp');
        if (! file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }
        $tempPath = $tempDir.DIRECTORY_SEPARATOR.'face_dup_check_'.uniqid().'.jpg';
        file_put_contents($tempPath, base64_decode($imageParts[1]));

        $output = PythonRunner::run(storage_path('app/public/recognize.py'), [$tempPath]);

        @unlink($tempPath);

        if (! $output || ! ($output['success'] ?? false)) {
            return null;
        }

        $matchedUserId = (int) ($output['user_id'] ?? 0);
        $confidence = (float) ($output['confidence'] ?? 0);

        if ($matchedUserId !== 0 && $matchedUserId !== $excludeUserId && $confidence >= self::CONFLICT_THRESHOLD) {
            return ['user_id' => $matchedUserId, 'confidence' => $confidence];
        }

        return null;
    }
}
