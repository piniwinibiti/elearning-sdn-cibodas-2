@extends('layouts.app')

@section('title', 'Scanner Absensi Kelas')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Scanner Absensi Kelas</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pindai wajah siswa satu per satu untuk mencatat kehadiran secara otomatis.</p>
        </div>
        <a href="{{ route('guru.dashboard') }}" class="text-blue-600 hover:underline text-sm font-medium">← Kembali ke Dashboard</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Area Kamera -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-800">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Kamera Pindai Wajah
                </h2>
                <div class="flex items-center space-x-2">
                     <span id="camera-status" class="flex items-center text-sm font-medium text-gray-500">
                        <span class="flex w-2.5 h-2.5 bg-gray-400 rounded-full mr-1.5 shrink-0"></span>
                        Offline
                    </span>
                </div>
            </div>
            
            <div class="p-6">
                <!-- Wrapper Kamera -->
                <div class="relative w-full aspect-video bg-gray-900 rounded-lg overflow-hidden flex items-center justify-center mb-6 shadow-md border-2 border-transparent focus-within:border-blue-500">
                    <video id="video-scanner" autoplay playsinline class="absolute top-0 left-0 w-full h-full object-cover transform scale-x-[-1]"></video>
                    
                    <!-- Overlay Kotak Target (untuk mode Guru) -->
                    <div class="absolute inset-x-0 inset-y-0 z-10 pointer-events-none flex items-center justify-center border-4 border-dashed border-white/40 rounded-xl px-20 py-16 m-8"></div>
                    
                    <!-- Laser Animasi Muter (Saat scan) -->
                    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-transparent via-green-400 to-transparent z-10 animate-scan pointer-events-none opacity-80 shadow-[0_0_15px_rgba(74,222,128,1)] hidden" id="scan-laser-active"></div>
                    
                    <div id="video-scanner-loading" class="z-20 text-white flex flex-col items-center">
                        <svg class="animate-spin h-8 w-8 text-white mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-sm font-medium">Menginisialisasi Kamera...</span>
                    </div>
                </div>

                <div class="flex gap-4">
                     <button type="button" id="btn-start-camera" class="flex-1 text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 text-center transition-colors">
                        Mulai Kamera
                    </button>
                    <button type="button" id="btn-capture-scan" disabled class="flex-1 text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-3 text-center transition-colors disabled:opacity-50 disabled:cursor-not-allowed hidden">
                        Pindai Siswa Ini (Manual)
                    </button>
                    <!-- Mode Otomatis Toggle -->
                     <button type="button" id="btn-toggle-auto" class="flex-1 text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-3 text-center transition-colors hidden">
                        Aktifkan Auto-Scan
                    </button>
                </div>
            </div>
        </div>

        <!-- Log Aktifitas -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700 flex flex-col h-full">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Riwayat Scan Hari Ini
                </h3>
            </div>
            <div class="p-4 flex-1 overflow-y-auto" id="scan-log-container" style="max-height: 500px;">
                <!-- Activity logs appear here -->
                <div class="text-center text-gray-500 dark:text-gray-400 py-10" id="empty-log-msg">
                    <span class="block mb-2">📷</span>
                    Belum ada siswa yang dipindai.
                </div>
            </div>
        </div>
    </div>
</div>

