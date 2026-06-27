<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;

class KelasController extends Controller
{
    public function index()
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        return view('admin.kelas.index', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas'
        ]);

        Kelas::create(['nama_kelas' => $request->nama_kelas]);

        return back()->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas,' . $id
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update(['nama_kelas' => $request->nama_kelas]);

        return back()->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return back()->with('success', 'Kelas berhasil dihapus.');
    }
}
