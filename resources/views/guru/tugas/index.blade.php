@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Kelola Tugas</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Daftar tugas yang telah Anda buat untuk siswa.</p>
</div>

@if(session('success'))
<div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
  {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
  <ul class="list-disc pl-5">
      @foreach($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
  </ul>
</div>
@endif

<div class="flex flex-col sm:flex-row items-center justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 sm:mb-0">Daftar Tugas</h2>
    <button data-modal-target="crud-modal-tugas" data-modal-toggle="crud-modal-tugas" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 flex items-center" type="button">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
        Tambah Tugas Baru
    </button>
</div>

<!-- Modal Tambah Tugas -->
<div id="crud-modal-tugas" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Buat Tugas Baru
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal-tugas">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    <span class="sr-only">Tutup modal</span>
                </button>
            </div>
            <form action="{{ route('guru.tugas.store') }}" method="POST" enctype="multipart/form-data" class="p-4 md:p-5">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="judul" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul Tugas</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/></svg>
                            </div>
                            <input type="text" id="judul" name="judul" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Kerjakan LKS Hal. 10" required>
                        </div>
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
                                <option value="" disabled selected>Pilih Mapel</option>
                                @foreach($mapelOptions as $mapel)
                                    <option value="{{ $mapel }}">{{ $mapel }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        {{-- GURU SPESIALIS: Open Kelas, Lock Mapel --}}
                        <div class="col-span-2 sm:col-span-1">
                            <label for="id_kelas" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelas Tujuan</label>
                            <select id="id_kelas" name="id_kelas" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                <option value="" disabled selected>Pilih Kelas</option>
                                @foreach($kelasOptions as $kls)
                                    <option value="{{ $kls }}">{{ $kls }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="mata_pelajaran" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mata Pelajaran</label>
                            <select id="mata_pelajaran" name="mata_pelajaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                <option value="" disabled selected>Pilih Mapel</option>
                                @foreach($mapelOptions as $mapel)
                                    <option value="{{ $mapel }}">{{ $mapel }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-span-2 sm:col-span-1">
                        <label for="deadline" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Batas Pengumpulan</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/></svg>
                            </div>
                            <input type="datetime-local" id="deadline" name="deadline" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                        </div>
                    </div>

                    <div class="col-span-2">
                        <label for="instruksi" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Detail Instruksi</label>
                        <textarea id="instruksi" name="instruksi" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Tulis instruksi lengkap tugas di sini..." required></textarea>
                    </div>

                    <div class="col-span-2">
                        <label for="file_tugas" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Lampiran File (Opsional)</label>
                        <input type="file" id="file_tugas" name="file_tugas" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PDF, Word, Image, atau ZIP (Maks 12MB)</p>
                    </div>
                </div>
                <button type="submit" class="text-white inline-flex items-center w-full justify-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="me-1 -ms-1 w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Distribusikan Tugas
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Control Bar (Filter & Search & Bulk Action) -->
<div class="flex flex-col mb-4 space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
    <div class="flex flex-col space-y-4 md:flex-row md:space-x-4 md:space-y-0 text-sm">
        <form id="bulk-delete-form" action="{{ route('guru.tugas.bulk_destroy') }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" id="bulk-delete-btn" class="hidden text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 text-center items-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Hapus Terpilih (<span id="selected-count">0</span>)
            </button>
        </form>

        <form action="{{ route('guru.tugas.index') }}" method="GET" class="flex flex-col space-y-4 md:flex-row md:space-x-4 md:space-y-0">
            <!-- Filter Kelas -->
            <select name="kelas" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full md:w-auto p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($kelasOptions as $kelas)
                    <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>Kelas {{ $kelas }}</option>
                @endforeach
            </select>
            
            <!-- Search -->
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" id="search" class="block w-full md:w-64 p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari Judul Tugas...">
            </div>
            
            @if(request('search') || request('kelas'))
                <a href="{{ route('guru.tugas.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-4 py-2 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600 text-center">Clear</a>
            @endif
        </form>
    </div>
</div>

<div class="flex items-center mb-4">
    <input id="checkbox-all" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
    <label for="checkbox-all" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Pilih Semua Tugas</label>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($tugasList as $tugas)
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 relative">
        <div class="flex justify-between items-start mb-4">
            <div class="flex items-center space-x-2">
                <input form="bulk-delete-form" id="checkbox-item-{{ $tugas->id }}" type="checkbox" name="ids[]" value="{{ $tugas->id }}" class="item-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{ $tugas->id_kelas }}</span>
                <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-purple-900 dark:text-purple-300">{{ $tugas->mata_pelajaran }}</span>
            </div>
            <span class="text-xs font-medium text-red-600 dark:text-red-400 flex items-center bg-red-50 dark:bg-gray-700 px-2 py-1 rounded">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y H:i') }}
            </span>
        </div>
        <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $tugas->judul }}</h5>
        <p class="mb-4 font-normal text-gray-700 dark:text-gray-400 line-clamp-3 ext-sm">{{ $tugas->instruksi }}</p>
        
        <div class="flex items-center justify-between border-t border-gray-100 dark:border-gray-700 pt-4 mt-auto">
            <a href="{{ route('guru.tugas.submissions', $tugas->id) }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-500 dark:hover:text-blue-400">
                Lihat Jawaban
                <span class="inline-flex items-center justify-center w-5 h-5 ms-2 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">
                    {{ $tugas->jawaban->count() }}
                </span>
            </a>
            
            <div class="flex items-center space-x-2">
                <button data-modal-target="edit-modal-tugas-{{ $tugas->id }}" data-modal-toggle="edit-modal-tugas-{{ $tugas->id }}" type="button" class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </button>
                <form action="{{ route('guru.tugas.destroy', $tugas->id) }}" method="POST" class="inline-block form-delete">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Tugas -->
    <div id="edit-modal-tugas-{{ $tugas->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[60] justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Tugas</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="edit-modal-tugas-{{ $tugas->id }}">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                        <span class="sr-only">Tutup modal</span>
                    </button>
                </div>
                <form action="{{ route('guru.tugas.update', $tugas->id) }}" method="POST" enctype="multipart/form-data" class="p-4">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul Tugas</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/></svg>
                                </div>
                                <input type="text" name="judul" value="{{ $tugas->judul }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full ps-10 p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                            </div>
                        </div>
                        @if($guru->id_kelas_wali)
                            {{-- GURU KELAS: Lock Kelas, Open Mapel --}}
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelas</label>
                                <input type="text" value="Kelas {{ $guru->id_kelas_wali }}" class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-400" readonly>
                                <input type="hidden" name="id_kelas" value="{{ $guru->id_kelas_wali }}">
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label for="mata_pelajaran_{{ $tugas->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mata Pelajaran</label>
                                <select id="mata_pelajaran_{{ $tugas->id }}" name="mata_pelajaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                    @foreach($mapelOptions as $mapel)
                                        <option value="{{ $mapel }}" {{ $tugas->mata_pelajaran == $mapel ? 'selected' : '' }}>{{ $mapel }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            {{-- GURU SPESIALIS: Open Kelas, Lock Mapel --}}
                            <div class="col-span-2 sm:col-span-1">
                                <label for="id_kelas_{{ $tugas->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelas Tujuan</label>
                                <select id="id_kelas_{{ $tugas->id }}" name="id_kelas" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                    @foreach($kelasOptions as $kls)
                                        <option value="{{ $kls }}" {{ $tugas->id_kelas == $kls ? 'selected' : '' }}>{{ $kls }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label for="mata_pelajaran_{{ $tugas->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mata Pelajaran</label>
                                <select id="mata_pelajaran_{{ $tugas->id }}" name="mata_pelajaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                    @foreach($mapelOptions as $mapel)
                                        <option value="{{ $mapel }}" {{ $tugas->mata_pelajaran == $mapel ? 'selected' : '' }}>{{ $mapel }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Batas Pengumpulan</label>
                            <input type="datetime-local" name="deadline" value="{{ date('Y-m-d\TH:i', strtotime($tugas->deadline)) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Instruksi</label>
                            <textarea name="instruksi" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>{{ $tugas->instruksi }}</textarea>
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Lampiran File</label>
                            @if($tugas->file_tugas)
                                <div class="flex items-center p-2 mb-2 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800">
                                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z"/></svg>
                                    <div>
                                        File saat ini: <a href="{{ Storage::url($tugas->file_tugas) }}" target="_blank" class="font-bold underline">Download</a>
                                    </div>
                                </div>
                            @endif
                            <input type="file" name="file_tugas" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pilih file baru untuk mengganti lampiran lama.</p>
                        </div>
                    </div>
                    <button type="submit" class="text-white w-full inline-flex items-center justify-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 text-center">
        <p class="text-gray-500 dark:text-gray-400">Tidak ada tugas yang ditemukan.</p>
    </div>
    @endforelse
    </div>

<div class="mt-4">
    {{ $tugasList->links() }}
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