<canvas id="canvas-scanner" class="hidden"></canvas>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const video = document.getElementById('video-scanner');
        const canvas = document.getElementById('canvas-scanner');
        const startBtn = document.getElementById('btn-start-camera');
        const captureBtn = document.getElementById('btn-capture-scan');
        const toggleAutoBtn = document.getElementById('btn-toggle-auto');
        const loadingUI = document.getElementById('video-scanner-loading');
        const statusIndicator = document.getElementById('camera-status');
        const laser = document.getElementById('scan-laser-active');
        const logContainer = document.getElementById('scan-log-container');
        const emptyMsg = document.getElementById('empty-log-msg');

        let streamActive = null;
        let isProcessing = false;
        let autoScanInterval = null;
        let isAutoScanOn = false;

        startBtn.addEventListener('click', startCamera);
        captureBtn.addEventListener('click', processFrame);
        toggleAutoBtn.addEventListener('click', toggleAutoScan);

        function updateStatus(text, color) {
            statusIndicator.innerHTML = '<span class="flex w-2.5 h-2.5 bg-'+color+'-500 rounded-full mr-1.5 shrink-0"></span>' + text;
        }

        function addLogEntry(siswaName, kelas, timeText, isSuccess, errorMsg = '') {
            if(emptyMsg) emptyMsg.style.display = 'none';

            const logEntry = document.createElement('div');
            logEntry.className = `p-3 mb-3 rounded-lg border flex items-center justify-between ${isSuccess ? 'bg-green-50 border-green-200 dark:bg-green-900/20' : 'bg-red-50 border-red-200 dark:bg-red-900/20'}`;
            
            if(isSuccess) {
                logEntry.innerHTML = `
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3 text-green-600 font-bold">
                            ${siswaName.charAt(0)}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">${siswaName}</p>
                            <p class="text-xs text-gray-500">Kelas ${kelas}</p>
                        </div>
                    </div>
                    <span class="text-xs font-medium text-green-600 bg-green-100 px-2 py-1 rounded">✅ Hadir Pkl ${timeText}</span>
                `;
            } else {
                 logEntry.innerHTML = `
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-3 text-red-600 font-bold">
                            !
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-red-800 dark:text-white">Gagal Dikenali</p>
                            <p class="text-xs text-red-500">${errorMsg}</p>
                        </div>
                    </div>
                `;
            }

            logContainer.prepend(logEntry);
        }

        function startCamera() {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                loadingUI.classList.remove('hidden');
                updateStatus('Memproses...', 'yellow');

                navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } })
                    .then(function(stream) {
                        streamActive = stream;
                        video.srcObject = stream;
                        
                        video.onloadedmetadata = () => {
                            loadingUI.classList.add('hidden');
                            updateStatus('Online', 'green');
                            startBtn.classList.add('hidden');
                            captureBtn.classList.remove('hidden');
                            toggleAutoBtn.classList.remove('hidden');
                            captureBtn.disabled = false;
                        };
                    })
                    .catch(function(error) {
                        console.error("Camera access denied:", error);
                        loadingUI.classList.add('hidden');
                        updateStatus('Error Akses', 'red');
                        alert("Gagal mengakses kamera. Pastikan izin telah diberikan pada browser Anda.");
                    });
            } else {
                alert("Browser Anda tidak mendukung akses kamera.");
            }
        }

        function toggleAutoScan() {
            if(isAutoScanOn) {
                clearInterval(autoScanInterval);
                isAutoScanOn = false;
                toggleAutoBtn.textContent = 'Aktifkan Auto-Scan';
                toggleAutoBtn.classList.remove('bg-blue-50', 'text-blue-700', 'border-blue-400');
                captureBtn.disabled = false;
            } else {
                isAutoScanOn = true;
                toggleAutoBtn.textContent = 'Matikan Auto-Scan';
                toggleAutoBtn.classList.add('bg-blue-50', 'text-blue-700', 'border-blue-400');
                captureBtn.disabled = true; // disable manual if auto is on
                
                // Set interval to scan every 4 seconds
                autoScanInterval = setInterval(() => {
                    if(!isProcessing) processFrame(true);
                }, 4000);
            }
        }

        function processFrame(isAuto = false) {
            if (isProcessing) return;
            isProcessing = true;
            
            if(!isAuto) captureBtn.disabled = true;
            laser.classList.remove('hidden');

            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            // Draw current video frame to canvas
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const base64Image = canvas.toDataURL('image/jpeg');

            fetch('{{ route('guru.scanner.process') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ image: base64Image })
            })
            .then(response => response.json())
            .then(data => {
                isProcessing = false;
                laser.classList.add('hidden');
                if(!isAuto) captureBtn.disabled = false;

                if (data.success) {
                    addLogEntry(data.siswa.nama, data.siswa.kelas, data.siswa.jam, true);
                    
                    // Mainkan suara sukses (optional)
                    // const audio = new Audio('/beep.mp3'); audio.play();
                } else {
                    addLogEntry('Unknown', '-', '-', false, data.message || 'Wajah tidak dikenali sistem');
                }
            })
            .catch(error => {
                console.error('Error during scan:', error);
                isProcessing = false;
                laser.classList.add('hidden');
                if(!isAuto) captureBtn.disabled = false;
                // Don't clutter log with network errors during auto-scan, only manual
                if(!isAuto) addLogEntry('Error', '-', '-', false, 'Koneksi ke server terputus.');
            });
        }
    });
</script>
@endpush
@endsection
