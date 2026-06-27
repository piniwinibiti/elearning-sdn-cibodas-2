@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Tugas Sekolah</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Daftar tugas yang harus Anda kerjakan.</p>
</div>

<!-- Control Bar (Filter Status & Search) -->
<div class="flex flex-col mb-4 space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
    <form action="{{ route('siswa.tugas.index') }}" method="GET" class="flex flex-col space-y-4 md:flex-row md:space-x-4 md:space-y-0 w-full md:w-auto">
        <!-- Filter Status -->
        <select name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full md:w-auto p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Masih Aktif</option>
            <option value="kedaluwarsa" {{ request('status') == 'kedaluwarsa' ? 'selected' : '' }}>Kedaluwarsa</option>
        </select>
        
        <!-- Search -->
        <div class="relative w-full md:w-auto">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" id="search" class="block w-full md:w-64 p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari Judul Tugas...">
        </div>
        
        @if(request('search') || request('status'))
            <a href="{{ route('siswa.tugas.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-4 py-2 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600 text-center">Clear</a>
        @endif
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($tugasList as $tugas)
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-indigo-900 dark:text-indigo-300">Tugas Baru</span>
            <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-purple-900 dark:text-purple-300">{{ $tugas->mata_pelajaran }}</span>
            <span class="text-sm font-medium {{ now() > $tugas->deadline ? 'text-red-600' : 'text-orange-500' }} ml-auto">Tenggat: {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M H:i') }}</span>
        </div>
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $tugas->judul }}</h5>
        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400 line-clamp-2">{{ Str::limit($tugas->instruksi, 100) }}</p>
        
        <a href="{{ route('siswa.tugas.show', $tugas->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-primary-700 rounded-lg hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
            Kerjakan Tugas
            <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg>
        </a>
    </div>
    @empty
    <div class="col-span-full p-6 text-center bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <p class="text-gray-500 dark:text-gray-400">Belum ada tugas untuk saat ini. Hore!</p>
    </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $tugasList->links() }}
</div>

@endsection
