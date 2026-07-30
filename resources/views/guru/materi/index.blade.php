@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Kelola Materi</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Daftar materi pembelajaran yang Anda unggah.</p>
</div>

<div class="flex flex-col sm:flex-row items-center justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 sm:mb-0">Daftar Materi</h2>
    <button data-modal-target="crud-modal-materi" data-modal-toggle="crud-modal-materi" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 flex items-center" type="button">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
        Tambah Materi Baru
    </button>
</div>

<!-- Modal Tambah Materi -->
<div id="crud-modal-materi" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Unggah Materi Baru
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal-materi">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Tutup modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form action="{{ route('guru.materi.store') }}" method="POST" enctype="multipart/form-data" class="p-4 md:p-5">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="judul" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul Materi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                </svg>
                            </div>
                            <input type="text" id="judul" name="judul" value="{{ old('judul') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Contoh: Pengantar Matematika" required>
                        </div>
                        <x-input-error name="judul" />
                    </div>

                    @if($guru->id_kelas_wali)
                        {{-- GURU KELAS: Lock Kelas, Open Mapel --}}
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelas</label>
                            <input type="text" value="Kelas {{ $guru->id_kelas_wali }}" class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-400" readonly>
                            <input type="hidden" name="id_kelas" value="{{ $guru->id_kelas_wali }}">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="mata_pelajaran" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mata Pelajaran</label>
                            <select id="mata_pelajaran" name="mata_pelajaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                <option value="" disabled {{ old('mata_pelajaran') ? '' : 'selected' }}>Pilih Mapel</option>
                                @foreach($mapelOptions as $mapel)
                                    <option value="{{ $mapel }}" {{ old('mata_pelajaran') == $mapel ? 'selected' : '' }}>{{ $mapel }}</option>
                                @endforeach
                            </select>
                            <x-input-error name="mata_pelajaran" />
                        </div>
                    @else
                        {{-- GURU SPESIALIS: Open Kelas, Lock Mapel --}}
                        <div class="col-span-2 sm:col-span-1">
                            <label for="id_kelas" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelas Tujuan</label>
                            <select id="id_kelas" name="id_kelas" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                <option value="" disabled {{ old('id_kelas') ? '' : 'selected' }}>Pilih Kelas</option>
                                @foreach($kelasOptions as $kls)
                                    <option value="{{ $kls }}" {{ old('id_kelas') == $kls ? 'selected' : '' }}>{{ $kls }}</option>
                                @endforeach
                            </select>
                            <x-input-error name="id_kelas" />
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="mata_pelajaran" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mata Pelajaran</label>
                            <select id="mata_pelajaran" name="mata_pelajaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                <option value="" disabled {{ old('mata_pelajaran') ? '' : 'selected' }}>Pilih Mapel</option>
                                @foreach($mapelOptions as $mapel)
                                    <option value="{{ $mapel }}" {{ old('mata_pelajaran') == $mapel ? 'selected' : '' }}>{{ $mapel }}</option>
                                @endforeach
                            </select>
                            <x-input-error name="mata_pelajaran" />
                        </div>
                    @endif

                    <div class="col-span-2 sm:col-span-1">
                        <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe File</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
                                    <path d="M14.066 0H7v5a2 2 0 0 1-2 2H0v11a1.97 1.97 0 0 0 1.934 2h12.132A1.97 1.97 0 0 0 16 18V2a1.97 1.97 0 0 0-1.934-2ZM10.5 6a1.5 1.5 0 1 1 0 2.999A1.5 1.5 0 0 1 10.5 6Zm2.221 10.515a1 1 0 0 1-.858.485h-8a1 1 0 0 1-.9-1.43L5.6 10.039a.978.978 0 0 1 .936-.57 1 1 0 0 1 .9.632l1.181 2.981.541-1a.945.945 0 0 1 .883-.522 1 1 0 0 1 .879.529l1.832 3.438a1 1 0 0 1-.031.988Z"/>
                                    <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.98 2.98 0 0 0 .13 5H5Z"/>
                                </svg>
                            </div>
                            <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                                <option value="pdf" {{ old('type') == 'pdf' ? 'selected' : '' }}>PDF Dokumen</option>
                                <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Video (MP4/MKV)</option>
                            </select>
                        </div>
                        <x-input-error name="type" />
                    </div>

                    <div class="col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_materi">Unggah File (Max 20MB)</label>
                        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="file_materi" name="file_materi" type="file" required>
                        <x-input-error name="file_materi" />
                    </div>
                </div>
                <button type="submit" class="text-white inline-flex items-center w-full justify-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="me-1 -ms-1 w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Unggah File Materi
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Control Bar (Filter & Search & Bulk Action) -->
<div class="flex flex-col mb-4 space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
    <div class="flex flex-col space-y-4 md:flex-row md:space-x-4 md:space-y-0 text-sm">
        <form id="bulk-delete-form" action="{{ route('guru.materi.bulk_destroy') }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" id="bulk-delete-btn" class="hidden text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 text-center items-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Hapus Terpilih (<span id="selected-count">0</span>)
            </button>
        </form>

        <form action="{{ route('guru.materi.index') }}" method="GET" class="flex flex-col space-y-4 md:flex-row md:space-x-4 md:space-y-0">
            <!-- Filter Kelas -->
            <select name="kelas" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full md:w-auto p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($kelasOptions as $kelas)
                    <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>Kelas {{ $kelas }}</option>
                @endforeach
            </select>
            
            <!-- Filter Tipe -->
            <select name="tipe" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full md:w-auto p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" onchange="this.form.submit()">
                <option value="">Semua Tipe</option>
                <option value="pdf" {{ request('tipe') == 'pdf' ? 'selected' : '' }}>PDF</option>
                <option value="video" {{ request('tipe') == 'video' ? 'selected' : '' }}>Video</option>
            </select>
            
            <!-- Search -->
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" id="search" class="block w-full md:w-64 p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari Judul Materi...">
            </div>
            
            @if(request('search') || request('kelas') || request('tipe'))
                <a href="{{ route('guru.materi.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-4 py-2 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600 text-center">Clear</a>
            @endif
        </form>
    </div>
