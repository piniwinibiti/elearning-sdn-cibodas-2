@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Atur Jadwal Pelajaran</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola jadwal mengajar guru per hari dan waktu.</p>
</div>

@if(session('success'))
<div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
    {{ session('success') }}
</div>
@endif

<div class="flex flex-col sm:flex-row items-center justify-between mb-6 gap-4">
    <form action="{{ route('admin.jadwal.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
        <select name="kelas" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">Semua Kelas</option>
            @foreach($kelasOptions as $kls)
                <option value="{{ $kls }}" {{ $selectedKelas == $kls ? 'selected' : '' }}>Kelas {{ $kls }}</option>
            @endforeach
        </select>
        <select name="guru_id" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">Semua Guru</option>
            @foreach($gurus as $g)
                <option value="{{ $g->id }}" {{ $selectedGuru == $g->id ? 'selected' : '' }}>{{ $g->user->nama_lengkap }}</option>
            @endforeach
        </select>
    </form>

    <button data-modal-target="modal-tambah-jadwal" data-modal-toggle="modal-tambah-jadwal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
        Tambah Jadwal
    </button>
</div>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th class="px-6 py-3">Hari</th>
                <th class="px-6 py-3">Waktu</th>
                <th class="px-6 py-3">Kelas</th>
                <th class="px-6 py-3">Mata Pelajaran</th>
                <th class="px-6 py-3">Guru Pengajar</th>
                <th class="px-6 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwals as $j)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $j->hari }}</td>
                <td class="px-6 py-4 font-mono">{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</td>
                <td class="px-6 py-4">Kelas {{ $j->id_kelas }}</td>
                <td class="px-6 py-4">{{ $j->nama_mapel }}</td>
                <td class="px-6 py-4">{{ $j->guru->user->nama_lengkap }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <button data-modal-target="modal-edit-jadwal-{{ $j->id }}" data-modal-toggle="modal-edit-jadwal-{{ $j->id }}" class="text-blue-600 hover:underline font-medium">Edit</button>
                        <form action="{{ route('admin.jadwal.destroy', $j->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline font-medium">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-10 text-center text-gray-500">Belum ada jadwal yang diatur untuk filter ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah -->
<div id="modal-tambah-jadwal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 border dark:border-gray-600">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Jadwal Pelajaran</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="modal-tambah-jadwal">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                </button>
            </div>
            <form action="{{ route('admin.jadwal.store') }}" method="POST" class="p-4 md:p-5">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Guru</label>
                        <select name="guru_id" id="guru_select_tambah" required class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">-- Pilih Guru --</option>
                            @foreach($gurus as $g)
                                <option value="{{ $g->id }}">{{ $g->user->nama_lengkap }} ({{ $g->nip }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelas</label>
                        <select name="id_kelas" required class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @foreach($kelasOptions as $kls)
                                <option value="{{ $kls }}">Kelas {{ $kls }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mapel</label>
                        <select name="nama_mapel" id="mapel_select_tambah" required class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">-- Pilih Guru Dulu --</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hari</label>
                        <select name="hari" required class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                                <option value="{{ $h }}">{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam Mulai</label>
                        <input type="time" name="jam_mulai" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div class="col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam Selesai</label>
                        <input type="time" name="jam_selesai" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>
                <button type="submit" class="text-white w-full inline-flex items-center justify-center bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Simpan Jadwal
                </button>
            </form>
        </div>
    </div>
</div>

@foreach($jadwals as $j)
<!-- Modal Edit {{ $j->id }} -->
<div id="modal-edit-jadwal-{{ $j->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 border dark:border-gray-600">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Jadwal Pelajaran</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="modal-edit-jadwal-{{ $j->id }}">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                </button>
            </div>
            <form action="{{ route('admin.jadwal.update', $j->id) }}" method="POST" class="p-4 md:p-5">
                @csrf @method('PUT')
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Guru</label>
                        <select name="guru_id" required class="guru-select-edit w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" data-target="mapel-select-edit-{{ $j->id }}" data-current="{{ $j->nama_mapel }}">
                            @foreach($gurus as $g)
                                <option value="{{ $g->id }}" {{ $j->guru_id == $g->id ? 'selected' : '' }}>{{ $g->user->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelas</label>
                        <select name="id_kelas" required class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @foreach($kelasOptions as $kls)
                                <option value="{{ $kls }}" {{ $j->id_kelas == $kls ? 'selected' : '' }}>Kelas {{ $kls }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mapel</label>
                        <select name="nama_mapel" id="mapel-select-edit-{{ $j->id }}" required class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="{{ $j->nama_mapel }}">{{ $j->nama_mapel }}</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hari</label>
                        <select name="hari" required class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                                <option value="{{ $h }}" {{ $j->hari == $h ? 'selected' : '' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam Mulai</label>
                        <input type="time" name="jam_mulai" value="{{ substr($j->jam_mulai, 0, 5) }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div class="col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam Selesai</label>
                        <input type="time" name="jam_selesai" value="{{ substr($j->jam_selesai, 0, 5) }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>
                <button type="submit" class="text-white w-full inline-flex items-center justify-center bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Perbarui Jadwal
                </button>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const guruSelectTambah = document.getElementById('guru_select_tambah');
        const mapelSelectTambah = document.getElementById('mapel_select_tambah');

        function updateMapels(guruId, mapelSelect, currentMapel = null) {
            if (!guruId) {
                mapelSelect.innerHTML = '<option value="">-- Pilih Guru Dulu --</option>';
                return;
            }

            mapelSelect.innerHTML = '<option value="">Loading...</option>';

            fetch(`/admin/jadwal/guru-mapels/${guruId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Request gagal');
                    return response.json();
                })
                .then(data => {
                    if (!data.length) {
                        mapelSelect.innerHTML = '<option value="">-- Guru ini belum ada mapel --</option>';
                        return;
                    }

                    mapelSelect.innerHTML = '<option value="">-- Pilih Mapel --</option>';
                    data.forEach(m => {
                        const option = document.createElement('option');
                        option.value = m;
                        option.textContent = m;
                        if (currentMapel === m) option.selected = true;
                        mapelSelect.appendChild(option);
                    });
                })
                .catch(() => {
                    mapelSelect.innerHTML = '<option value="">-- Gagal memuat mapel --</option>';
                });
        }

        guruSelectTambah.addEventListener('change', function() {
            updateMapels(this.value, mapelSelectTambah);
        });

        // Handle Edit Modals
        const guruSelectsEdit = document.querySelectorAll('.guru-select-edit');
        guruSelectsEdit.forEach(select => {
            const targetId = select.getAttribute('data-target');
            const mapelSelect = document.getElementById(targetId);
            const currentMapel = select.getAttribute('data-current');

            // Initial load for edit modals (to populate if many mapels)
            if (select.value) {
                updateMapels(select.value, mapelSelect, currentMapel);
            }

            select.addEventListener('change', function() {
                updateMapels(this.value, mapelSelect);
            });
        });
    });
</script>

@endsection
