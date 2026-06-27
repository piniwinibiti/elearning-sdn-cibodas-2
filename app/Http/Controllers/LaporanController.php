<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Tugas;
use App\Models\JawabanTugas;
use App\Models\Absensi;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function getReportData()
    {
        // For simplicity, we assume Guru can see all siswa's report.
        // In a real app, it might be scoped to a specific class the Guru teaches.
        
        $siswas = Siswa::with(['user', 'absensis', 'jawabans'])->get();
        $tugasCount = Tugas::count();

        $laporanData = [];
        foreach ($siswas as $siswa) {
            // Hitung Absensi
            $totalKehadiran = $siswa->absensis->where('status', 'hadir')->count();
            $totalTerlambat = $siswa->absensis->where('status', 'terlambat')->count();
            $totalAlpha = $siswa->absensis->where('status', 'alpha')->count();
            $totalMasuk = $totalKehadiran + $totalTerlambat;

            // Hitung Nilai Rata-rata
            $totalNilai = $siswa->jawabans->sum('nilai');
            $jumlahTugasDikerjakan = $siswa->jawabans->whereNotNull('nilai')->count();
            
            $rataRata = $jumlahTugasDikerjakan > 0 ? round($totalNilai / $jumlahTugasDikerjakan, 2) : 0;

            $laporanData[] = [
                'siswa' => $siswa,
                'hadir' => $totalMasuk,
                'alpha' => $totalAlpha,
                'rata_rata_tugas' => $rataRata,
                'tugas_terkumpul' => $jumlahTugasDikerjakan,
                'total_tugas' => $tugasCount,
            ];
        }

        return $laporanData;
    }

    public function index()
    {
        $laporanData = $this->getReportData();
        return view('guru.laporan.index', compact('laporanData'));
    }

    public function downloadPdf()
    {
        $laporanData = $this->getReportData();
        
        $pdf = Pdf::loadView('guru.laporan.pdf', compact('laporanData'))->setPaper('a4', 'landscape');
        return $pdf->download('laporan-rekap-'.date('YmdHis').'.pdf');
    }
}