</div>
<!-- Tabel Daftar Materi -->
<div class="relative overflow-x-auto shadow-sm sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="p-4">
                    <div class="flex items-center">
                        <input id="checkbox-all" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-all" class="sr-only">checkbox</label>
                    </div>
                </th>
                <th scope="col" class="px-6 py-3">Materi</th>
                <th scope="col" class="px-6 py-3">Mapel</th>
                <th scope="col" class="px-6 py-3">Kelas</th>
                <th scope="col" class="px-6 py-3">Tipe</th>
                <th scope="col" class="px-6 py-3">Tanggal Unggah</th>
                <th scope="col" class="px-6 py-3 border-s dark:border-gray-700">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($materis as $materi)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="w-4 p-4">
                    <div class="flex items-center">
                        <input form="bulk-delete-form" id="checkbox-table-{{ $materi->id }}" type="checkbox" name="ids[]" value="{{ $materi->id }}" class="item-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-table-{{ $materi->id }}" class="sr-only">checkbox</label>
                    </div>
                </td>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <a href="{{ Storage::url($materi->file_path) }}" target="_blank" class="hover:text-blue-600 hover:underline">
                        {{ $materi->judul }}
                    </a>
                </th>
                <td class="px-6 py-4">
                    <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-purple-900 dark:text-purple-300">{{ $materi->mata_pelajaran }}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Kelas {{ $materi->id_kelas }}</span>
                </td>
                <td class="px-6 py-4 uppercase font-mono text-xs">{{ $materi->type }}</td>
                <td class="px-6 py-4">{{ $materi->created_at->format('d M Y') }}</td>
                <td class="px-6 py-4 border-s dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <button data-modal-target="edit-modal-materi-{{ $materi->id }}" data-modal-toggle="edit-modal-materi-{{ $materi->id }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline flex items-center" type="button">
                            <svg class="w-4 h-4 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m13.835 7.578-.005.007-7.137 7.137 2.139 2.138 7.143-7.142-2.14-2.14Zm-10.696 3.59 2.139 2.14 7.138-7.137.002-.002-2.136-2.138-7.143 7.137ZM15.969 5.44 14.56 4.032a1.5 1.5 0 0 0-2.122 0l-.888.888 2.138 2.138.888-.888a1.5 1.5 0 0 0 0-2.122Z"/></svg>
                            Edit
                        </button>
                        
                        <form action="{{ route('guru.materi.destroy', $materi->id) }}" method="POST" class="inline-block form-delete">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline flex items-center">
                                <svg class="w-4 h-4 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h16M7 8v8m4-8v8M7 1h4a1 1 0 0 1 1 1v3H6V2a1 1 0 0 1 1-1ZM3 5h12v13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5Z"/></svg>
                                Hapus
                            </button>
                        </form>
                    </div>

                    <!-- Modal Edit Materi -->
                    <div id="edit-modal-materi-{{ $materi->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[60] justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-md max-h-full">
                            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                                <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Perbarui Materi</h3>
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="edit-modal-materi-{{ $materi->id }}">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                                        <span class="sr-only">Tutup modal</span>
                                    </button>
                                </div>
                                <form action="{{ route('guru.materi.update', $materi->id) }}" method="POST" enctype="multipart/form-data" class="p-4">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid gap-4 mb-4 grid-cols-2">
                                        <div class="col-span-2">
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul Materi</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/></svg>
                                                </div>
                                                <input type="text" name="judul" value="{{ old('judul', $materi->judul) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full ps-10 p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                                            </div>
                                            <x-input-error name="judul" />
                                        </div>
                                        @if($guru->id_kelas_wali)
                                            {{-- GURU KELAS: Lock Kelas, Open Mapel --}}
                                            <div class="col-span-2 sm:col-span-1">
                                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelas</label>
                                                <input type="text" value="Kelas {{ $guru->id_kelas_wali }}" class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-400" readonly>
                                                <input type="hidden" name="id_kelas" value="{{ $guru->id_kelas_wali }}">
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <label for="mata_pelajaran_{{ $materi->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mata Pelajaran</label>
                                                <select id="mata_pelajaran_{{ $materi->id }}" name="mata_pelajaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                                    @foreach($mapelOptions as $mapel)
                                                        <option value="{{ $mapel }}" {{ old('mata_pelajaran', $materi->mata_pelajaran) == $mapel ? 'selected' : '' }}>{{ $mapel }}</option>
                                                    @endforeach
                                                </select>
                                                <x-input-error name="mata_pelajaran" />
                                            </div>
                                        @else
                                            {{-- GURU SPESIALIS: Open Kelas, Lock Mapel --}}
                                            <div class="col-span-2 sm:col-span-1">
                                                <label for="id_kelas_{{ $materi->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelas Tujuan</label>
                                                <select id="id_kelas_{{ $materi->id }}" name="id_kelas" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                                    @foreach($kelasOptions as $kls)
                                                        <option value="{{ $kls }}" {{ old('id_kelas', $materi->id_kelas) == $kls ? 'selected' : '' }}>{{ $kls }}</option>
                                                    @endforeach
                                                </select>
                                                <x-input-error name="id_kelas" />
                                            </div>
                                            <div class="col-span-2 sm:col-span-1">
                                                <label for="mata_pelajaran_{{ $materi->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mata Pelajaran</label>
                                                <select id="mata_pelajaran_{{ $materi->id }}" name="mata_pelajaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                                    @foreach($mapelOptions as $mapel)
                                                        <option value="{{ $mapel }}" {{ old('mata_pelajaran', $materi->mata_pelajaran) == $mapel ? 'selected' : '' }}>{{ $mapel }}</option>
                                                    @endforeach
                                                </select>
                                                <x-input-error name="mata_pelajaran" />
                                            </div>
                                        @endif
                                        <div class="col-span-2 sm:col-span-1">
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe</label>
                                            <select name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                                                <option value="pdf" {{ old('type', $materi->type) == 'pdf' ? 'selected' : '' }}>PDF</option>
                                                <option value="video" {{ old('type', $materi->type) == 'video' ? 'selected' : '' }}>Video</option>
                                            </select>
                                            <x-input-error name="type" />
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">File Baru (Kosongkan bila tidak diganti)</label>
                                            <input name="file_materi" type="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-600 dark:border-gray-500">
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Harap upload PDF atau video max 20MB.</p>
                                            <x-input-error name="file_materi" />
                                        </div>
                                    </div>
                                    <button type="submit" class="text-white inline-flex items-center w-full justify-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        Simpan Perubahan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                    Tidak ada materi yang ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $materis->links() }}
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxAll = document.getElementById('checkbox-all');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
        const selectedCountSpan = document.getElementById('selected-count');

        function updateBulkDeleteButton() {
            const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
            selectedCountSpan.textContent = checkedCount;
            
            if (checkedCount > 0) {
                bulkDeleteBtn.classList.remove('hidden');
                bulkDeleteBtn.classList.add('inline-flex');
            } else {
                bulkDeleteBtn.classList.add('hidden');
                bulkDeleteBtn.classList.remove('inline-flex');
            }
        }

        if(checkboxAll) {
            checkboxAll.addEventListener('change', function() {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkDeleteButton();
            });
        }

        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
                if(checkboxAll) {
                    checkboxAll.checked = checkedCount === itemCheckboxes.length;
                }
                updateBulkDeleteButton();
            });
        });
    });
</script>
@endpush

@endsection
