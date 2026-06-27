@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Dashboard Guru</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ikhtisar statistik kelas, presensi, tugas, dan nilai hari ini.</p>
</div>

<!-- Class Statistics Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Jumlah Siswa -->
    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center">
            <div class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 text-blue-600 bg-blue-100 rounded-lg dark:bg-blue-900 dark:text-blue-300">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
            </div>
            <div class="ms-4">
                <p class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">Total Siswa (Kelas {{ $guruKelas }})</p>
                <div class="flex items-baseline">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalSiswa }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Jumlah Materi -->
    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center">
            <div class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 text-green-600 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            </div>
            <div class="ms-4">
                <p class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">Materi Terunggah</p>
                <div class="flex items-baseline">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalMateri }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Tugas Belum Dinilai -->
    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center">
            <div class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 text-purple-600 bg-purple-100 rounded-lg dark:bg-purple-900 dark:text-purple-300">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
            </div>
            <div class="ms-4">
                <p class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">Jawaban Belum Dinilai</p>
                <div class="flex items-baseline">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tugasBelumDinilai }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Kelola Ujian -->
    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 transition cursor-pointer" onclick="window.location='{{ route('guru.ujian.index') }}'">
        <div class="flex items-center">
            <div class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 text-red-600 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <div class="ms-4 w-full flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">Kelola Ujian</p>
                    <p class="text-sm font-bold text-blue-600 hover:underline">Buka &rarr;</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Face ID Registration Section -->
<div class="relative overflow-hidden p-6 mb-6 bg-white border border-gray-100 rounded-2xl shadow-xl dark:bg-gray-800 dark:border-gray-700">
    <!-- Dekorasi Background -->
    <div class="absolute top-0 right-0 -m-8 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -m-8 w-24 h-24 bg-purple-500/10 rounded-full blur-2xl"></div>

    <div class="relative flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center space-x-5">
            <div class="flex-shrink-0 p-4 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg shadow-blue-200 dark:shadow-none">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-gray-900 dark:text-white">Autentikasi Face ID Guru</h3>
                <div class="mt-1 flex items-center">
                    @if($hasFaceDataset) 
                        <span class="flex h-2 w-2 rounded-full bg-green-500 mr-2"></span>
                        <p class="text-sm font-semibold text-green-600 dark:text-green-400">Identitas Terverifikasi</p>
                    @else
                        <span class="flex h-2 w-2 rounded-full bg-red-500 animate-pulse mr-2"></span>
                        <p class="text-sm font-semibold text-red-500">Belum Terdaftar</p>
                    @endif
                </div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 max-w-md">
                    Gunakan biometrik wajah untuk proses login yang lebih cepat tanpa perlu mengetik password setiap saat.
                </p>
            </div>
        </div>
        <div class="w-full md:w-auto">
            <button type="button" onclick="openFaceRegModal()" class="w-full md:w-auto px-8 py-3.5 text-base font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl hover:from-blue-700 hover:to-indigo-800 focus:ring-4 focus:ring-blue-300 transition-all shadow-lg hover:shadow-indigo-500/30 transform hover:-translate-y-0.5 active:scale-95">
                {{ $hasFaceDataset ? 'Perbarui Data Wajah' : 'Mulai Scan Sekarang' }}
            </button>
        </div>
    </div>
</div>

