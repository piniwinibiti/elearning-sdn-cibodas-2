<?php

namespace App\Http\Controllers;

use App\Http\Requests\BulkDestroyMateriRequest;
use App\Http\Requests\StoreMateriRequest;
use App\Http\Requests\UpdateMateriRequest;
use App\Models\Kelas;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function indexGuru(Request $request)
    {
        $guruId = auth()->user()->guru->id;
        $query = Materi::where('guru_id', $guruId);

        // Pencarian (Search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('judul', 'like', "%{$search}%");
        }

        // Filter berdasarkan Kelas
        if ($request->filled('kelas')) {
            $query->where('id_kelas', $request->kelas);
        }

        // Filter berdasarkan Tipe
        if ($request->filled('tipe')) {
            $query->where('type', $request->tipe);
        }

        $materis = $query->latest()->paginate(10)->withQueryString();
        $guru = auth()->user()->guru;
        $kelasOptions = Kelas::orderBy('nama_kelas')->pluck('nama_kelas');
        $mapelOptions = $guru->mapelOptions();

        return view('guru.materi.index', compact('materis', 'kelasOptions', 'mapelOptions', 'guru'));
    }

    public function create()
    {
        return view('guru.materi.create');
    }

    public function store(StoreMateriRequest $request)
    {
        $filePath = $request->file('file_materi')->store('materis', 'public');

        Materi::create([
            'guru_id' => auth()->user()->guru->id,
            'id_kelas' => $request->id_kelas,
            'mata_pelajaran' => $request->mata_pelajaran,
            'judul' => $request->judul,
            'type' => $request->type,
            'file_path' => $filePath,
        ]);

        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil ditambahkan');
    }

    public function update(UpdateMateriRequest $request, $id)
    {
        $materi = Materi::where('guru_id', auth()->user()->guru->id)->findOrFail($id);

        $data = [
            'judul' => $request->judul,
            'id_kelas' => $request->id_kelas,
            'mata_pelajaran' => $request->mata_pelajaran,
            'type' => $request->type,
        ];

        if ($request->hasFile('file_materi')) {
            // Hapus file lama jika ada
            if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
                Storage::disk('public')->delete($materi->file_path);
            }
            $data['file_path'] = $request->file('file_materi')->store('materis', 'public');
        }

        $materi->update($data);

        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $materi = Materi::where('guru_id', auth()->user()->guru->id)->findOrFail($id);

        if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
            Storage::disk('public')->delete($materi->file_path);
        }

        $materi->delete();

        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil dihapus');
    }

    public function bulkDestroy(BulkDestroyMateriRequest $request)
    {
        $guruId = auth()->user()->guru->id;
        // Pastikan hanya bisa menghapus materinya sendiri
        $materis = Materi::where('guru_id', $guruId)->whereIn('id', $request->ids)->get();

        foreach ($materis as $materi) {
            if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
                Storage::disk('public')->delete($materi->file_path);
            }
            $materi->delete();
        }

        return redirect()->route('guru.materi.index')->with('success', count($materis).' Materi berhasil dihapus secara massal.');
    }

    public function indexSiswa()
    {
        $siswaKelas = auth()->user()->siswa->id_kelas;
        $materis = Materi::where('id_kelas', $siswaKelas)->latest()->get();

        return view('siswa.materi.index', compact('materis'));
    }
}
