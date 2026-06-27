@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Rekap Presensi</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pantau kehadiran harian Anda di sekolah.</p>
</div>

<!-- Statistik Kehadiran -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-xl">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($persentase, 0) }}%</span>
        </div>
        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Kehadiran</h3>
        <div class="w-full bg-gray-200 rounded-full h-2 mt-4 dark:bg-gray-700">
            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $persentase }}%"></div>
        </div>
    </div>

    <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-green-100 dark:bg-green-900 rounded-xl">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Hadir</h3>
                <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['hadir'] + $stats['terlambat'] }}</span>
            </div>
        </div>
    </div>

    <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-xl">
                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Izin / Sakit</h3>
                <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['izin'] + $stats['sakit'] }}</span>
            </div>
        </div>
    </div>

    <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-red-100 dark:bg-red-900 rounded-xl">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Alpha</h3>
                <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['alpha'] }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Riwayat -->
<div class="bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Riwayat Kehadiran Detail</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Jam Masuk</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($absensis as $absensi)
                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($absensi->tanggal)->format('d F Y') }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $absensi->jam_masuk ? substr($absensi->jam_masuk, 0, 5) : '-' }}
                    </td>
                    <td class="px-6 py-4">
                        @if($absensi->status == 'hadir')
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">Hadir</span>
                        @elseif($absensi->status == 'terlambat')
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-yellow-900/30 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800">Terlambat</span>
                        @elseif($absensi->status == 'izin')
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800">Izin</span>
                        @elseif($absensi->status == 'sakit')
                            <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-purple-900/30 dark:text-purple-400 border border-purple-200 dark:border-purple-800">Sakit</span>
                        @else
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">Alpha</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 italic text-gray-400">
                        {{ $absensi->status == 'hadir' ? 'Tepat waktu' : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            Belum ada riwayat presensi.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
