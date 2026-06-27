<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\JawabanTugas;
use App\Models\JawabanUjianEssay;
use Illuminate\Support\Facades\DB;

class KenaikanKelasController extends Controller
{
    public function index(Request $request)
    {
        $kelasOptions = Kelas::orderBy('nama_kelas')->get();
        $selectedKelas = $request->kelas;
        $kkm = $request->get('kkm', 75);
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
                        'is_recommended' => $totalAvg >= $kkm
                    ];
                });
        }

        return view('admin.kenaikan.index', compact('kelasOptions', 'siswas', 'selectedKelas', 'kkm'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'siswa_ids' => 'required|array',
            'siswa_ids.*' => 'exists:siswas,id',
            'target_kelas' => 'required|string|max:50'
        ]);

        $count = 0;
        DB::transaction(function () use ($request, &$count) {
            $count = Siswa::whereIn('id', $request->siswa_ids)
                ->update(['id_kelas' => $request->target_kelas]);
        });

        return redirect()->route('admin.kenaikan.index', ['kelas' => $request->current_kelas])
            ->with('success', "Berhasil menaikkan {$count} siswa ke kelas {$request->target_kelas}.");
    }
}
