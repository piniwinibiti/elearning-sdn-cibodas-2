<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\PythonRunner;

class AbsensiController extends Controller
{
    public function index()
    {
        $user  = auth()->user();
        $siswa = $user->siswa;
        
        $today = now()->toDateString();
        
        $daysMap = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $hariIni = $daysMap[now()->format('l')];
        $nowTime = now()->toTimeString();

        // 1. Cek Jadwal Aktif
        $activeJadwal = \App\Models\Jadwal::where('id_kelas', $siswa->id_kelas)
            ->where('hari', $hariIni)
            ->where('jam_mulai', '<=', $nowTime)
            ->where('jam_selesai', '>=', $nowTime)
            ->first();

        // 2. Cek apakah sudah absen hari ini di mapel apapun (atau spesifik mapel aktif)
        $sudahAbsenMapel = false;
        if ($activeJadwal) {
            $sudahAbsenMapel = Absensi::where('siswa_id', $siswa->id)
                ->where('mata_pelajaran', $activeJadwal->nama_mapel)
                ->where('tanggal', $today)
                ->exists();
        }

        return view('siswa.absensi.index', compact('activeJadwal', 'sudahAbsenMapel'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|string',
        ]);

        $imageParts  = explode(";base64,", $request->image);
        $imageBase64 = base64_decode($imageParts[1]);

        $fileName = 'temp_face_' . time() . '.jpg';
        $tempDir  = storage_path('app/public/temp');
        $tempPath = $tempDir . DIRECTORY_SEPARATOR . $fileName;

        if (!file_exists($tempDir)) mkdir($tempDir, 0777, true);
        file_put_contents($tempPath, $imageBase64);

        $scriptPath = storage_path('app/public/recognize.py');
        $output     = PythonRunner::run($scriptPath, [$tempPath]);

        if (!$output) {
            @unlink($tempPath);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menjalankan pengenalan wajah. Periksa log Laravel untuk detail.'
            ], 500);
        }

        if (isset($output['success']) && $output['success'] &&
            isset($output['confidence']) && $output['confidence'] > 75) {

            $absensi = Absensi::updateOrCreate(
                ['siswa_id' => auth()->user()->siswa->id, 'tanggal' => now()->toDateString()],
                ['jam_masuk' => now()->toTimeString(), 'status' => 'hadir', 'foto_bukti' => 'temp/' . $fileName]
            );

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil! Wajah dikenali dengan tingkat kecocokan ' . $output['confidence'] . '%',
            ]);
        }

        @unlink($tempPath);
        return response()->json([
            'success' => false,
            'message' => $output['message'] ?? 'Wajah tidak dikenali atau tingkat kecocokan rendah. Silakan coba lagi.',
        ], 400);
    }

    /** Input absensi manual oleh guru */
    public function storeManual(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'status'   => 'required|in:hadir,izin,sakit,alpha',
            'tanggal'  => 'required|date',
        ]);

        $absensi = Absensi::updateOrCreate(
            ['siswa_id' => $request->siswa_id, 'tanggal' => $request->tanggal],
            ['jam_masuk' => now()->toTimeString(), 'status' => $request->status]
        );

        return back()->with('success', 'Absensi berhasil dicatat secara manual.');
    }

    /** Hapus data absensi oleh guru */
    public function destroy($id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();
        return back()->with('success', 'Data absensi berhasil dihapus.');
    }
}
