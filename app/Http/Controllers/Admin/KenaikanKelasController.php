<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessKenaikanKelasRequest;
use App\Models\JawabanTugas;
use App\Models\JawabanUjianEssay;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KenaikanKelasController extends Controller
{
    public function index(Request $request)
    {
        // Pengecualian sadar dari pola FormRequest: ini endpoint GET tanpa form,
        // tapi `kkm` bukan filter biasa — ia ambang numerik yang menentukan
        // rekomendasi naik/tidak naik, jadi nilai non-numerik harus ditolak.
        // Lihat docs/analysis/2026-07-30-validasi-form-02-admin-master-data.md §4.4
        $validated = $request->validate([
            'kelas' => ['nullable', 'string', 'exists:kelas,nama_kelas'],
            'kkm' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $kelasOptions = Kelas::orderBy('nama_kelas')->get();
        $selectedKelas = $validated['kelas'] ?? null;
        $kkm = $validated['kkm'] ?? 75;
        $siswas = [];

        if ($selectedKelas) {
            $siswas = Siswa::with('user')
                ->where('id_kelas', $selectedKelas)
                ->get()
                ->map(function ($siswa) use ($kkm) {
                    $avgUjian = JawabanUjianEssay::where('siswa_id', $siswa->id)->avg('nilai') ?? 0;
                    $avgTugas = JawabanTugas::where('siswa_id', $siswa->id)->avg('nilai') ?? 0;

                    // Simple average of all graded items
                    $items = [];
                    $ujianScores = JawabanUjianEssay::where('siswa_id', $siswa->id)->whereNotNull('nilai')->pluck('nilai');
                    $tugasScores = JawabanTugas::where('siswa_id', $siswa->id)->whereNotNull('nilai')->pluck('nilai');

                    $allScores = $ujianScores->concat($tugasScores);
                    $totalAvg = $allScores->isNotEmpty() ? $allScores->average() : 0;

                    return (object) [
                        'id' => $siswa->id,
                        'nama' => $siswa->user->nama_lengkap,
                        'nis' => $siswa->nis,
                        'avg_ujian' => round($avgUjian, 2),
                        'avg_tugas' => round($avgTugas, 2),
                        'total_avg' => round($totalAvg, 2),
                        'is_recommended' => $totalAvg >= $kkm,
                    ];
                });
        }

        return view('admin.kenaikan.index', compact('kelasOptions', 'siswas', 'selectedKelas', 'kkm'));
    }

    public function process(ProcessKenaikanKelasRequest $request)
    {
        $validated = $request->validated();

        $count = 0;
        DB::transaction(function () use ($validated, &$count) {
            $count = Siswa::whereIn('id', $validated['siswa_ids'])
                ->update(['id_kelas' => $validated['target_kelas']]);
        });

        return redirect()->route('admin.kenaikan.index', ['kelas' => $validated['current_kelas'] ?? null])
            ->with('success', "Berhasil menaikkan {$count} siswa ke kelas {$validated['target_kelas']}.");
    }
}
