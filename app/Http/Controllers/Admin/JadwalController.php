<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJadwalRequest;
use App\Http\Requests\UpdateJadwalRequest;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function getGuruMapels($id)
    {
        $guru = Guru::findOrFail($id);

        return response()->json($guru->mapelOptions());
    }

    public function index(Request $request)
    {
        $selectedKelas = $request->get('kelas');
        $selectedGuru = $request->get('guru_id');

        $jadwals = Jadwal::with('guru.user')
            ->when($selectedKelas, function ($q) use ($selectedKelas) {
                return $q->where('id_kelas', $selectedKelas);
            })
            ->when($selectedGuru, function ($q) use ($selectedGuru) {
                return $q->where('guru_id', $selectedGuru);
            })
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('jam_mulai')
            ->get();

        $gurus = Guru::with('user')->get();
        $kelasOptions = Kelas::orderBy('nama_kelas')->pluck('nama_kelas');
        $mapelOptions = Mapel::orderBy('nama_mapel')->pluck('nama_mapel');

        return view('admin.jadwal.index', compact('jadwals', 'gurus', 'kelasOptions', 'mapelOptions', 'selectedKelas', 'selectedGuru'));
    }

    public function store(StoreJadwalRequest $request)
    {
        Jadwal::create($request->validated());

        return back()->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    public function update(UpdateJadwalRequest $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $jadwal->update($request->validated());

        return back()->with('success', 'Jadwal pelajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return back()->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }
}
