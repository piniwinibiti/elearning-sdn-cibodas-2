<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mapel;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $mapels = Mapel::when($search, function($query, $search) {
            return $query->where('nama_mapel', 'like', "%{$search}%")
                        ->orWhere('kode', 'like', "%{$search}%");
        })->orderBy('nama_mapel')->get();
        
        return view('admin.mapel.index', compact('mapels', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:20|unique:mapels,kode',
            'nama_mapel' => 'required|string|max:100|unique:mapels,nama_mapel'
        ]);

        Mapel::create([
            'kode' => strtoupper($request->kode),
            'nama_mapel' => $request->nama_mapel
        ]);

        return back()->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:20|unique:mapels,kode,' . $id,
            'nama_mapel' => 'required|string|max:100|unique:mapels,nama_mapel,' . $id
        ]);

        $mapel = Mapel::findOrFail($id);
        $mapel->update([
            'kode' => strtoupper($request->kode),
            'nama_mapel' => $request->nama_mapel
        ]);

        return back()->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $mapel = Mapel::findOrFail($id);
        $mapel->delete();

        return back()->with('success', 'Mata Pelajaran berhasil dihapus.');
    }
}
