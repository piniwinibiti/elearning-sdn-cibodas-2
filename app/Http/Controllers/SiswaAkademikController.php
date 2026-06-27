<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\JawabanTugas;
use App\Models\JawabanUjianEssay;
use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiswaAkademikController extends Controller
{
    public function indexPresensi()
    {
        $siswa = auth()->user()->siswa;
        
        $absensis = Absensi::where('siswa_id', $siswa->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        $stats = [
            'total' => $absensis->count(),
            'hadir' => $absensis->where('status', 'hadir')->count(),
            'terlambat' => $absensis->where('status', 'terlambat')->count(),
            'izin' => $absensis->where('status', 'izin')->count(),
            'sakit' => $absensis->where('status', 'sakit')->count(),
            'alpha' => $absensis->where('status', 'alpha')->count(),
        ];

        $persentase = $stats['total'] > 0 
            ? (($stats['hadir'] + $stats['terlambat']) / $stats['total']) * 100 
            : 0;

        return view('siswa.akademik.presensi', compact('absensis', 'stats', 'persentase'));
    }

    public function indexNilai()
    {
        $siswa = auth()->user()->siswa;
        $idKelas = $siswa->id_kelas;

        // 1. Get List of Subjects for this class
        // We can get subjects from either Jadwal or Tugas/Ujian. 
        // Let's get them from the master Mapel table or assigned ones.
        $subjects = Mapel::orderBy('nama_mapel')->pluck('nama_mapel')->toArray();

        $rekapNilai = [];

        foreach ($subjects as $subject) {
            // A. Task Grades (Average)
            $rataTugas = JawabanTugas::where('siswa_id', $siswa->id)
                ->whereHas('tugas', function($q) use ($subject) {
                    $q->where('mata_pelajaran', $subject);
                })
                ->whereNotNull('nilai')
                ->avg('nilai');

            // B1. Exam Grades (Essay)
            $nilaiUjianEssay = JawabanUjianEssay::where('siswa_id', $siswa->id)
                ->whereHas('ujian', function($q) use ($subject) {
                    $q->where('mata_pelajaran', $subject);
                })
                ->whereNotNull('nilai')
                ->pluck('nilai')->toArray();

            // B2. Exam Grades (Pilihan Ganda)
            $ujiansGanda = Ujian::where('mata_pelajaran', $subject)
                ->where('tipe', 'ganda')
                ->whereHas('jawabanGandas', function($q) use ($siswa) {
                    $q->where('siswa_id', $siswa->id);
                })
                ->with(['jawabanGandas' => function($q) use ($siswa) {
                    $q->where('siswa_id', $siswa->id);
                }, 'soals'])
                ->get();
                
            $nilaiUjianGanda = [];
            foreach ($ujiansGanda as $ug) {
                $totalSoal = $ug->soals->count();
                if ($totalSoal > 0) {
                    $benar = $ug->jawabanGandas->where('is_benar', true)->count();
                    $nilaiUjianGanda[] = ($benar / $totalSoal) * 100;
                }
            }
            
            $semuaNilaiUjian = array_merge($nilaiUjianEssay, $nilaiUjianGanda);
            $nilaiUjian = count($semuaNilaiUjian) > 0 ? array_sum($semuaNilaiUjian) / count($semuaNilaiUjian) : null;

            if ($rataTugas !== null || $nilaiUjian !== null) {
                $rataTugas = round($rataTugas ?? 0, 1);
                $nilaiUjian = round($nilaiUjian ?? 0, 1);
                
                // Final Grade calculation (40% Task, 60% Exam)
                $nilaiAkhir = ($rataTugas * 0.4) + ($nilaiUjian * 0.6);

                $rekapNilai[] = (object)[
                    'mapel' => $subject,
                    'rata_tugas' => $rataTugas,
                    'nilai_ujian' => $nilaiUjian,
                    'nilai_akhir' => round($nilaiAkhir, 1)
                ];
            }
        }

        return view('siswa.akademik.nilai', compact('rekapNilai'));
    }
}
