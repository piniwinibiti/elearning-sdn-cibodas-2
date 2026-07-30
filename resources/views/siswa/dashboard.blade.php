@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Dashboard Siswa</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Selamat datang kembali di portal SDN Cibodas, persiapkan dirimu untuk belajar!</p>
    </div>
</div>

<!-- Profil Siswa Card -->
@php
    $siswa = auth()->user()->siswa;
@endphp
@if($siswa)
<div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6 mb-6 flex items-center">
    <div class="flex-shrink-0 mr-5">
        @if(auth()->user()->avatar)
            <img class="w-16 h-16 rounded-full border-2 border-blue-500 object-cover shadow-sm" src="{{ Storage::url('avatars/' . auth()->user()->avatar) }}" alt="Avatar">
        @else
            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center border-2 border-blue-500 shadow-sm text-blue-600 font-bold text-xl dark:bg-gray-700 dark:text-blue-400">
                {{ substr($siswa->nama, 0, 1) }}
            </div>
        @endif
    </div>
    <div class="flex-1 min-w-0">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white truncate">{{ $siswa->nama }}</h2>
        <div class="mt-1 flex flex-col sm:flex-row sm:items-center text-sm text-gray-500 dark:text-gray-400 space-y-1 sm:space-y-0 sm:space-x-4">
            <span class="flex items-center">
                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                NISN: <span class="font-medium ml-1 text-gray-900 dark:text-gray-300">{{ $siswa->nisn }}</span>
            </span>
            <span class="hidden sm:inline-block text-gray-300 dark:text-gray-600">|</span>
            <span class="flex items-center">
                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m3-4h1m-1 4h1m-5 8h8"></path></svg>
                Kelas: <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300 ml-1">{{ $siswa->id_kelas ?? '-' }}</span>
            </span>
        </div>
    </div>
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Akses Ujian (Face ID Gateway) -->
    <div class="md:col-span-2 p-6 bg-gradient-to-r from-blue-600 to-indigo-700 border border-blue-200 rounded-lg shadow-sm text-white relative overflow-hidden">
        <!-- Decoration -->
        <svg class="absolute top-0 right-0 transform translate-x-1/3 -translate-y-1/4 w-64 h-64 text-white opacity-10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"></path></svg>
        
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between">
            <div>
                <h5 class="text-2xl font-bold tracking-tight mb-2 flex items-center">
                    <svg class="w-7 h-7 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Ujian SDN Cibodas
                </h5>
                <p class="text-sm text-blue-100 max-w-xl">Kerjakan ujian dengan pengawasan cerdas Face ID. Pastikan wajah Anda terlihat jelas sebelum memulai.</p>
            </div>
            <a href="{{ route('siswa.ujian.index') }}" class="mt-4 md:mt-0 inline-flex items-center px-5 py-2.5 text-sm font-medium text-blue-700 bg-white rounded-lg hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-blue-300 transition-colors shadow-sm">
                Mulai Ujian Secara Aman
                <svg class="rtl:rotate-180 w-4 h-4 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                </svg>
            </a>
        </div>
    </div>

    <!-- Jadwal Pelajaran Hari Ini -->
    <div class="md:col-span-2">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6">
            <h5 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white flex items-center mb-4">
                <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Jadwal Pelajaran Hari Ini
            </h5>
            
            @if($jadwalHariIni->isEmpty())
                <div class="flex flex-col items-center justify-center py-6 text-gray-500">
                    <svg class="w-12 h-12 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm italic">Oopss! Sepertinya hari ini tidak ada jadwal pelajaran.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($jadwalHariIni as $j)
                        @php
                            $isCurrent = false;
                            $now = now()->toTimeString();
                            if ($now >= $j->jam_mulai && $now <= $j->jam_selesai) $isCurrent = true;
                        @endphp
                        <div class="p-4 rounded-xl border transition-all {{ $isCurrent ? 'bg-indigo-50 border-indigo-200 dark:bg-indigo-900/30 dark:border-indigo-800 ring-1 ring-indigo-500' : 'bg-gray-50 border-gray-100 dark:bg-gray-700/50 dark:border-gray-600' }}">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-[10px] font-black {{ $isCurrent ? 'text-indigo-600' : 'text-gray-400' }} uppercase px-2 py-0.5 rounded-full {{ $isCurrent ? 'bg-indigo-100' : 'bg-gray-100 dark:bg-gray-600' }}">
                                    {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}
                                </span>
                                @if($isCurrent)
                                    <span class="flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                                    </span>
                                @endif
                            </div>
                            <h6 class="text-base font-bold text-gray-900 dark:text-white mb-1">{{ $j->nama_mapel }}</h6>
                            <p class="text-xs text-gray-500 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                {{ $j->guru->user->nama_lengkap }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Tugas Terbaru -->
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center justify-between border-b pb-3 mb-4">
            <h5 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Tugas Terbaru
            </h5>
            <a href="{{ route('siswa.tugas.index') }}" class="text-sm font-medium text-blue-600 hover:underline">Lihat Semua</a>
        </div>
        
        @if($tugasList->isEmpty())
            <p class="text-gray-500 text-sm italic">Belum ada tugas baru untuk kelas ini.</p>
        @else
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($tugasList as $tugas)
                    <li class="py-3">
                        <div class="flex items-center space-x-4">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                    {{ $tugas->judul }}
                                </p>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-500 truncate dark:text-gray-400">
                                        Tenggat: {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y, H:i') }}
                                    </span>
                                    <span class="bg-purple-100 text-purple-800 text-[10px] font-medium px-2 py-0.5 rounded dark:bg-purple-900 dark:text-purple-300">{{ $tugas->mata_pelajaran }}</span>
                                </div>
                            </div>
                            <div class="inline-flex items-center text-sm font-semibold text-gray-900 dark:text-white">
                                <a href="{{ route('siswa.tugas.show', $tugas->id) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 text-xs transition">Buka</a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Materi Terbaru -->
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center justify-between border-b pb-3 mb-4">
            <h5 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white flex items-center">
                <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                Materi Terbaru
            </h5>
            <a href="{{ route('siswa.materi.index') }}" class="text-sm font-medium text-green-600 hover:underline">Lihat Semua</a>
        </div>

        @if($materis->isEmpty())
            <p class="text-gray-500 text-sm italic">Belum ada materi baru untuk kelas ini.</p>
        @else
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($materis as $materi)
                    <li class="py-3">
                        <div class="flex items-center space-x-4">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                    {{ $materi->judul }}
                                </p>
                                <div class="flex items-center space-x-2">
                                    <p class="text-sm text-gray-500 truncate dark:text-gray-400 capitalize">
                                        Format: {{ $materi->type }}
                                    </p>
                                    <span class="bg-purple-100 text-purple-800 text-[10px] font-medium px-2 py-0.5 rounded dark:bg-purple-900 dark:text-purple-300">{{ $materi->mata_pelajaran }}</span>
                                </div>
                            </div>
                            <div class="inline-flex items-center text-sm font-semibold text-gray-900 dark:text-white">
                                <a href="{{ asset('storage/' . $materi->file_path) }}" target="_blank" class="px-3 py-1 bg-green-100 text-green-700 rounded-md hover:bg-green-200 text-xs transition">Lihat</a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Presensi Mandiri (Absensi Wajah) -->
    <div class="md:col-span-1 p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-start justify-between">
            <div class="flex items-center">
                <div class="p-3 bg-indigo-100 rounded-lg dark:bg-indigo-900/30 mr-4">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 011.664.89l.812 1.22A2 2 0 0010.07 10H19a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13V9m-3 4V9m-3 4V9"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Presensi Mandiri</h3>
                    <p class="text-xs text-gray-500 mt-1">Lakukan absensi harian dengan scan wajah.</p>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            @if(!$hasFaceDataset)
                <div class="p-4 bg-yellow-50 text-yellow-800 text-xs rounded-lg border border-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-400 dark:border-yellow-800 italic">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Wajah belum terdaftar. Silakan daftarkan wajah Anda terlebih dahulu di bawah.
                </div>
            @elseif($sudahAbsenMapel)
                <div class="p-4 bg-green-50 text-green-800 text-sm rounded-lg border border-green-100 flex items-center dark:bg-green-900/20 dark:text-green-400 dark:border-green-800">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span class="font-bold">Sudah Hadir!</span> Presensi Anda untuk mapel **{{ $activeJadwal->nama_mapel }}** telah tercatat.
                </div>
            @elseif(!$activeJadwal)
                <div class="p-4 bg-gray-50 text-gray-500 text-xs rounded-lg border border-gray-200 dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600 italic">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Fitur scan dinonaktifkan karena tidak ada jadwal pelajaran yang aktif saat ini.
                </div>
            @else
                <button type="button" onclick="openAbsensiModal()" class="w-full text-white bg-indigo-600 hover:bg-indigo-700 font-bold rounded-xl text-sm px-5 py-3.5 text-center transition-all shadow-lg hover:scale-[1.01]">
                    Scan Wajah: {{ $activeJadwal->nama_mapel }}
                </button>
            @endif
        </div>
    </div>

    <!-- Face ID Registration Section -->
    <div class="md:col-span-1 p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full dark:bg-blue-900/30 mr-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Autentikasi Face ID</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        @if($hasFaceDataset) 
                            <span class="text-green-600 font-semibold">Tersertifikasi</span>. Wajah Anda sudah terdaftar dalam sistem.
                        @else
                            <span class="text-red-500 font-semibold">Belum Terdaftar</span>. Daftarkan wajah Anda untuk akses presensi dan ujian.
                        @endif
                    </p>
                </div>
            </div>
            <button type="button" onclick="openFaceRegModal()" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-shadow shadow-md">
                {{ $hasFaceDataset ? 'Update Scan Wajah' : 'Mulai Pendaftaran Wajah' }}
            </button>
        </div>
    </div>
    
    <!-- Riwayat Nilai Ujian Table -->
    <div class="md:col-span-2">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Riwayat Nilai Ujian</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Hasil dan nilai ujian yang sudah kamu kumpulkan.</p>
                </div>
            </div>
            <div class="overflow-x-auto p-0">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Mata Pelajaran (Ujian)</th>
                            <th scope="col" class="px-6 py-3 text-center">Tipe</th>
                            <th scope="col" class="px-6 py-3 text-center">Dikumpulkan Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-right">Nilai Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatUjian as $riwayat)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $riwayat->ujian->judul }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-purple-200 dark:text-purple-800">
                                    {{ ucfirst($riwayat->ujian->tipe) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $riwayat->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if(is_null($riwayat->nilai))
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Menunggu Penilaian</span>
                                @else
                                    <span class="bg-emerald-100 text-emerald-800 text-sm font-bold px-3 py-1 rounded dark:bg-emerald-900 dark:text-emerald-300">{{ $riwayat->nilai }} / 100</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-lg font-medium">Belum Ada Riwayat Ujian</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Face ID Registration Modal -->
<div id="face-reg-modal" class="fixed inset-0 z-[60] hidden overflow-y-auto overflow-x-hidden flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative p-4 w-full max-w-2xl">
        <div class="relative bg-white rounded-2xl shadow-2xl dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Registrasi Identitas Wajah</h3>
                <button type="button" onclick="closeFaceRegModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path></svg>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6">
                <div id="reg-instructions">
                    <div class="bg-blue-50 text-blue-800 p-4 rounded-lg mb-4 text-sm dark:bg-blue-900/30 dark:text-blue-300">
                        <ul class="list-disc ml-5 space-y-1">
                            <li>Pastikan wajah Anda terlihat jelas dengan pencahayaan yang cukup.</li>
                            <li>Sistem akan mengambil **20 sampel foto** secara otomatis.</li>
                            <li>Gerakkan kepala sedikit saat memindai agar akurasi lebih baik.</li>
                        </ul>
                    </div>
                    <button type="button" onclick="startRegistration()" class="w-full text-white bg-blue-600 hover:bg-blue-700 font-bold rounded-xl text-lg px-5 py-3 text-center transition-all shadow-lg hover:scale-[1.02]">
                        Siap, Mulai Pemindaian
                    </button>
                </div>

                <div id="reg-camera-area" class="hidden">
                    <div class="relative w-full aspect-video bg-gray-900 rounded-xl overflow-hidden border-4 border-gray-100 dark:border-gray-700 mb-4 shadow-inner">
                        <video id="reg-video" autoplay playsinline class="absolute inset-0 w-full h-full object-cover transform scale-x-[-1]"></video>
                        <div class="absolute inset-0 border-4 border-dashed border-white/30 rounded-lg m-10 pointer-events-none"></div>
                        <div id="reg-countdown" class="absolute inset-0 flex items-center justify-center text-white text-8xl font-black hidden bg-black/40">3</div>
                        <!-- Progress Bar Overlay -->
                        <div class="absolute bottom-0 left-0 w-full h-2 bg-gray-200 dark:bg-gray-700">
                            <div id="reg-progress-bar" class="h-full bg-blue-600 transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span id="reg-status" class="text-gray-500 font-medium">Menunggu Kamera...</span>
                        <span id="reg-count-status" class="font-bold text-gray-900 dark:text-white">0 / 20 Sampel</span>
                    </div>
                </div>

                <div id="reg-success" class="hidden text-center py-6">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 text-green-600 dark:bg-green-900/30">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Pendaftaran Selesai!</h4>
                    <p class="text-gray-500 mb-6">Wajah Anda berhasil direkam dan model telah diperbarui secara otomatis. Anda sekarang bisa menggunakan Face ID.</p>
                    <button type="button" onclick="location.reload()" class="w-full text-white bg-green-600 hover:bg-green-700 font-bold rounded-xl px-5 py-3">Tutup & Segarkan Halaman</button>
                </div>
            </div>
        </div>
    </div>
</div>

<canvas id="reg-canvas" class="hidden"></canvas>

@push('scripts')
<script>
    const modal = document.getElementById('face-reg-modal');
    const instrArea = document.getElementById('reg-instructions');
    const camArea = document.getElementById('reg-camera-area');
    const successArea = document.getElementById('reg-success');
    const video = document.getElementById('reg-video');
    const canvas = document.getElementById('reg-canvas');
    const progressBar = document.getElementById('reg-progress-bar');
    const countdownEl = document.getElementById('reg-countdown');
    const statusEl = document.getElementById('reg-status');
    const countEl = document.getElementById('reg-count-status');

    let stream = null;
    let sampleCount = 0;
    const maxSamples = 20;
    
    function openFaceRegModal() {
        modal.classList.remove('hidden');
        instrArea.classList.remove('hidden');
        camArea.classList.add('hidden');
        successArea.classList.add('hidden');
    }

    function closeFaceRegModal() {
        if(stream) {
            stream.getTracks().forEach(track => track.stop());
        }
        modal.classList.add('hidden');
    }

    async function startRegistration() {
        instrArea.classList.add('hidden');
        camArea.classList.remove('hidden');
        statusEl.textContent = 'Membuka Kamera...';

        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
            video.srcObject = stream;
            statusEl.textContent = 'Siap...';
            
            // Countdown 3s
            let count = 3;
            countdownEl.classList.remove('hidden');
            const timer = setInterval(() => {
                count--;
                countdownEl.textContent = count;
                if(count <= 0) {
                    clearInterval(timer);
                    countdownEl.classList.add('hidden');
                    captureSamples();
                }
            }, 1000);

        } catch (err) {
            console.error(err);
            alert('Gagal mengakses kamera. Pastikan izin kamera diaktifkan.');
            closeFaceRegModal();
        }
    }

    async function captureSamples() {
        statusEl.textContent = 'Memindai Wajah... (Jangan Berpindah)';
        statusEl.classList.add('text-blue-600', 'animate-pulse');
        
        sampleCount = 0;
        const interval = setInterval(async () => {
            sampleCount++;
            
            // Draw to canvas
            const ctx = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            const imageData = canvas.toDataURL('image/jpeg', 0.8);
            
            // Update UI
            const percent = (sampleCount / maxSamples) * 100;
            progressBar.style.width = percent + '%';
            countEl.textContent = `${sampleCount} / ${maxSamples} Sampel`;

            // Send to server
            try {
                const response = await fetch('{{ route('face.register') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        image: imageData,
                        sample_count: sampleCount
                    })
                });

                if (response.status === 422) {
                    const data = await response.json();
                    const first = Object.values(data.errors ?? {})[0]?.[0];
                    clearInterval(interval);
                    statusEl.classList.remove('text-blue-600', 'animate-pulse');
                    statusEl.classList.add('text-red-600');
                    statusEl.textContent = first ?? 'Gagal merekam sample wajah. Coba lagi.';
                    return;
                }
            } catch (err) {
                console.error('Failed to send sample:', err);
            }

            if(sampleCount >= maxSamples) {
                clearInterval(interval);
                finishRegistration();
            }
        }, 150); // Every 150ms capture a frame
    }

    function finishRegistration() {
        if(stream) {
            stream.getTracks().forEach(track => track.stop());
        }
        camArea.classList.add('hidden');
        successArea.classList.remove('hidden');
    }
