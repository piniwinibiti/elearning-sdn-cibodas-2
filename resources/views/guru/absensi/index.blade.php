@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Kelola Absensi Siswa</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pilih kelas dan mata pelajaran untuk mencatat kehadiran.</p>
</div>

<!-- Informasi Status Guru -->
<div class="p-4 mb-6 bg-blue-50 border border-blue-200 rounded-lg dark:bg-gray-800 dark:border-blue-900 flex items-center justify-between">
    <div class="flex items-center space-x-3">
        <div class="p-2 bg-blue-100 rounded-full dark:bg-blue-900">
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </div>
        <div>
            <p class="text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wider">Status Mengajar</p>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                {{ $isWali ? 'Wali Kelas ' . $guru->id_kelas_wali : 'Guru Bidang / Spesialis' }}
            </h2>
        </div>
    </div>
    <div class="hidden sm:block text-right">
        <p class="text-xs text-gray-500 dark:text-gray-400">Mapel Terdaftar:</p>
        <div class="flex flex-wrap justify-end gap-1 mt-1">
            @foreach($guru->mapels as $m)
                <span class="px-2 py-0.5 text-[10px] font-semibold bg-white border border-gray-200 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">{{ $m->nama_mapel }}</span>
            @endforeach
        </div>
    </div>
</div>