<!-- Jadwal Mengajar Hari Ini -->
<div class="mb-6">
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6">
        <h5 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white flex items-center mb-4">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Jadwal Mengajar Hari Ini
        </h5>
        
        @if($jadwalHariIni->isEmpty())
            <div class="flex flex-col items-center justify-center py-6 text-gray-500">
                <svg class="w-12 h-12 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-sm italic font-medium">Santai dulu bro! Tidak ada jadwal mengajar untuk Anda hari ini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($jadwalHariIni as $j)
                    @php
                        $isCurrent = false;
                        $now = now()->toTimeString();
                        if ($now >= $j->jam_mulai && $now <= $j->jam_selesai) $isCurrent = true;
                    @endphp
                    <a href="{{ route('guru.absensi.index', ['kelas' => $j->id_kelas, 'mapel' => $j->nama_mapel]) }}" 
                       class="p-4 rounded-xl border transition-all transform hover:scale-105 {{ $isCurrent ? 'bg-blue-50 border-blue-200 dark:bg-blue-900/30 dark:border-blue-800 ring-2 ring-blue-500' : 'bg-gray-50 border-gray-100 dark:bg-gray-700/50 dark:border-gray-600 font-medium' }}">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[10px] font-black {{ $isCurrent ? 'text-blue-600' : 'text-gray-400' }} uppercase px-2 py-0.5 rounded-full {{ $isCurrent ? 'bg-blue-100' : 'bg-gray-100 dark:bg-gray-600' }}">
                                {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}
                            </span>
                            @if($isCurrent)
                                <span class="flex h-2 w-2 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                </span>
                            @endif
                        </div>
                        <h6 class="text-base font-bold text-gray-900 dark:text-white mb-1">{{ $j->nama_mapel }}</h6>
                        <p class="text-xs text-gray-500">Kelas {{ $j->id_kelas }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Absensi Shortcut -->
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">Kehadiran Hari Ini</h5>
                <div class="px-2.5 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300">
                    Hadir: {{ number_format($persentaseKehadiran, 1) }}%
                </div>
            </div>

            <div class="mb-6">
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700 dark:text-white">Tingkat Kehadiran</span>
                    <span class="text-sm font-medium text-gray-700 dark:text-white">{{ $siswaHadir }} / {{ $totalSiswa }} Siswa</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                    <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-700" style="width: {{ $persentaseKehadiran }}%"></div>
                </div>
            </div>

            <div class="p-4 mb-6 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800">
                <p class="text-xs text-blue-700 dark:text-blue-300 leading-relaxed font-medium">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Absensi manual dan Scanner Wajah kini telah dipindahkan ke modul khusus untuk manajemen yang lebih fleksibel per mata pelajaran.
                </p>
            </div>
        </div>

        <a href="{{ route('guru.absensi.index') }}" class="w-full text-white bg-blue-600 hover:bg-blue-700 font-bold rounded-lg text-sm px-5 py-3 text-center transition-all flex items-center justify-center group">
            Buka Kelola Absensi
            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </a>
    </div>

    <!-- Deadline Terdekat -->
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">Deadline Tugas Terdekat</h5>
            <a href="{{ route('guru.tugas.index') }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">
                Kelola Tugas
            </a>
        </div>
        <div class="flow-root">
            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($tugasTerdekat as $tugas)
                <li class="py-3 sm:py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 text-red-500 dark:text-red-400">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div class="flex-1 min-w-0 ms-4">
                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                {{ $tugas->judul }}
                            </p>
                            <p class="text-sm text-red-500 truncate dark:text-red-400 font-semibold">
                                Selesai: {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <div class="inline-flex items-center text-xs font-semibold text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($tugas->deadline)->diffForHumans() }}
                        </div>
                    </div>
                </li>
                @empty
                <li class="py-3 sm:py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                    Tidak ada tugas dengan deadline dalam waktu dekat.
                </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<!-- Grafik Rata-Rata Nilai Tugas -->
<div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
    <div class="flex justify-between items-center mb-4">
        <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">Rata-Rata Nilai Tugas Kelas {{ $guruKelas }}</h5>
    </div>
    @if(count($tugasLabels) > 0)
    <div class="h-64">
        <canvas id="gradeChart"></canvas>
    </div>
    @else
    <div class="h-64 flex items-center justify-center text-gray-500 dark:text-gray-400 text-sm">
        Belum ada data tugas yang dinilai untuk ditampilkan pada grafik.
    </div>
    @endif
</div>

@push('scripts')
@if(count($tugasLabels) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('gradeChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.5)'); // Blue-500 with opacity
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($tugasLabels) !!},
                datasets: [{
                    label: 'Rata-Rata Nilai',
                    data: {!! json_encode($tugasAverages) !!},
                    borderColor: '#3b82f6', // blue-500
                    backgroundColor: gradient,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
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
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: 'rgba(156, 163, 175, 0.1)', // gray-400 with very low opacity
                            borderDash: [5, 5]
                        },
                        ticks: {
                            color: '#6b7280' // gray-500
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6b7280',
                            maxRotation: 45,
                            minRotation: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)', // gray-900
                        titleColor: '#ffffff',
                        bodyColor: '#e5e7eb', // gray-200
                        padding: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Rata-Rata: ' + context.parsed.y.toFixed(1);
                            }
                        }
                    }
                }
            }
    });
</script>
@endif
@endpush

@push('scripts')
<script>
    // Tab Switcher - Scan Wajah / Manual
    const tabScanner = document.getElementById('tab-scanner');
    const tabManual = document.getElementById('tab-manual');
    const panelScanner = document.getElementById('panel-scanner');
    const panelManual = document.getElementById('panel-manual');

    function switchTab(active, inactive, showPanel, hidePanel) {
        active.classList.add('text-blue-600', 'border-blue-600');
        active.classList.remove('text-gray-400', 'border-transparent');
        inactive.classList.remove('text-blue-600', 'border-blue-600');
        inactive.classList.add('text-gray-400', 'border-transparent');
        showPanel.classList.remove('hidden');
        hidePanel.classList.add('hidden');
    }

    tabScanner.addEventListener('click', () => switchTab(tabScanner, tabManual, panelScanner, panelManual));
    tabManual.addEventListener('click', () => switchTab(tabManual, tabScanner, panelManual, panelScanner));
</script>
@endpush

<!-- Face ID Registration Modal -->
<div id="face-reg-modal" class="fixed inset-0 z-[60] hidden overflow-y-auto overflow-x-hidden flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative p-4 w-full max-w-2xl">
        <div class="relative bg-white rounded-2xl shadow-2xl dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Registrasi Identitas Wajah Guru</h3>
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
            
            const ctx = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            const imageData = canvas.toDataURL('image/jpeg', 0.8);
            
            const percent = (sampleCount / maxSamples) * 100;
            progressBar.style.width = percent + '%';
            countEl.textContent = `${sampleCount} / ${maxSamples} Sampel`;

            try {
                await fetch('{{ route('face.register') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        image: imageData,
                        sample_count: sampleCount
                    })
                });
            } catch (err) {
                console.error('Failed to send sample:', err);
            }

            if(sampleCount >= maxSamples) {
                clearInterval(interval);
                finishRegistration();
            }
        }, 150);
    }

    function finishRegistration() {
        if(stream) {
            stream.getTracks().forEach(track => track.stop());
        }
        camArea.classList.add('hidden');
        successArea.classList.remove('hidden');
    }
</script>
@endpush
 @endsection
