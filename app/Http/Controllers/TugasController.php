<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\JawabanTugas;
use App\Models\Mapel;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    public function indexGuru(Request $request)
    {
        $guruId = auth()->user()->guru->id;
        $query = Tugas::where('guru_id', $guruId);

        // Pencarian (Search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('judul', 'like', "%{$search}%");
        }

        // Filter berdasarkan Kelas
        if ($request->filled('kelas')) {
            $query->where('id_kelas', $request->kelas);
        }

        $tugasList = $query->latest()->paginate(12)->withQueryString();
        $kelasOptions = Kelas::orderBy('nama_kelas')->pluck('nama_kelas');
        $mapelOptions = Mapel::orderBy('nama_mapel')->pluck('nama_mapel');
        $guru = auth()->user()->guru;

        return view('guru.tugas.index', compact('tugasList', 'kelasOptions', 'mapelOptions', 'guru'));
    }

    public function create()
    {
        return view('guru.tugas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'id_kelas' => 'required|string|max:255',
            'mata_pelajaran' => 'required|string|max:255',
            'instruksi' => 'required|string',
            'deadline' => 'required|date',
            'file_tugas' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,zip|max:12288', // Max 12MB
        ]);

        $data = [
            'guru_id' => auth()->user()->guru->id,
            'id_kelas' => $request->id_kelas,
            'mata_pelajaran' => $request->mata_pelajaran,
            'judul' => $request->judul,
            'instruksi' => $request->instruksi,
            'deadline' => $request->deadline,
        ];

        if ($request->hasFile('file_tugas')) {
            $data['file_tugas'] = $request->file('file_tugas')->store('tugas', 'public');
        }

        Tugas::create($data);

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $tugas = Tugas::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'id_kelas' => 'required|string|max:255',
            'mata_pelajaran' => 'required|string|max:255',
            'instruksi' => 'required|string',
            'deadline' => 'required|date',
            'file_tugas' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,zip|max:12288',
        ]);

        $data = [
            'judul' => $request->judul,
            'id_kelas' => $request->id_kelas,
            'mata_pelajaran' => $request->mata_pelajaran,
            'instruksi' => $request->instruksi,
            'deadline' => $request->deadline,
        ];

        if ($request->hasFile('file_tugas')) {
            // Delete old file if exists
            if ($tugas->file_tugas) {
                Storage::disk('public')->delete($tugas->file_tugas);
            }
            $data['file_tugas'] = $request->file('file_tugas')->store('tugas', 'public');
        }

        $tugas->update($data);

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil diperbarui');
    }

    public function destroy($id)
    {
        $tugas = Tugas::findOrFail($id);
        if ($tugas->file_tugas) {
            Storage::disk('public')->delete($tugas->file_tugas);
        }
        $tugas->delete();

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil dihapus');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:tugas,id'
        ]);

        $guruId = auth()->user()->guru->id;
        // Pastikan hanya bisa menghapus tugasnya sendiri
        $tugasList = Tugas::where('guru_id', $guruId)->whereIn('id', $request->ids)->get();

        foreach ($tugasList as $tugas) {
            if ($tugas->file_tugas) {
                Storage::disk('public')->delete($tugas->file_tugas);
            }
            $tugas->delete();
        }

        return redirect()->route('guru.tugas.index')->with('success', count($tugasList) . ' Tugas berhasil dihapus secara massal.');
    }

    public function indexSiswa(Request $request)
    {
        $siswaKelas = auth()->user()->siswa->id_kelas;
        $query = Tugas::where('id_kelas', $siswaKelas);

        // Pencarian (Search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('judul', 'like', "%{$search}%");
        }

        // Filter Status (Aktif vs Kedaluwarsa)
        if ($request->filled('status')) {
            if ($request->status == 'aktif') {
                $query->where('deadline', '>', now());
            } elseif ($request->status == 'kedaluwarsa') {
                $query->where('deadline', '<=', now());
            }
        }

        $tugasList = $query->latest()->paginate(12)->withQueryString();
        
        return view('siswa.tugas.index', compact('tugasList'));
    }

    public function showSiswa($id)
    {
        $tugas = Tugas::findOrFail($id);
        $siswaId = auth()->user()->siswa->id;
        $jawaban = JawabanTugas::where('tugas_id', $id)->where('siswa_id', $siswaId)->first();
        
        return view('siswa.tugas.show', compact('tugas', 'jawaban'));
    }

    public function uploadJawaban(Request $request, $id)
    {
        $request->validate([
            'file_jawaban' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:10240', // Max 10MB
        ]);

        $tugas = Tugas::findOrFail($id);
        
        if (now() > $tugas->deadline) {
            return back()->with('error', 'Waktu pengumpulan tugas sudah habis.');
        }

        $filePath = $request->file('file_jawaban')->store('jawaban', 'public');

        JawabanTugas::updateOrCreate(
            ['tugas_id' => $id, 'siswa_id' => auth()->user()->siswa->id],
            ['file_jawaban' => $filePath]
        );

        return back()->with('success', 'Jawaban berhasil diunggah.');
    }

    public function submissions($id)
    {
        $guruId = auth()->user()->guru->id;
        $tugas = Tugas::where('id', $id)->where('guru_id', $guruId)->firstOrFail();
        
        // Eager load jawaban and assigned siswa
        $jawabans = JawabanTugas::with('siswa.user')->where('tugas_id', $id)->get();

        return view('guru.tugas.submissions', compact('tugas', 'jawabans'));
    }

    public function grade(Request $request, $jawaban_id)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        $jawaban = JawabanTugas::findOrFail($jawaban_id);
        
        // Verify this belongs to current guru's tugas
        if ($jawaban->tugas->guru_id !== auth()->user()->guru->id) {
            abort(403, 'Unauthorized action.');
        }

        $jawaban->update([
            'nilai' => $request->nilai
        ]);

        return back()->with('success', 'Nilai berhasil disimpan!');
    }
}
