<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'guru') return redirect()->route('guru.dashboard');
        if ($role === 'siswa') return redirect()->route('siswa.dashboard');
        
        return view('dashboard.index'); // Fallback
    }

    public function adminDashboard()
    {
        $totalGuru = \App\Models\Guru::count();
        $totalSiswa = \App\Models\Siswa::count();
        $totalKelas = \App\Models\Kelas::count();
        $siswaTrained = \App\Models\Siswa::whereNotNull('dataset_path')->count();

        // Data for 7-day attendance chart
        $attendanceData = [];
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDays($i)->format('Y-m-d');
            $count = \App\Models\Absensi::where('tanggal', $date)->count();
            $labels[] = \Carbon\Carbon::parse($date)->format('d M');
            $attendanceData[] = $count;
        }

        // Recent presensi 5 latest
        $recentPresensi = \App\Models\Absensi::with('siswa.user')->orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalGuru', 'totalSiswa', 'totalKelas', 'siswaTrained', 
            'labels', 'attendanceData', 'recentPresensi'
        ));
    }

    public function guruDashboard()
    {
        $guru = auth()->user()->guru;
        $guruKelas = $guru->id_kelas_wali ?? '-';
        
        // 1. Statistik Kelas
        $totalSiswa = \App\Models\Siswa::where('id_kelas', $guruKelas)->count();
        $totalMateri = \App\Models\Materi::where('guru_id', $guru->id)->count();
        
        $tugasBelumDinilai = \App\Models\JawabanTugas::whereHas('tugas', function($q) use ($guru) {
            $q->where('guru_id', $guru->id);
        })->whereNull('nilai')->count();

        // 2. Monitoring Presensi Hari Ini
        $hariIni = \Carbon\Carbon::today()->format('Y-m-d');
        
        $absensiHariIni = \App\Models\Absensi::where('tanggal', $hariIni)
            ->whereHas('siswa', function($q) use ($guruKelas) {
                $q->where('id_kelas', $guruKelas);
            })->get();
            
        $siswaHadir = $absensiHariIni->whereIn('status', ['hadir', 'terlambat'])->count();
        $persentaseKehadiran = $totalSiswa > 0 ? ($siswaHadir / $totalSiswa) * 100 : 0;
        
        // Daftar siswa tidak hadir hari ini
        $siswaHadirIds = $absensiHariIni->whereIn('status', ['hadir', 'terlambat'])->pluck('siswa_id')->toArray();
        $siswaTidakHadirRaw = \App\Models\Siswa::with('user')->where('id_kelas', $guruKelas)->whereNotIn('id', $siswaHadirIds)->get();
        
        $siswaTidakHadir = $siswaTidakHadirRaw->map(function($siswa) use ($absensiHariIni) {
            $absenRecord = $absensiHariIni->where('siswa_id', $siswa->id)->first();
            return (object)[
                'siswa' => $siswa,
                'status' => $absenRecord ? $absenRecord->status : 'Belum Absen'
            ];
        });

        // Days map for Indoneisan Hari
        $daysMap = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $hariIniName = $daysMap[now()->format('l')];

        // 3. Jadwal Hari Ini
        $jadwalHariIni = \App\Models\Jadwal::where('guru_id', $guru->id)
            ->where('hari', $hariIniName)
            ->orderBy('jam_mulai')
            ->get();

        // 4. Deadline Terdekat
        $tugasTerdekat = \App\Models\Tugas::where('guru_id', $guru->id)
            ->where('deadline', '>=', now())
            ->orderBy('deadline', 'asc')
            ->take(5)
            ->get();

        // 5. Grafik Rata-Rata Nilai Tugas Kelas
        $tugasTugas = \App\Models\Tugas::where('guru_id', $guru->id)
            ->withAvg('jawaban', 'nilai')
            ->orderBy('created_at', 'asc')
            ->take(10)
            ->get();
            
        $tugasLabels = [];
        $tugasAverages = [];
        
        foreach ($tugasTugas as $tgs) {
            $tugasLabels[] = \Illuminate\Support\Str::limit($tgs->judul, 15);
            $tugasAverages[] = round($tgs->jawaban_avg_nilai ?? 0, 1);
        }

        // Daftar semua siswa di kelas guru (untuk form absensi manual)
        $hasFaceDataset = collect(glob(storage_path('app/public/dataset/User.' . auth()->id() . '.*.jpg')))->isNotEmpty();

        return view('guru.dashboard', compact(
            'guruKelas', 'totalSiswa', 'totalMateri', 'tugasBelumDinilai',
            'siswaHadir', 'persentaseKehadiran', 'siswaTidakHadir',
            'tugasTerdekat', 'tugasLabels', 'tugasAverages', 'jadwalHariIni',
            'hasFaceDataset'
        ));
    }

    public function siswaDashboard()
    {
        $siswa = auth()->user()->siswa;
        $siswaKelas = $siswa->id_kelas ?? null;
        
        // Days map for Indoneisan Hari
        $daysMap = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $hariIniName = $daysMap[now()->format('l')];

        // Schedule
        $jadwalHariIni = \App\Models\Jadwal::with('guru.user')
            ->where('id_kelas', $siswaKelas)
            ->where('hari', $hariIniName)
            ->orderBy('jam_mulai')
            ->get();

        $materis = \App\Models\Materi::where('id_kelas', $siswaKelas)->latest()->take(5)->get();
        $tugasList = \App\Models\Tugas::where('id_kelas', $siswaKelas)->latest()->take(5)->get();
        
        $riwayatUjian = \App\Models\JawabanUjianEssay::with('ujian')
            ->where('siswa_id', $siswa->id)
            ->latest()
            ->take(5)
            ->get();

        // Check for active class schedule
        $now = now()->toTimeString();
        $activeJadwal = \App\Models\Jadwal::where('id_kelas', $siswaKelas)
            ->where('hari', $hariIniName)
            ->where('jam_mulai', '<=', $now)
            ->where('jam_selesai', '>=', $now)
            ->first();

        $sudahAbsenMapel = false;
        if ($activeJadwal) {
            $sudahAbsenMapel = \App\Models\Absensi::where('siswa_id', $siswa->id)
                ->where('mata_pelajaran', $activeJadwal->nama_mapel)
                ->where('tanggal', now()->toDateString())
                ->exists();
        }

        $hasFaceDataset = collect(glob(storage_path('app/public/dataset/User.' . auth()->id() . '.*.jpg')))->isNotEmpty();

        return view('siswa.dashboard', compact('materis', 'tugasList', 'riwayatUjian', 'jadwalHariIni', 'hasFaceDataset', 'activeJadwal', 'sudahAbsenMapel'));
    }
}
