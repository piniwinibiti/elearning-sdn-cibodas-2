@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Laporan Nilai Akademik</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Transkrip nilai capaian belajar Anda semester ini.</p>
</div>

<!-- Kartu Ringkasan -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="p-6 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl shadow-lg text-white">
        <h3 class="text-lg font-medium opacity-80 mb-2">Rata-rata Nilai</h3>
        <span class="text-4xl font-black">{{ count($rekapNilai) > 0 ? number_format(collect($rekapNilai)->avg('nilai_akhir'), 1) : '-' }}</span>
        <p class="mt-4 text-xs opacity-70">Dihitung dari seluruh mata pelajaran yang memiliki nilai.</p>
    </div>
    
    <div class="p-6 bg-white border border-gray-200 rounded-3xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Mata Pelajaran</h3>
            <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
        </div>
        <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ count($rekapNilai) }}</span>
        <p class="mt-2 text-xs text-gray-400">Mata pelajaran aktif semester ini.</p>
    </div>

    <div class="p-6 bg-white border border-gray-200 rounded-3xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Predikat Terbanyak</h3>
            <div class="p-2 bg-green-50 dark:bg-green-900/30 rounded-lg">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z"></path></svg>
            </div>
        </div>
        <span class="text-3xl font-bold text-gray-900 dark:text-white">
            @php
                $avgAll = collect($rekapNilai)->avg('nilai_akhir');
                if($avgAll >= 90) echo 'A';
                elseif($avgAll >= 80) echo 'B';
                elseif($avgAll >= 70) echo 'C';
                else echo 'D';
            @endphp
        </span>
        <p class="mt-2 text-xs text-gray-400">Berdasarkan rata-rata nilai akhir.</p>
    </div>
</div>

<!-- Tabel Nilai -->
<div class="bg-white border border-gray-200 rounded-3xl shadow-sm dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-700/30">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Nilai Per Mata Pelajaran</h2>
        <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full dark:bg-indigo-900/50 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800">Semester Ganjil 2024/2025</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-4">Mata Pelajaran</th>
                    <th class="px-6 py-4 text-center">Rerata Tugas (40%)</th>
                    <th class="px-6 py-4 text-center">Nilai Ujian (60%)</th>
                    <th class="px-6 py-4 text-center">Nilai Akhir</th>
                    <th class="px-6 py-4 text-center">Predikat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($rekapNilai as $nilai)
                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold mr-3">
                                {{ substr($nilai->mapel, 0, 1) }}
                            </div>
                            <span class="font-bold text-gray-900 dark:text-white">{{ $nilai->mapel }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center font-medium">{{ $nilai->rata_tugas }}</td>
                    <td class="px-6 py-4 text-center font-medium">{{ $nilai->nilai_ujian }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-lg font-black {{ $nilai->nilai_akhir >= 75 ? 'text-green-600' : 'text-orange-500' }}">
                            {{ number_format($nilai->nilai_akhir, 1) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $p = 'D';
                            $c = 'text-red-600 bg-red-50';
                            if($nilai->nilai_akhir >= 90) { $p = 'A'; $c = 'text-green-600 bg-green-50'; }
                            elseif($nilai->nilai_akhir >= 80) { $p = 'B'; $c = 'text-blue-600 bg-blue-50'; }
                            elseif($nilai->nilai_akhir >= 70) { $p = 'C'; $c = 'text-yellow-600 bg-yellow-50'; }
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-black {{ $c }} dark:bg-gray-700 border border-current">
                            {{ $p }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-20 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="w-16 h-16 mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <p class="text-lg font-medium">Belum ada nilai yang tersedia.</p>
                            <p class="text-sm text-gray-400">Nilai akan tampil setelah guru memberikan nilai pada tugas atau ujian Anda.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-8 p-6 bg-yellow-50 border border-yellow-200 rounded-2xl dark:bg-yellow-900/10 dark:border-yellow-900/50">
    <div class="flex">
        <svg class="w-6 h-6 text-yellow-600 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div>
            <h4 class="text-sm font-bold text-yellow-800 dark:text-yellow-400">Informasi Perhitungan Nilai Akhir</h4>
            <p class="mt-1 text-xs text-yellow-700 dark:text-yellow-500/80 leading-relaxed">
                Nilai Akhir dihitung berdasarkan pembobotan standar: <strong>40% Rata-rata Tugas</strong> dan <strong>60% Nilai Ujian</strong>. 
                Predikat: <strong>A</strong> (90-100), <strong>B</strong> (80-89), <strong>C</strong> (70-79), <strong>D</strong> (<70).
            </p>
        </div>
    </div>
</div>
@endsection
