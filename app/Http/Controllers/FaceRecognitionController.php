<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessScannerRequest;
use App\Http\Requests\RecognizeAbsensiRequest;
use App\Http\Requests\RegisterFaceDatasetRequest;
use App\Http\Requests\VerifySiswaAuthRequest;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\User;
use App\Services\FaceDuplicateGuard;
use App\Services\PythonRunner;

class FaceRecognitionController extends Controller
{
    /** Absensi siswa mandiri (scan wajah dari halaman siswa) */
    public function recognize(RecognizeAbsensiRequest $request)
    {
        $request->validated();

        $user = auth()->user();
        $siswa = $user->siswa;

        if (! $siswa) {
            return response()->json(['success' => false, 'message' => 'Hanya siswa yang dapat melakukan presensi mandiri.'], 403);
        }

        // 1. Cek Jadwal Aktif
        $daysMap = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu',
        ];
        $hariIni = $daysMap[now()->format('l')];
        $nowTime = now()->toTimeString();

        $activeJadwal = Jadwal::where('id_kelas', $siswa->id_kelas)
            ->where('hari', $hariIni)
            ->where('jam_mulai', '<=', $nowTime)
            ->where('jam_selesai', '>=', $nowTime)
            ->first();

        if (! $activeJadwal) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada jadwal pelajaran aktif untuk kelas Anda saat ini.',
            ], 403);
        }

        // 2. Cek apakah sudah absen di Mapel ini hari ini
        $sudahAbsen = Absensi::where('siswa_id', $siswa->id)
            ->where('mata_pelajaran', $activeJadwal->nama_mapel)
            ->where('tanggal', now()->toDateString())
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan presensi untuk mata pelajaran '.$activeJadwal->nama_mapel.' hari ini.',
            ], 400);
        }

        // 3. Proses Face Recognition
        $imageParts = explode(';base64,', $request->image);
        $imageBase64 = base64_decode($imageParts[1]);

        $fileName = 'face_scan_'.time().'.jpg';
        $tempDir = storage_path('app/temp');
        $tempPath = $tempDir.DIRECTORY_SEPARATOR.$fileName;

        if (! file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }
        file_put_contents($tempPath, $imageBase64);

        $scriptPath = storage_path('app/public/recognize.py');
        $output = PythonRunner::run($scriptPath, [$tempPath]);

        if (! $output) {
            @unlink($tempPath);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menjalankan pengenalan wajah.',
            ], 500);
        }

        // Threshold 20%
        if (isset($output['success']) && $output['success'] &&
            isset($output['confidence']) && $output['confidence'] > 20 &&
            isset($output['user_id']) && $output['user_id'] == $user->id) {

            if (! file_exists(storage_path('app/public/absensi'))) {
                mkdir(storage_path('app/public/absensi'), 0777, true);
            }

            $finalPath = storage_path('app/public/absensi/'.$fileName);
            $absensi = Absensi::create([
                'siswa_id' => $siswa->id,
                'id_kelas' => $siswa->id_kelas,
                'mata_pelajaran' => $activeJadwal->nama_mapel,
                'tanggal' => now()->toDateString(),
                'jam_masuk' => now()->toTimeString(),
                'status' => 'hadir',
                'foto_bukti' => 'absensi/'.$fileName,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Presensi Berhasil! Anda tercatat hadir di mata pelajaran '.$activeJadwal->nama_mapel,
                'confidence' => $output['confidence'],
                'data' => $absensi,
            ]);
        }

        @unlink($tempPath);

        return response()->json([
            'success' => false,
            'message' => 'Wajah tidak dikenali atau tidak cocok dengan akun Anda. (Skor: '.($output['confidence'] ?? 0).'%)',
        ], 401);
    }

    /** Training manual dari halaman admin */
    public function trainAdmin()
    {
        $scriptPath = storage_path('app/public/train.py');

        if (! file_exists($scriptPath)) {
            return back()->with('error', 'Script training tidak ditemukan.');
        }

        $output = PythonRunner::runRaw($scriptPath);

        if (file_exists(storage_path('app/public/trainer.yml'))) {
            return back()->with('success', 'Model Wajah berhasil dilatih (Training Selesai).');
        }

        return back()->with('error', 'Gagal melatih model: '.$output);
    }

    /** Scanner absensi dari halaman guru */
    public function scannerGuru()
    {
        return view('guru.scanner');
    }

    public function processScanner(ProcessScannerRequest $request)
    {
        $request->validated();

        $imageParts = explode(';base64,', $request->image);
        $imageBase64 = base64_decode($imageParts[1]);

        $fileName = 'scan_guru_'.time().'.jpg';
        $tempDir = storage_path('app/temp');
        $tempPath = $tempDir.DIRECTORY_SEPARATOR.$fileName;

        if (! file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }
        file_put_contents($tempPath, $imageBase64);

        $scriptPath = storage_path('app/public/recognize.py');
        $output = PythonRunner::run($scriptPath, [$tempPath]);

        if (! $output) {
            @unlink($tempPath);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menjalankan pengenalan wajah. Periksa log Laravel untuk detail.',
            ], 500);
        }

        if (isset($output['success']) && $output['success'] &&
            isset($output['confidence']) && $output['confidence'] > 20) {

            $user = User::with(['siswa', 'guru'])->find($output['user_id']);
            if (! $user) {
                @unlink($tempPath);

                return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan dalam database.']);
            }

            // Jika yang di-scan adalah Siswa (untuk absensi)
            if ($user->siswa) {
                $siswa = $user->siswa;
                if (! file_exists(storage_path('app/public/absensi'))) {
                    mkdir(storage_path('app/public/absensi'), 0777, true);
                }

                $finalPath = storage_path('app/public/absensi/'.$fileName);
                rename($tempPath, $finalPath);

                $absensi = Absensi::updateOrCreate(
                    ['siswa_id' => $siswa->id, 'tanggal' => now()->toDateString()],
                    ['jam_masuk' => now()->toTimeString(), 'status' => 'hadir', 'foto_bukti' => 'absensi/'.$fileName]
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Absensi tercatat (Siswa)',
                    'siswa' => [
                        'nama' => $user->nama_lengkap,
                        'kelas' => $siswa->id_kelas,
                        'jam' => $absensi->jam_masuk,
                    ],
                ]);
            }

            @unlink($tempPath);

            return response()->json(['success' => false, 'message' => 'User terdeteksi tapi bukan Siswa.']);
        }

        @unlink($tempPath);

        return response()->json([
            'success' => false,
            'message' => $output['message'] ?? 'Wajah tidak dikenali.',
        ], 400);
    }

    /** Verifikasi identitas sebelum ujian */
    public function verifySiswaAuth(VerifySiswaAuthRequest $request)
    {
        $request->validated();

        $imageParts = explode(';base64,', $request->image);
        $imageBase64 = base64_decode($imageParts[1]);

        $fileName = 'verify_ujian_'.time().'.jpg';
        $tempDir = storage_path('app/temp');
        $tempPath = $tempDir.DIRECTORY_SEPARATOR.$fileName;

        if (! file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }
        file_put_contents($tempPath, $imageBase64);

        $scriptPath = storage_path('app/public/recognize.py');
        $output = PythonRunner::run($scriptPath, [$tempPath]);

        @unlink($tempPath);

        if (! $output) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menjalankan pengenalan wajah.',
            ], 500);
        }

        $loggedInUserId = auth()->id();

        if (isset($output['success']) && $output['success'] &&
            isset($output['confidence']) && $output['confidence'] > 20 &&
            isset($output['user_id']) && $output['user_id'] == $loggedInUserId) {

            return response()->json([
                'success' => true,
                'message' => 'Verifikasi Identitas Berhasil!',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Wajah tidak cocok dengan identitas akun yang sedang login.',
            'confidence' => $output['confidence'] ?? 0,
        ], 401);
    }

    /** Mendaftarkan dataset wajah (Training) - Bisa dipanggil dari dashboard */
    public function registerFaceDataset(RegisterFaceDatasetRequest $request)
    {
        $validated = $request->validated();

        $imageParts = explode(';base64,', $validated['image']);
        $imageBase64 = base64_decode($imageParts[1]);

        $userId = auth()->id();
        $sample = $validated['sample_count'];

        // Cek duplikat cuma di sample pertama (cukup 1x, hemat panggilan Python).
        if ($sample === 1) {
            $conflict = FaceDuplicateGuard::findConflict($validated['image'], $userId);
            if ($conflict) {
                $conflictUser = User::find($conflict['user_id']);

                return response()->json([
                    'success' => false,
                    'errors' => [
                        'image' => ['Wajah ini sudah terdaftar atas nama '.($conflictUser->nama_lengkap ?? 'pengguna lain')." (kecocokan {$conflict['confidence']}%). Registrasi dibatalkan untuk mencegah duplikasi. Hubungi admin jika ini adalah kesalahan."],
                    ],
                ], 422);
            }
        }

        // Format: User.[USER_ID].[SAMPLE].jpg
        $fileName = "User.{$userId}.{$sample}.jpg";
        $datasetDir = storage_path('app/public/dataset');

        if (! file_exists($datasetDir)) {
            mkdir($datasetDir, 0777, true);
        }
        file_put_contents($datasetDir.DIRECTORY_SEPARATOR.$fileName, $imageBase64);

        // Jika sampel terakhir (ke-20), jalankan training otomatis
        $isTrained = false;
        if ($sample >= 20) {
            $trainScript = storage_path('app/public/train.py');
            if (file_exists($trainScript)) {
                PythonRunner::runRaw($trainScript);
                $isTrained = true;
            }
        }

        return response()->json([
            'success' => true,
            'message' => $isTrained
                ? 'Pendaftaran selesai! Model wajah sudah diperbarui otomatis.'
                : "Sample foto ke-{$sample} disimpan.",
            'is_finished' => ($sample >= 20),
        ]);
    }
}
