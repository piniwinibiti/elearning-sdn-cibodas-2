@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Dashboard {{ ucfirst(auth()->user()->role ?? '') }}</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Selamat datang, {{ auth()->user()->nama_lengkap ?? '' }}!</p>
</div>

<div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Informasi Umum</h5>
        <p class="font-normal text-gray-700 dark:text-gray-400">Akses Anda dibatasi sebagai {{ ucfirst(auth()->user()->role ?? '') }}. Konten ini akan disesuaikan.</p>
    </div>
</div>
@endsection
