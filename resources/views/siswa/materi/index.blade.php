@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Materi Pelajaran</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Daftar materi untuk kelas Anda.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($materis as $materi)
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex flex-wrap items-center gap-2 mb-2">
            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300 uppercase">{{ $materi->type }}</span>
            <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-purple-900 dark:text-purple-300">{{ $materi->mata_pelajaran }}</span>
            <span class="text-xs text-gray-500 dark:text-gray-400 ml-auto">{{ $materi->created_at->diffForHumans() }}</span>
        </div>
        <a href="#">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $materi->judul }}</h5>
        </a>
        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Guru: {{ $materi->guru->user->nama_lengkap ?? 'N/A' }}</p>
        
        <a href="{{ Storage::url($materi->file_path) }}" target="_blank" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-primary-700 rounded-lg hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
            Buka Materi
            <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg>
        </a>
    </div>
    @empty
    <div class="col-span-full p-6 text-center bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <p class="text-gray-500 dark:text-gray-400">Belum ada materi pelajaran untuk saat ini.</p>
    </div>
    @endforelse
</div>
@endsection
