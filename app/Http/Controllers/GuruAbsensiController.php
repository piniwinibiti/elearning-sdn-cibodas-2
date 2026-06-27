<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\GuruMapel;
use Illuminate\Support\Facades\DB;
use App\Services\PythonRunner;

class GuruAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user()->guru;
        $isWali = $guru->isWali();
        
        // Days map for Indoneisan Hari
        $daysMap = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $hariIni = $daysMap[now()->format('l')];

        // Fetch Today's Schedule for this Guru
        $jadwalHariIni = Jadwal::where('guru_id', $guru->id)
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai')
            ->get();

        // Context
        $selectedKelas = $request->input('kelas', $isWali ? $guru->id_kelas_wali : null);
        $selectedMapel = $request->input('mapel');
        $tanggal = $request->input('tanggal', now()->toDateString());

        // Options
        $kelasOptions = Kelas::orderBy('nama_kelas')->pluck('nama_kelas');
        
        if ($isWali) {
            // Wali Kelas can pick any subject for their class
            $mapelOptions = Mapel::orderBy('nama_mapel')->pluck('nama_mapel');
        } else {
            // Bidang Guru can only pick their assigned subjects
            $mapelOptions = $guru->mapels()->pluck('nama_mapel');
            
            // Fallback: Jika relasi kosong (misal data lama), coba parse dari string mapel_ajar
            if ($mapelOptions->isEmpty() && !empty($guru->mapel_ajar) && $guru->mapel_ajar !== '-') {
                $mapelOptions = collect(explode(',', $guru->mapel_ajar))->map(fn($m) => trim($m))->filter();
            }
            
            // AUTO-SELECT: If subject teacher has exactly 1 subject, use it as default
            if ($mapelOptions->count() === 1) {
                $selectedMapel = $selectedMapel ?? $mapelOptions->first();
            } elseif ($mapelOptions->isEmpty()) {
                // Last fallback: tampilkan semua mapel jika tetap tidak ada
                $mapelOptions = Mapel::orderBy('nama_mapel')->pluck('nama_mapel');
            }
        }

        $siswas = [];
        $rekapAbsensi = [];

        if ($selectedKelas) {
            $siswas = Siswa::with('user')
                ->where('id_kelas', $selectedKelas)
                ->get();
                
            if ($selectedMapel) {
                $rekapAbsensi = Absensi::where('id_kelas', $selectedKelas)
                    ->where('mata_pelajaran', $selectedMapel)
                    ->where('tanggal', $tanggal)
                    ->get()
                    ->keyBy('siswa_id');
            }
        }

        return view('guru.absensi.index', compact(
            'guru', 'isWali', 'selectedKelas', 'selectedMapel', 
            'tanggal', 'kelasOptions', 'mapelOptions', 'siswas', 'rekapAbsensi', 'jadwalHariIni'
        ));
    }

    public function getStudents(Request $request)
    {
        $kelas = $request->kelas;
        $students = Siswa::with('user')->where('id_kelas', $kelas)->get();
        return response()->json($students);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas' => 'required|string',
            'mapel' => 'required|string',
            'tanggal' => 'required|date',
            'absensi' => 'required|array',
        ]);

        $kelas = $request->kelas;
        $mapel = $request->mapel;
        $tanggal = $request->tanggal;

        foreach ($request->absensi as $siswaId => $data) {
            Absensi::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'tanggal' => $tanggal,
                    'id_kelas' => $kelas,
                    'mata_pelajaran' => $mapel,
                ],
                [
                    'status' => $data['status'],
                    'jam_masuk' => $data['status'] == 'hadir' ? now()->toTimeString() : null,
                    'keterangan' => $data['keterangan'] ?? null,
                ]
            );
        }

        return back()->with('success', 'Absensi berhasil disimpan untuk ' . $mapel . ' di Kelas ' . $kelas);
    }

    public function scannerProcess(Request $request)
    {
        $request->validate([
            'image' => 'required|string',
            'kelas' => 'required|string',
            'mapel' => 'required|string',
        ]);

        $imageParts  = explode(";base64,", $request->image);
        $imageBase64 = base64_decode($imageParts[1]);

        $fileName = 'guru_scan_' . time() . '.jpg';
        $tempDir  = storage_path('app/public/temp');
        $tempPath = $tempDir . DIRECTORY_SEPARATOR . $fileName;

        if (!file_exists($tempDir)) mkdir($tempDir, 0777, true);
        file_put_contents($tempPath, $imageBase64);

        $scriptPath = storage_path('app/public/recognize.py');
        $output     = PythonRunner::run($scriptPath, [$tempPath]);

        if (!$output || !isset($output['success']) || !$output['success']) {
            @unlink($tempPath);
            return response()->json([
                'success' => false,
                'message' => $output['message'] ?? 'Wajah tidak dikenali.'
            ], 400);
        }

        if (isset($output['confidence']) && $output['confidence'] > 75) {
            $siswaId = $output['id'];
            $siswa = Siswa::with('user')->find($siswaId);

            if (!$siswa || $siswa->id_kelas != $request->kelas) {
                @unlink($tempPath);
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa dikenali (' . ($siswa->user->nama_lengkap ?? 'Unknown') . '), tapi bukan di kelas ini.'
                ], 400);
            }

            Absensi::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'tanggal' => now()->toDateString(),
                    'id_kelas' => $request->kelas,
                    'mata_pelajaran' => $request->mapel,
                ],
                [
                    'status' => 'hadir',
                    'jam_masuk' => now()->toTimeString(),
                    'foto_bukti' => 'temp/' . $fileName
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil! ' . $siswa->user->nama_lengkap . ' hadir.',
                'siswa' => ['nama' => $siswa->user->nama_lengkap]
            ]);
        }

        @unlink($tempPath);
        return response()->json([
            'success' => false,
            'message' => 'Tingkat kecocokan rendah (' . $output['confidence'] . '%). Silakan coba lagi.'
        ], 400);
    }
}
