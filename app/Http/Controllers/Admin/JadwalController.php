<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\GuruMapel;

class JadwalController extends Controller
{
    public function getGuruMapels($id)
    {
        $mapels = GuruMapel::where('guru_id', $id)->pluck('nama_mapel');
        return response()->json($mapels);
    }

    public function index(Request $request)
    {
        $selectedKelas = $request->get('kelas');
        $selectedGuru = $request->get('guru_id');

        $jadwals = Jadwal::with('guru.user')
            ->when($selectedKelas, function($q) use ($selectedKelas) {
                return $q->where('id_kelas', $selectedKelas);
            })
            ->when($selectedGuru, function($q) use ($selectedGuru) {
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

    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'id_kelas' => 'required|string',
            'nama_mapel' => 'required|string',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        Jadwal::create($request->all());

        return back()->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'id_kelas' => 'required|string',
            'nama_mapel' => 'required|string',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        $jadwal->update($request->all());

        return back()->with('success', 'Jadwal pelajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return back()->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }
}
