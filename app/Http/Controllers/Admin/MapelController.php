<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMapelRequest;
use App\Http\Requests\UpdateMapelRequest;
use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $mapels = Mapel::when($search, function ($query, $search) {
            return $query->where('nama_mapel', 'like', "%{$search}%")
                ->orWhere('kode', 'like', "%{$search}%");
        })->orderBy('nama_mapel')->get();

        return view('admin.mapel.index', compact('mapels', 'search'));
    }

    public function store(StoreMapelRequest $request)
    {
        Mapel::create($request->validated());

        return back()->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function update(UpdateMapelRequest $request, $id)
    {
        $mapel = Mapel::findOrFail($id);
        $mapel->update($request->validated());

        return back()->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $mapel = Mapel::findOrFail($id);
        $mapel->delete();

        return back()->with('success', 'Mata Pelajaran berhasil dihapus.');
    }
}
