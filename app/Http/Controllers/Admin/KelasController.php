<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKelasRequest;
use App\Http\Requests\UpdateKelasRequest;
use App\Models\Kelas;

class KelasController extends Controller
{
    public function index()
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('admin.kelas.index', compact('kelasList'));
    }

    public function store(StoreKelasRequest $request)
    {
        Kelas::create($request->validated());

        return back()->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function update(UpdateKelasRequest $request, $id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->update($request->validated());

        return back()->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return back()->with('success', 'Kelas berhasil dihapus.');
    }
}