</script>

<!-- Face ID Absensi Modal -->
<div id="face-absensi-modal" class="fixed inset-0 z-[60] hidden overflow-y-auto overflow-x-hidden flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative p-4 w-full max-w-xl">
        <div class="relative bg-white rounded-2xl shadow-2xl dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Presensi Wajah: {{ $activeJadwal ? $activeJadwal->nama_mapel : '' }}</h3>
                <button type="button" onclick="closeAbsensiModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path></svg>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6 text-center">
                <div id="absensi-camera-area">
                    <div class="relative w-full aspect-square max-w-sm mx-auto bg-gray-900 rounded-full overflow-hidden border-8 border-gray-100 dark:border-gray-700 mb-6 shadow-inner ring-4 ring-indigo-500/20">
                        <video id="absensi-video" autoplay playsinline class="absolute inset-0 w-full h-full object-cover transform scale-x-[-1]"></video>
                        <div id="absensi-feedback" class="absolute inset-0 flex items-center justify-center text-white text-4xl font-black hidden bg-black/40">1</div>
                    </div>
                    <h4 id="absensi-status" class="text-lg font-bold text-gray-900 dark:text-white mb-2">Mengaktifkan Biometrik...</h4>
                    <p class="text-sm text-gray-500">Posisikan wajah Anda di tengah lingkaran.</p>
                </div>

                <div id="absensi-result" class="hidden py-6">
                    <div id="absensi-icon-success" class="hidden w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 text-green-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div id="absensi-icon-error" class="hidden w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 text-red-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <h4 id="absensi-result-title" class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Hasil Presensi</h4>
                    <p id="absensi-result-msg" class="text-gray-500 mb-6 font-medium">Memproses data...</p>
                    <button type="button" onclick="location.reload()" class="px-8 py-3 bg-gray-900 text-white font-bold rounded-xl hover:bg-gray-800 transition-all">Selesai</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const absModal = document.getElementById('face-absensi-modal');
    const absVideo = document.getElementById('absensi-video');
    const absStatus = document.getElementById('absensi-status');
    const absResArea = document.getElementById('absensi-result');
    const absCamArea = document.getElementById('absensi-camera-area');
    const absIconSuccess = document.getElementById('absensi-icon-success');
    const absIconError = document.getElementById('absensi-icon-error');
    const absFeedback = document.getElementById('absensi-feedback');
    
    let absStream = null;

    function openAbsensiModal() {
        absModal.classList.remove('hidden');
        absCamArea.classList.remove('hidden');
        absResArea.classList.add('hidden');
        startAbsensiCamera();
    }

    function closeAbsensiModal() {
        stopAbsensiCamera();
        absModal.classList.add('hidden');
    }

    async function startAbsensiCamera() {
        absStatus.textContent = 'Menghubungkan Kamera...';
        try {
            absStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
            absVideo.srcObject = absStream;
            absStatus.textContent = 'Mengenali Wajah...';
            
            // Wait 2 seconds then capture once
            setTimeout(() => {
                absFeedback.classList.remove('hidden');
                absFeedback.textContent = "3";
                setTimeout(() => {
                    absFeedback.textContent = "2";
                    setTimeout(() => {
                        absFeedback.textContent = "1";
                        setTimeout(() => {
                            absFeedback.classList.add('hidden');
                            doCaptureAndVerify();
                        }, 800);
                    }, 800);
                }, 800);
            }, 500);

        } catch (err) {
            console.error(err);
            alert('Gagal mengakses kamera.');
            closeAbsensiModal();
        }
    }

    function stopAbsensiCamera() {
        if(absStream) {
            absStream.getTracks().forEach(track => track.stop());
            absStream = null;
        }
    }

    async function doCaptureAndVerify() {
        absStatus.textContent = 'Sedang Mencocokkan...';
        
        const captureCanvas = document.createElement('canvas');
        captureCanvas.width = absVideo.videoWidth;
        captureCanvas.height = absVideo.videoHeight;
        const ctx = captureCanvas.getContext('2d');
        ctx.drawImage(absVideo, 0, 0);
        
        const imageData = captureCanvas.toDataURL('image/jpeg', 0.82);
        
        stopAbsensiCamera();
        absCamArea.classList.add('hidden');
        absResArea.classList.remove('hidden');
        absIconSuccess.classList.add('hidden');
        absIconError.classList.add('hidden');
        
        try {
            const response = await fetch('{{ route('siswa.absensi.scan') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ image: imageData })
            });

            let result = await response.json();

            if (response.status === 422) {
                const first = Object.values(result.errors ?? {})[0]?.[0];
                result = { success: false, message: first ?? result.message ?? 'Data tidak valid.' };
            }

            if (result.success) {
                absIconSuccess.classList.remove('hidden');
                document.getElementById('absensi-result-title').textContent = 'Sukses!';
                document.getElementById('absensi-result-msg').textContent = result.message + ' (Kecocokan: ' + result.confidence.toFixed(1) + '%)';
                document.getElementById('absensi-result-msg').className = 'text-green-600 font-bold mb-6';
            } else {
                absIconError.classList.remove('hidden');
                document.getElementById('absensi-result-title').textContent = 'Gagal';
                document.getElementById('absensi-result-msg').textContent = result.message;
                document.getElementById('absensi-result-msg').className = 'text-red-500 font-bold mb-6';
            }
        } catch (err) {
            absIconError.classList.remove('hidden');
            document.getElementById('absensi-result-title').textContent = 'Error';
            document.getElementById('absensi-result-msg').textContent = 'Terjadi kesalahan sistem.';
        }
    }
</script>
@endpush
@endpush
@endsection
