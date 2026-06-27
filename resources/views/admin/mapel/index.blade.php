@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Page Header & Breadcrumbs -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white transition">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            Beranda
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Kelola Mapel</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl dark:text-white">
                Kelola Mata Pelajaran
            </h1>
            <p class="mt-2 text-base text-gray-500 dark:text-gray-400 italic">
                Pusat manajemen master data mata pelajaran untuk kurikulum sekolah.
            </p>
        </div>
        
        <!-- Stats Card -->
        <div class="flex items-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 rounded-2xl dark:from-gray-800 dark:to-gray-900 dark:border-gray-700 shadow-sm transition-all hover:shadow-md group">
            <div class="p-3 mr-4 text-blue-600 bg-blue-100 rounded-xl group-hover:bg-blue-200 transition-colors">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-blue-600 uppercase tracking-wider dark:text-blue-400">Total Mapel</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $mapels->count() }} Subjek</p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div id="alert-success" class="flex items-center p-4 mb-4 text-green-800 border-t-4 border-green-300 bg-green-50 dark:text-green-400 dark:bg-gray-800 dark:border-green-800 rounded-lg shadow-sm animate-pulse-once" role="alert">
        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <div class="ms-3 text-sm font-medium">
            <span class="font-bold">Berhasil!</span> {{ session('success') }}
        </div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-success" aria-label="Close">
          <span class="sr-only">Dismiss</span>
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
          </svg>
        </button>
    </div>
    @endif

    <!-- Main Content Card -->
    <div class="bg-white border border-gray-100 rounded-3xl shadow-xl dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
        <!-- Search & Actions Bar -->
        <div class="p-5 border-b border-gray-50 dark:border-gray-700">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <form action="{{ route('admin.mapel.index') }}" method="GET" class="relative group w-full sm:max-w-md">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari kode atau nama mata pelajaran..." 
                        class="block w-full p-3 pl-11 text-sm text-gray-900 border border-gray-200 rounded-2xl bg-gray-50 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 focus:bg-white dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-900 transition-all outline-none shadow-sm">
                    @if($search)
                    <a href="{{ route('admin.mapel.index') }}" class="absolute inset-y-0 right-0 flex items-center pr-3 group-hover:opacity-100 opacity-60">
                        <svg class="w-5 h-5 text-gray-400 hover:text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                    </a>
                    @endif
                </form>
                
                <button type="button" data-modal-target="addMapelModal" data-modal-toggle="addMapelModal" class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-4 focus:ring-blue-200 shadow-lg shadow-blue-500/20 active:scale-95 transition-all w-full sm:w-auto">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Mapel Baru
                </button>
            </div>
        </div>

        <div class="overflow-x-auto min-h-[400px]">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50/50 dark:bg-gray-700/50 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-8 py-5 font-bold tracking-widest uppercase">No.</th>
                        <th scope="col" class="px-8 py-5 font-bold tracking-widest uppercase">Kode</th>
                        <th scope="col" class="px-8 py-5 font-bold tracking-widest uppercase">Nama Mata Pelajaran</th>
                        <th scope="col" class="px-8 py-5 font-bold tracking-widest uppercase text-right">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse($mapels as $index => $mapel)
                    <tr class="group hover:bg-blue-50/40 dark:hover:bg-gray-700/40 transition-all">
                        <td class="px-8 py-5 font-medium text-gray-400 group-hover:text-blue-600 transition-colors">{{ ($mapels instanceof \Illuminate\Pagination\LengthAwarePaginator) ? $mapels->firstItem() + $index : $index + 1 }}</td>
                        <td class="px-8 py-5">
                            <span class="px-3 py-1 text-xs font-extrabold text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-900/40 dark:text-blue-300 tracking-wider">
                                {{ $mapel->kode ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center">
                                <span class="flex items-center justify-center w-8 h-8 mr-3 text-sm font-bold text-blue-600 bg-blue-100 rounded-lg dark:bg-blue-900/30 dark:text-blue-400 group-hover:scale-110 transition-transform uppercase">
                                    {{ substr($mapel->nama_mapel, 0, 1) }}
                                </span>
                                <span class="text-base font-bold text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors">
                                    {{ $mapel->nama_mapel }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center justify-end space-x-3 opacity-0 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0">
                                <button type="button" data-modal-target="editMapelModal-{{ $mapel->id }}" data-modal-toggle="editMapelModal-{{ $mapel->id }}" 
                                    class="p-2 text-amber-500 bg-amber-50 hover:bg-amber-500 hover:text-white rounded-xl dark:bg-gray-700 dark:hover:bg-amber-600 transition-all shadow-sm" title="Edit Mapel">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <form action="{{ route('admin.mapel.destroy', $mapel->id) }}" method="POST" class="inline-block form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 bg-red-50 hover:bg-red-500 hover:text-white rounded-xl dark:bg-gray-700 dark:hover:bg-red-600 transition-all shadow-sm" title="Hapus Mapel">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div id="editMapelModal-{{ $mapel->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative w-full max-w-md max-h-full">
                            <form action="{{ route('admin.mapel.update', $mapel->id) }}" method="POST" class="relative bg-white rounded-3xl shadow-2xl dark:bg-gray-800 border-0 overflow-hidden">
                                @csrf
                                @method('PUT')
                                <div class="bg-gradient-to-r from-amber-500 to-orange-500 p-6 flex justify-between items-center">
                                    <div class="flex items-center space-x-3 text-white">
                                        <div class="p-2 bg-white/20 rounded-xl backdrop-blur-md">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </div>
                                        <h3 class="text-xl font-bold">Edit Mapel</h3>
                                    </div>
                                    <button type="button" class="text-white/80 hover:text-white bg-white/10 hover:bg-white/20 rounded-full p-1.5 transition-colors" data-modal-toggle="editMapelModal-{{ $mapel->id }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                                <div class="p-8 space-y-5">
                                    <div>
                                        <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Kode Mapel</label>
                                        <input type="text" name="kode" value="{{ $mapel->kode }}" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-2xl focus:ring-amber-500 focus:border-amber-500 block w-full p-4 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-inner uppercase font-mono font-bold" placeholder="MISAL: MTK" required>
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Nama Mata Pelajaran</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            </div>
                                            <input type="text" name="nama_mapel" value="{{ $mapel->nama_mapel }}" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-2xl focus:ring-amber-500 focus:border-amber-500 block w-full p-4 pl-12 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-inner uppercase font-semibold" required>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <button type="submit" class="flex-1 text-white bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 focus:ring-4 focus:ring-amber-200 font-bold rounded-2xl text-base px-6 py-4 text-center transition-all shadow-lg active:scale-95">Simpan Perubahan</button>
                                        <button data-modal-toggle="editMapelModal-{{ $mapel->id }}" type="button" class="px-6 py-4 text-sm font-bold text-gray-500 bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 rounded-2xl transition-all border border-transparent hover:border-gray-200">Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="bg-blue-50 dark:bg-blue-900/20 p-8 rounded-full mb-6">
                                    <svg class="w-20 h-20 text-blue-200 dark:text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                </div>
                                @if($search)
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Pencarian Tidak Ditemukan</h3>
                                    <p class="text-gray-500 max-w-xs">Tidak ada mata pelajaran yang cocok dengan kata kunci <span class="text-blue-600 font-bold">"{{ $search }}"</span>.</p>
                                    <a href="{{ route('admin.mapel.index') }}" class="mt-6 text-blue-600 font-bold hover:underline flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                        Kembali Tampilkan Semua
                                    </a>
                                @else
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Belum Ada Mata Pelajaran</h3>
                                    <p class="text-gray-500 max-w-xs">Mulai kelola kurikulum dengan menambahkan mata pelajaran pertama Anda sekarang!</p>
                                    <button type="button" data-modal-target="addMapelModal" data-modal-toggle="addMapelModal" class="mt-6 inline-flex items-center px-6 py-3 text-sm font-bold text-blue-600 bg-blue-50 rounded-2xl hover:bg-blue-100 transition-all border border-blue-100">
                                        Tambah Mapel Sekarang
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($mapels instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="px-8 py-5 border-t border-gray-50 dark:border-gray-700 bg-gray-50/30">
            {{ $mapels->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Tambah -->
<div id="addMapelModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full">
        <form action="{{ route('admin.mapel.store') }}" method="POST" class="relative bg-white rounded-3xl shadow-2xl dark:bg-gray-800 border-0 overflow-hidden">
            @csrf
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 flex justify-between items-center text-white">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-white/20 rounded-xl backdrop-blur-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold italic tracking-tight uppercase">Tambah Mapel Baru</h3>
                </div>
                <button type="button" class="text-white/80 hover:text-white bg-white/10 hover:bg-white/20 rounded-full p-1.5 transition-colors" data-modal-toggle="addMapelModal">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-8 space-y-5">
                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest leading-none">Kode Mapel</label>
                    <input type="text" name="kode" placeholder="Misal: MTK" 
                        class="bg-gray-50 border-2 border-transparent border-b-gray-200 text-gray-900 text-base font-bold rounded-2xl focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100 block w-full p-4 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:border-b-gray-500 dark:focus:border-blue-600 uppercase transition-all shadow-inner font-mono" required>
                    <p class="mt-2 text-xs text-gray-400">Kode unik singkat untuk mata pelajaran.</p>
                </div>
                <div>
                    <label class="block mb-3 text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest leading-none">Nama Mata Pelajaran</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <input type="text" name="nama_mapel" placeholder="Misal: MATEMATIKA" 
                            class="bg-gray-50 border-2 border-transparent border-b-gray-200 text-gray-900 text-base font-bold rounded-2xl focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100 block w-full p-6 pl-12 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:border-b-gray-500 dark:focus:border-blue-600 uppercase transition-all shadow-inner" required>
                    </div>
                    <p class="mt-2 text-xs text-gray-400">Masukkan nama lengkap mata pelajaran.</p>
                </div>
                
                <div class="flex items-center space-x-3 pt-2">
                    <button type="submit" class="flex-1 text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed font-extrabold rounded-2xl text-base px-6 py-5 text-center transition-all shadow-xl shadow-blue-500/20 active:scale-95 group">
                        <span class="flex items-center justify-center">
                            SIMPAN MATA PELAJARAN
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-close alert after 5 seconds
        const alert = document.getElementById('alert-success');
        if (alert) {
            setTimeout(() => {
                alert.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        }
    });
</script>
<style>
    @keyframes pulse-once {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }
    .animate-pulse-once {
        animation: pulse-once 0.5s ease-out 1;
    }
</style>
@endpush
@endsection
