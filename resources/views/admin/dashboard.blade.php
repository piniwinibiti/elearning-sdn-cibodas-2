@extends('layouts.app')

@section('content')
@if(session('success'))
<div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
    <span class="font-medium">Berhasil!</span> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
    <span class="font-medium">Gagal!</span> {{ session('error') }}
</div>
@endif

<div class="mb-6 flex flex-col sm:flex-row items-center justify-between">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h2>
    <div class="flex flex-wrap gap-2 mt-4 sm:mt-0">
        <a href="{{ route('admin.guru.index') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 flex items-center">
            <svg class="w-4 h-4 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1c0 .6-.4 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
            Tambah Guru
        </a>
        <a href="{{ route('admin.laporan.download') }}" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 flex items-center">
            <svg class="w-4 h-4 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 15v2a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-2m-8 1V4m0 12-4-4m4 4 4-4"/></svg>
            Cetak Laporan Bulanan
        </a>
        <form action="{{ route('admin.face.train') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-purple-600 dark:hover:bg-purple-700 focus:outline-none dark:focus:ring-purple-800 flex items-center">
                <svg class="w-4 h-4 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 1 0-18c1.05 0 2.06.18 3 .51V5a3 3 0 0 1-3 3M12 21a9 9 0 1 0 0-18c-1.05 0-2.06.18-3 .51V5a3 3 0 0 0 3 3"/></svg>
                Train Model Wajah
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Card 1 -->
    <div class="bg-blue-50 dark:bg-gray-800 rounded-xl p-6 border border-blue-100 dark:border-gray-700 shadow-sm flex items-center transition-all hover:scale-105">
        <div class="p-3 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300 mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Guru</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalGuru }}</p>
        </div>
    </div>
    
    <!-- Card 2 -->
    <div class="bg-green-50 dark:bg-gray-800 rounded-xl p-6 border border-green-100 dark:border-gray-700 shadow-sm flex items-center transition-all hover:scale-105">
        <div class="p-3 rounded-full bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300 mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Siswa</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalSiswa }}</p>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-amber-50 dark:bg-gray-800 rounded-xl p-6 border border-amber-100 dark:border-gray-700 shadow-sm flex items-center transition-all hover:scale-105">
        <div class="p-3 rounded-full bg-amber-100 text-amber-600 dark:bg-amber-900 dark:text-amber-300 mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Kelas</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalKelas }}</p>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="bg-purple-50 dark:bg-gray-800 rounded-xl p-6 border border-purple-100 dark:border-gray-700 shadow-sm flex items-center transition-all hover:scale-105">
        <div class="p-3 rounded-full bg-purple-100 text-purple-600 dark:bg-purple-900 dark:text-purple-300 mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Siswa Trained</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $siswaTrained }} <span class="text-sm text-gray-500 font-normal">/ {{ $totalSiswa }}</span></p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Chart -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Statistik Kehadiran (7 Hari)</h3>
        <div class="relative h-[250px] w-full">
            <canvas id="attendanceChart"></canvas>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">5 Presensi Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3 rounded-s-lg">Nama Siswa</th>
                        <th scope="col" class="px-4 py-3">Kelas</th>
                        <th scope="col" class="px-4 py-3">Jam</th>
                        <th scope="col" class="px-4 py-3 rounded-e-lg">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPresensi as $presensi)
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $presensi->siswa->user->nama_lengkap ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $presensi->siswa->id_kelas ?? '-' }}</td>
                        <td class="px-4 py-3 font-mono text-xs">{{ \Carbon\Carbon::parse($presensi->jam_masuk)->format('H:i') }}</td>
                        <td class="px-4 py-3">
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300 flex w-fit items-center">
                                <span class="w-2 h-2 me-1 bg-green-500 rounded-full"></span>
                                {{ $presensi->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col flex-wrap justify-center items-center">
                                <svg class="w-8 h-8 mb-2 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                                <p>Belum ada presensi hari ini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Scripts for Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const labels = {!! json_encode($labels) !!};
        const data = {!! json_encode($attendanceData) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Hadir',
                    data: data,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.8)',
                        padding: 12,
                        titleFont: { size: 13 },
                        bodyFont: { size: 14, weight: 'bold' },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' Siswa Hadir';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#6b7280', font: { size: 12 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(156, 163, 175, 0.1)', drawBorder: false },
                        ticks: { 
                            stepSize: 1, 
                            color: '#6b7280',
                            font: { size: 12 }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });
    });
</script>
@endsection
