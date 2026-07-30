@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Absensi Wajah (Face Recognition)</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Silakan melakukan presensi mandiri sesuai jadwal pelajaran yang aktif.</p>
</div>

<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700 p-6">
    @if(!$activeJadwal)
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-amber-100 text-amber-500 mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Jadwal Tidak Ditemukan</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-8">Belum ada jadwal kelas aktif untuk Anda saat ini.</p>
            <a href="{{ route('dashboard') }}" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-6 py-3 transition-colors shadow-sm">
                Kembali ke Dashboard
            </a>
        </div>
    @elseif($sudahAbsenMapel)
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 text-green-500 mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Sudah Absen: {{ $activeJadwal->nama_mapel }}</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-8">Anda telah berhasil melakukan absensi untuk mata pelajaran ini hari ini.</p>
            <a href="{{ route('dashboard') }}" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-6 py-3 transition-colors shadow-sm">
                Kembali ke Dashboard
            </a>
        </div>
    @else
        <div class="mb-4 text-center">
             <span class="bg-indigo-100 text-indigo-800 text-xs font-bold px-3 py-1 rounded-full dark:bg-indigo-900 dark:text-indigo-300">
                Pelajaran Aktif: {{ $activeJadwal->nama_mapel }}
             </span>
        </div>
        <div class="relative w-full overflow-hidden rounded-lg bg-gray-900 aspect-video flex items-center justify-center">
            <!-- Webcam Video (Mirrored for natural feel) -->
            <video id="webcam" class="w-full h-full object-cover transform scale-x-[-1]" autoplay playsinline></video>
            
            <!-- Oval Guide Overlay -->
            <div class="absolute inset-0 pointer-events-none border-[40px] border-black/40 xl:border-[60px]">
                 <div class="w-full h-full border-4 border-dashed border-white/50 rounded-[40%] shadow-[0_0_0_9999px_rgba(0,0,0,0.4)]"></div>
            </div>
            
            <!-- Scanning Laser Animation -->
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-transparent via-blue-400 to-transparent z-10 animate-scan pointer-events-none opacity-80 shadow-[0_0_15px_rgba(96,165,250,1)] hidden" id="scan-laser"></div>
            
            <!-- Loading overlay -->
            <div id="loading" class="absolute inset-0 bg-gray-900/80 flex flex-col items-center justify-center hidden z-10 text-white">
                <svg aria-hidden="true" class="w-12 h-12 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600 mb-3" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                </svg>
                <span class="font-medium text-lg">Menganalisis Wajah...</span>
            </div>
        </div>
        
        <!-- Hidden Canvas used for capturing frame -->
        <canvas id="canvas" class="hidden"></canvas>

        <div class="mt-6 text-center">
            <!-- Status Container -->
            <div id="status-message" class="mb-4 text-sm font-medium rounded-lg p-3 hidden"></div>

            <button id="capture-btn" class="w-full sm:w-auto text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-lg px-8 py-3 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 inline-flex items-center justify-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Pindai Wajah Sekarang
            </button>
        </div>
    @endif
</div>

@if($activeJadwal && !$sudahAbsenMapel)
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const video = document.getElementById('webcam');
        const canvas = document.getElementById('canvas');
        const captureBtn = document.getElementById('capture-btn');
        const loading = document.getElementById('loading');
        const statusMsg = document.getElementById('status-message');

        // Request Camera Access
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } })
                .then(function(stream) {
                    video.srcObject = stream;
                })
                .catch(function(error) {
                    console.error("Camera access denied:", error);
                    showStatus("Gagal mengakses kamera. Pastikan izin kamera telah diberikan.", "error");
                });
        }

        // Capture functionality
        captureBtn.addEventListener('click', () => {
            // Setup canvas
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const context = canvas.getContext('2d');
            
            // Draw current video frame to canvas
            // We flip it horizontally again on canvas so the final image is stored correctly (un-mirrored text)
            context.translate(canvas.width, 0);
            context.scale(-1, 1);
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Convert to Base64
            const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
            
            // Send to Laravel via AJAX
            sendFaceData(dataUrl);
        });

        function sendFaceData(base64Image) {
            // Show Loading & Laser
            loading.classList.remove('hidden');
            document.getElementById('scan-laser').classList.remove('hidden');
            captureBtn.disabled = true;
            statusMsg.classList.add('hidden');

            fetch('{{ route('siswa.absensi.scan') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ image: base64Image })
            })
            .then(async (response) => {
                const data = await response.json();
                if (response.status === 422) {
                    const first = Object.values(data.errors ?? {})[0]?.[0];
                    return { success: false, message: first ?? data.message ?? 'Data tidak valid.' };
                }
                return data;
            })
            .then(data => {
                loading.classList.add('hidden');
                document.getElementById('scan-laser').classList.add('hidden');
                captureBtn.disabled = false;
                
                if (data.success) {
                    showStatus(data.message, 'success');
                    captureBtn.classList.add('hidden'); // Hide button if success
                    
                    // Redirect to refresh page / dashboard after 2 secs
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showStatus(data.message, 'error');
                }
            })
            .catch(error => {
                console.error("Error:", error);
                loading.classList.add('hidden');
                document.getElementById('scan-laser').classList.add('hidden');
                captureBtn.disabled = false;
                showStatus('Terjadi kesalahan koneksi server. Coba lagi.', 'error');
            });
        }

        function showStatus(msg, type) {
            statusMsg.textContent = msg;
            statusMsg.classList.remove('hidden', 'bg-red-50', 'text-red-800', 'bg-green-50', 'text-green-800');
            
            if (type === 'error') {
                statusMsg.classList.add('bg-red-50', 'text-red-800', 'dark:bg-gray-700', 'dark:text-red-400');
            } else {
                statusMsg.classList.add('bg-green-50', 'text-green-800', 'dark:bg-gray-700', 'dark:text-green-400');
            }
        }
    });
</script>
@endif
@endsection