<!-- Header Filter -->
<div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 mb-8 shadow-sm">
    <form action="{{ route('guru.absensi.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
            <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Pilih Kelas</label>
            <select name="kelas" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" {{ $isWali ? 'disabled' : 'required' }}>
                <option value="" disabled selected>-- Pilih Kelas --</option>
                @foreach($kelasOptions as $kls)
                    <option value="{{ $kls }}" {{ $selectedKelas == $kls ? 'selected' : '' }}>Kelas {{ $kls }}</option>
                @endforeach
            </select>
            @if($isWali) <input type="hidden" name="kelas" value="{{ $guru->id_kelas_wali }}"> @endif
        </div>
        <div>
            <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Mata Pelajaran</label>
            <select name="mapel" required class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="" disabled {{ !$selectedMapel ? 'selected' : '' }}>-- Pilih Mapel --</option>
                @foreach($mapelOptions as $m)
                    <option value="{{ $m }}" {{ $selectedMapel == $m ? 'selected' : '' }}>{{ $m }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Tanggal</label>
            <input type="date" name="tanggal" value="{{ $tanggal }}" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-all">Muat Siswa</button>
            <a href="{{ route('guru.absensi.index') }}" class="px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </a>
        </div>
    </form>
    
    @if($jadwalHariIni->count() > 0)
    <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Jadwal Mengajar Hari Ini</h4>
        <div class="flex flex-wrap gap-3">
            @foreach($jadwalHariIni as $j)
                @php
                    $isCurrent = false;
                    $now = now()->toTimeString();
                    if ($now >= $j->jam_mulai && $now <= $j->jam_selesai) $isCurrent = true;
                @endphp
                <a href="{{ route('guru.absensi.index', ['kelas' => $j->id_kelas, 'mapel' => $j->nama_mapel]) }}" 
                   class="flex items-center p-3 rounded-xl border transition-all transform hover:scale-105 
                          {{ $isCurrent ? 'bg-blue-50 border-blue-200 dark:bg-blue-900/30 dark:border-blue-800 ring-2 ring-blue-500 ring-offset-2' : 'bg-white border-gray-100 dark:bg-gray-800 dark:border-gray-700' }}">
                    <div class="mr-3">
                        <div class="text-[10px] font-bold {{ $isCurrent ? 'text-blue-600' : 'text-gray-400' }} uppercase">{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</div>
                        <div class="text-sm font-black text-gray-900 dark:text-white">{{ $j->nama_mapel }}</div>
                        <div class="text-xs text-gray-500">Kelas {{ $j->id_kelas }}</div>
                    </div>
                    @if($isCurrent)
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

@if($selectedKelas && $selectedMapel)
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Panel Kiri: Input & Scan -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Tabs -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden shadow-sm">
            <div class="flex border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <button type="button" id="tab-manual" class="flex-1 py-4 text-sm font-bold border-b-2 border-blue-600 text-blue-600 transition-all">✍️ Input Manual</button>
                <button type="button" id="tab-scanner" class="flex-1 py-4 text-sm font-bold border-b-2 border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-all">📷 Scan Wajah Siswa</button>
            </div>

            <!-- Content: Manual -->
            <div id="panel-manual" class="p-6">
                <form action="{{ route('guru.absensi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kelas" value="{{ $selectedKelas }}">
                    <input type="hidden" name="mapel" value="{{ $selectedMapel }}">
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 py-3">Nama Siswa</th>
                                    <th class="px-4 py-3 text-center">Kehadiran</th>
                                    <th class="px-4 py-3">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($siswas as $s)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-600 text-xs">
                                                {{ substr($s->user->nama_lengkap ?? '?', 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900 dark:text-white">{{ $s->user->nama_lengkap ?? '-' }}</p>
                                                <p class="text-[10px] text-gray-400">NIS: {{ $s->nis }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        @php $currentStatus = $rekapAbsensi[$s->id]->status ?? 'alpha'; @endphp
                                        <div class="flex justify-center gap-1.5">
                                            @foreach([
                                                'hadir' => ['label' => 'H', 'color' => 'green', 'title' => 'Hadir'],
                                                'izin'  => ['label' => 'I', 'color' => 'blue', 'title' => 'Izin'],
                                                'sakit' => ['label' => 'S', 'color' => 'yellow', 'title' => 'Sakit'],
                                                'alpha' => ['label' => 'A', 'color' => 'red', 'title' => 'Alpha']
                                            ] as $val => $cfg)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="absensi[{{ $s->id }}][status]" value="{{ $val }}" class="sr-only peer" {{ $currentStatus == $val ? 'checked' : '' }}>
                                                <div class="w-8 h-8 flex items-center justify-center rounded-md border text-xs font-black transition-all
                                                    @if($cfg['color'] == 'green') border-green-200 text-green-600 peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600 @endif
                                                    @if($cfg['color'] == 'blue') border-blue-200 text-blue-600 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 @endif
                                                    @if($cfg['color'] == 'yellow') border-yellow-200 text-yellow-600 peer-checked:bg-yellow-500 peer-checked:text-white peer-checked:border-yellow-500 @endif
                                                    @if($cfg['color'] == 'red') border-red-200 text-red-600 peer-checked:bg-red-600 peer-checked:text-white peer-checked:border-red-600 @endif
                                                    dark:border-gray-600" title="{{ $cfg['title'] }}">
                                                    {{ $cfg['label'] }}
                                                </div>
                                            </label>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <input type="text" name="absensi[{{ $s->id }}][keterangan]" value="{{ $rekapAbsensi[$s->id]->keterangan ?? '' }}" placeholder="Opsional..." class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 font-medium rounded-lg text-sm px-10 py-3 dark:bg-blue-600 dark:hover:bg-blue-700 flex items-center shadow-lg transform hover:-translate-y-0.5 transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Simpan Seluruh Absensi
                        </button>
                    </div>
                </form>
            </div>

            <!-- Content: Scanner -->
            <div id="panel-scanner" class="hidden p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div class="relative aspect-square bg-black rounded-2xl overflow-hidden border-4 border-gray-100 dark:border-gray-700 shadow-xl group">
                            <video id="scan-webcam" autoplay playsinline class="absolute inset-0 w-full h-full object-cover transform scale-x-[-1] hidden"></video>
                            <div id="scan-laser" class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-transparent via-green-400 to-transparent z-10 hidden" style="animation: scan 2s linear infinite;"></div>
                            <div id="scan-placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-white cursor-pointer hover:bg-white/5 transition-colors">
                                <div class="p-6 bg-white/10 rounded-full backdrop-blur-sm mb-4 group-hover:scale-110 transition-transform">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <span class="font-bold text-lg">Aktifkan Kamera</span>
                                <p class="text-xs text-white/60 mt-2 px-10 text-center">Pastikan pencahayaan cukup dan wajah terlihat jelas</p>
                            </div>
                        </div>
                        <button type="button" id="btn-snap" disabled class="w-full py-4 text-white bg-green-600 hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed font-bold rounded-xl shadow-lg flex items-center justify-center gap-2 transform active:scale-95 transition-all">
                            🎯 Ambil & Verifikasi Wajah
                        </button>
                    </div>
                    <div class="space-y-6">
                        <div class="p-5 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800">
                            <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-4">Petunjuk Penggunaan</h4>
                            <ul class="space-y-4">
                                <li class="flex items-start gap-4">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900 border border-blue-200 flex items-center justify-center text-xs font-bold text-blue-600">1</div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">Pintaskan kamera dan minta siswa berdiri di depan layar.</p>
                                </li>
                                <li class="flex items-start gap-4">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900 border border-blue-200 flex items-center justify-center text-xs font-bold text-blue-600">2</div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">Klik tombol verifikasi. Sistem akan mencocokkan wajah dengan dataset.</p>
                                </li>
                                <li class="flex items-start gap-4">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900 border border-blue-200 flex items-center justify-center text-xs font-bold text-blue-600">3</div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">Jika cocok, status kehadiran siswa di Mapel ini akan otomatis menjadi <b>Hadir</b>.</p>
                                </li>
                            </ul>
                        </div>
                        <div id="scan-result" class="hidden p-4 rounded-xl font-bold flex items-center justify-center gap-3"></div>
                        <canvas id="scan-canvas" class="hidden"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel Kanan: Summary & Feedback -->
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-sm">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4">Ringkasan Sesi Ini</h3>
            
            @php
                $countHadir = $rekapAbsensi->where('status', 'hadir')->count();
                $countTotal = $siswas->count();
                $percent = $countTotal > 0 ? ($countHadir / $countTotal) * 100 : 0;
            @endphp

            <div class="space-y-6">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-tighter">Progres Kehadiran</span>
                        <span class="text-lg font-black text-blue-600">{{ number_format($percent, 0) }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-100 dark:border-green-800">
                        <p class="text-[10px] uppercase font-bold text-green-600 mb-1">Hadir</p>
                        <p class="text-2xl font-black text-green-700 dark:text-white">{{ $countHadir }}</p>
                    </div>
                    <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-100 dark:border-red-800">
                        <p class="text-[10px] uppercase font-bold text-red-600 mb-1">Alpha/Belum</p>
                        <p class="text-2xl font-black text-red-700 dark:text-white">{{ $countTotal - $countHadir }}</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-orange-100 rounded-lg dark:bg-orange-900">
                            <svg class="w-5 h-5 text-orange-600 dark:text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-500 uppercase font-black">Sesi Saat Ini</p>
                            <p class="text-xs text-gray-900 dark:text-gray-300">{{ $selectedMapel }} - Kelas {{ $selectedKelas }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="flex p-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 dark:border-green-800 animate-bounce" role="alert">
            <svg class="flex-shrink-0 w-4 h-4 mt-0.5 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/></svg>
            <div>
                <span class="font-bold">Berhasil!</span> {{ session('success') }}
            </div>
        </div>
        @endif
    </div>
</div>
@else
<!-- Empty State -->
<div class="mt-10 py-20 bg-white dark:bg-gray-800 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center text-center">
    <div class="p-5 bg-blue-50 dark:bg-gray-900 rounded-full mb-6">
        <svg class="w-16 h-16 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    </div>
    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Siap Untuk Absensi?</h3>
    <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-sm">Tentukan Kelas dan Mata Pelajaran di atas untuk memuat daftar siswa dan menggunakan scanner.</p>
</div>
@endif

@push('scripts')
<script>
    const tabManual = document.getElementById('tab-manual');
    const tabScanner = document.getElementById('tab-scanner');
    const panelManual = document.getElementById('panel-manual');
    const panelScanner = document.getElementById('panel-scanner');

    function setActiveTab(active, inactive, activePanel, inactivePanel) {
        active.classList.add('text-blue-600', 'border-blue-600');
        active.classList.remove('text-gray-500', 'border-transparent');
        inactive.classList.remove('text-blue-600', 'border-blue-600');
        inactive.classList.add('text-gray-500', 'border-transparent');
        activePanel.classList.remove('hidden');
        inactivePanel.classList.add('hidden');
    }

    if(tabManual && tabScanner) {
        tabManual.addEventListener('click', () => setActiveTab(tabManual, tabScanner, panelManual, panelScanner));
        tabScanner.addEventListener('click', () => {
            setActiveTab(tabScanner, tabManual, panelScanner, panelManual);
            // Refresh webcam layout if needed
        });
    }

    // Scanner Logic
    const placeholder = document.getElementById('scan-placeholder');
    const video = document.getElementById('scan-webcam');
    const laser = document.getElementById('scan-laser');
    const btnSnap = document.getElementById('btn-snap');
    const canvas = document.getElementById('scan-canvas');
    const resultBox = document.getElementById('scan-result');
    let stream = null;

    if(placeholder) {
        placeholder.addEventListener('click', async () => {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
                video.srcObject = stream;
                video.classList.remove('hidden');
                laser.classList.remove('hidden');
                placeholder.classList.add('hidden');
                btnSnap.disabled = false;
            } catch (err) {
                alert('Gagal mengakses kamera: ' + err.message);
            }
        });

        btnSnap.addEventListener('click', async () => {
            btnSnap.disabled = true;
            btnSnap.textContent = 'Memverifikasi...';
            resultBox.className = 'p-4 rounded-xl font-bold flex items-center justify-center gap-3 bg-blue-50 text-blue-600';
            resultBox.innerHTML = '<svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">...</svg> Mengolah Biometrik...';
            resultBox.classList.remove('hidden');

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.translate(canvas.width, 0);
            ctx.scale(-1, 1);
            ctx.drawImage(video, 0, 0);

            const dataUrl = canvas.toDataURL('image/jpeg', 0.8);

            try {
                const response = await fetch('{{ route("guru.absensi.scanner.process") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        image: dataUrl,
                        kelas: '{{ $selectedKelas }}',
                        mapel: '{{ $selectedMapel }}'
                    })
                });

                const data = await response.json();

                if (data.success) {
                    resultBox.className = 'p-4 rounded-xl font-bold bg-green-100 text-green-700 flex items-center justify-center gap-3 animate-pulse';
                    resultBox.innerHTML = '✨ BERHASIL: ' + data.siswa.nama;
                    setTimeout(() => location.reload(), 1500);
                } else {
                    resultBox.className = 'p-4 rounded-xl font-bold bg-red-100 text-red-700 flex items-center justify-center gap-3';
                    resultBox.innerHTML = '❌ TERDETEKSI: ' + (data.message || 'Wajah tidak cocok');
                    btnSnap.disabled = false;
                    btnSnap.textContent = '🎯 Ambil & Verifikasi Wajah';
                }
            } catch (error) {
                resultBox.className = 'p-4 rounded-xl font-bold bg-red-100 text-red-700 flex items-center justify-center gap-3';
                resultBox.textContent = 'Kesalahan jaringan atau server.';
                btnSnap.disabled = false;
            }
        });
    }
</script>
@endpush
@endsection
