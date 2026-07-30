@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Ujian SDN Cibodas</h1>
    <div class="flex items-center space-x-3 mt-1">
        <p class="text-sm text-gray-500 dark:text-gray-400">Pelajaran:</p>
        <span class="bg-purple-100 text-purple-800 text-xs font-bold px-3 py-1 rounded-full dark:bg-purple-900 dark:text-purple-300 shadow-sm border border-purple-200 dark:border-purple-700">{{ $mataPelajaran }}</span>
    </div>
</div>

<!-- ========================================== -->
<!-- GATEWAY: Camera Verification Container -->
<!-- ========================================== -->
<div id="ujian-gateway" class="max-w-2xl mx-auto mt-10 p-6 bg-white border border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700 text-center">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Verifikasi Identitas</h2>
    <p class="text-gray-500 dark:text-gray-400 mb-6">Untuk mencegah kecurangan dan joki, sistem mensyaratkan scanning Face ID sebelum ujian dimulai. Wajah harus cocok dengan siswa yang login.</p>

    <!-- UI Scanner -->
    <div class="relative w-full max-w-sm aspect-[4/5] mx-auto bg-gray-900 rounded-lg overflow-hidden flex items-center justify-center mb-6 shadow-inner border-4 border-gray-100 dark:border-gray-700">
        <video id="video-ujian" autoplay playsinline class="absolute top-0 left-0 w-full h-full object-cover transform scale-x-[-1]"></video>
        
        <!-- Overlay Guide -->
        <div class="absolute inset-x-0 inset-y-0 z-10 pointer-events-none flex flex-col items-center justify-center">
            <!-- Oval shape cut out with border -->
             <div class="w-48 h-64 border-4 border-dashed border-white/60 rounded-[40%] shadow-[0_0_0_9999px_rgba(0,0,0,0.4)]"></div>
        </div>
        
        <!-- Loading State -->
        <div id="video-ujian-loading" class="z-20 text-white flex flex-col items-center">
            <svg class="animate-spin h-8 w-8 text-white mb-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-sm font-medium">Buka Kamera...</span>
        </div>
    </div>
    <canvas id="canvas-ujian" class="hidden"></canvas>

    <div id="gateway-status" class="mb-4 text-sm font-medium hidden px-3 py-2 rounded-lg inline-block"></div>

    @if($ujian)
    <button type="button" id="btn-verify-face" disabled class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 text-base font-medium text-white transition-colors bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 disabled:opacity-50">
        <svg class="w-5 h-5 mr-2 -ml-1 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
        Pindai & Buka Ujian
    </button>
    @else
    <button type="button" disabled class="cursor-not-allowed opacity-50 w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 text-base font-medium text-white bg-gray-600 border border-transparent rounded-lg">
        Belum ada ujian untuk kelas ini.
    </button>
    @endif
</div>

<!-- ========================================== -->
<!-- EXAM CONTENT (HIDDEN BY DEFAULT)           -->
<!-- ========================================== -->
<div id="ujian-content" class="hidden mt-6 space-y-6">
    <div class="flex justify-between items-center bg-blue-50 border-l-4 border-blue-600 p-4 rounded dark:bg-gray-800 dark:border-blue-500">
        <div>
            <p class="text-sm text-blue-800 font-semibold dark:text-blue-300">Face ID Terverifikasi ✅</p>
            <p class="text-xs text-blue-600 dark:text-blue-400">Selamat mengerjakan, {{ auth()->user()->nama_lengkap }}</p>
        </div>
        <div class="text-right">
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Sisa Waktu</p>
            <p class="text-xl font-mono text-gray-900 dark:text-white font-bold" id="timer">45:00</p>
        </div>
    </div>

    @if($ujian)
    <form action="{{ route('siswa.ujian.submit', $ujian->id) }}" method="POST" id="form-ujian" enctype="multipart/form-data">
        @csrf
        
        @if($ujian->tipe == 'essay')
        <!-- Essay Type Content -->
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 border-b pb-2 dark:border-gray-700">Instruksi Soal:</h3>
            <div class="prose max-w-none text-gray-800 dark:text-gray-300 mb-6 whitespace-pre-wrap">{{ $ujian->teks_essay }}</div>
            
            @if($ujian->file_soal)
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-between border border-blue-100 dark:border-blue-800">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <div>
                        <h4 class="text-sm font-bold text-blue-900 dark:text-blue-200">File Lampiran Soal</h4>
                        <p class="text-xs text-blue-700 dark:text-blue-300">Guru melampirkan file referensi soal. Silakan diunduh/dibaca.</p>
                    </div>
                </div>
                <a href="{{ Storage::url('soal_ujian/' . $ujian->file_soal) }}" target="_blank" class="inline-flex items-center text-sm font-medium text-white bg-blue-600 rounded-lg px-4 py-2 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800">
                    Buka File Lampiran
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>
            </div>
            @endif
            
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_jawaban">Upload Lembar Jawaban (PDF / Gambar) <span class="text-red-500">*</span></label>
                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="file_jawaban_help" id="file_jawaban" name="file_jawaban" type="file" accept=".pdf,image/*" required>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" id="file_jawaban_help">Pastikan foto jelas dan bisa dibaca oleh guru. Maksimal 5MB.</p>
                <x-input-error name="file_jawaban" />
            </div>
        </div>
        @else
        <!-- Multiple Choice Content -->
        @foreach($ujian->soals as $idx => $soal)
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 mb-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4"><span class="font-bold mr-2">{{ $idx + 1 }}.</span> {{ $soal->pertanyaan }}</h3>

            <div class="space-y-3">
                <label class="flex items-center p-3 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors">
                    <input type="radio" value="A" name="jawaban[{{ $soal->id }}]" {{ old('jawaban.'.$soal->id) === 'A' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" required>
                    <span class="ml-3 font-medium cursor-pointer w-full"><span class="mr-2 font-bold text-gray-500">A.</span> {{ $soal->opsi_a }}</span>
                </label>
                <label class="flex items-center p-3 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors">
                    <input type="radio" value="B" name="jawaban[{{ $soal->id }}]" {{ old('jawaban.'.$soal->id) === 'B' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" required>
                    <span class="ml-3 font-medium cursor-pointer w-full"><span class="mr-2 font-bold text-gray-500">B.</span> {{ $soal->opsi_b }}</span>
                </label>
                <label class="flex items-center p-3 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors">
                    <input type="radio" value="C" name="jawaban[{{ $soal->id }}]" {{ old('jawaban.'.$soal->id) === 'C' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" required>
                    <span class="ml-3 font-medium cursor-pointer w-full"><span class="mr-2 font-bold text-gray-500">C.</span> {{ $soal->opsi_c }}</span>
                </label>
                <label class="flex items-center p-3 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors">
                    <input type="radio" value="D" name="jawaban[{{ $soal->id }}]" {{ old('jawaban.'.$soal->id) === 'D' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" required>
                    <span class="ml-3 font-medium cursor-pointer w-full"><span class="mr-2 font-bold text-gray-500">D.</span> {{ $soal->opsi_d }}</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('jawaban.'.$soal->id)" />
        </div>
        @endforeach
        <x-input-error :messages="$errors->get('jawaban')" />
        @endif

        <div class="flex justify-end mt-6">
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-8 py-3 text-center dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 shadow-md">
                Kumpulkan Jawaban
            </button>
        </div>
    </form>
    @endif
</div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. Camera Gateway Logic ---
        const video = document.getElementById('video-ujian');
        const canvas = document.getElementById('canvas-ujian');
        const verifyBtn = document.getElementById('btn-verify-face');
        const loadingUI = document.getElementById('video-ujian-loading');
        const statusUI = document.getElementById('gateway-status');
        
        const gatewayContainer = document.getElementById('ujian-gateway');
        const contentContainer = document.getElementById('ujian-content');
        
        let streamActive = null;
        let isProcessing = false;

        // Initialize Camera Check on page load
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } })
                .then(function(stream) {
                    streamActive = stream;
                    video.srcObject = stream;
                    video.onloadedmetadata = () => {
                        loadingUI.classList.add('hidden');
                        verifyBtn.disabled = false;
                    };
                })
                .catch(function(error) {
                    console.error("Camera error:", error);
                    loadingUI.innerHTML = '<span class="text-red-400">Gagal mengakses kamera. Izin ditolak.</span>';
                });
        }

        // Verify Logic
        verifyBtn.addEventListener('click', function() {
            if (isProcessing) return;
            isProcessing = true;
            verifyBtn.disabled = true;
            verifyBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memverifikasi...';
            
            statusUI.classList.add('hidden');
            statusUI.className = 'mb-4 text-sm font-medium hidden px-3 py-2 rounded-lg inline-block';

            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const base64Image = canvas.toDataURL('image/jpeg');

            fetch('{{ route('siswa.ujian.verify') }}', {
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
                if (data.success) {
                    // Berhasil -> Transisi ke Soal
                    statusUI.textContent = data.message;
                    statusUI.classList.add('bg-green-100', 'text-green-800');
                    statusUI.classList.remove('hidden');
                    
                    // Stop Camera to save resources
                    if(streamActive) {
                        streamActive.getTracks().forEach(track => track.stop());
                    }

                    // Morph UI Container
                    setTimeout(() => {
                        gatewayContainer.style.height = gatewayContainer.offsetHeight + 'px'; // fix height for transition
                        gatewayContainer.classList.add('transition-all', 'duration-500', 'opacity-0', 'scale-95');
                        
                        setTimeout(() => {
                            gatewayContainer.classList.add('hidden');
                            contentContainer.classList.remove('hidden');
                            contentContainer.classList.add('animate-fade-in'); // define in css if needed
                            startTimer();
                        }, 500);
                    }, 800);

                } else {
                    // Gagal
                    statusUI.textContent = "Gagal: " + (data.message || 'Wajah tidak sesuai.');
                    statusUI.classList.add('bg-red-100', 'text-red-800');
                    statusUI.classList.remove('hidden');
                    resetVerifyBtn();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                statusUI.textContent = "Terjadi kesalahan sistem, coba lagi.";
                statusUI.classList.add('bg-orange-100', 'text-orange-800');
                statusUI.classList.remove('hidden');
                resetVerifyBtn();
            });
        });

        function resetVerifyBtn() {
            isProcessing = false;
            verifyBtn.disabled = false;
            verifyBtn.innerHTML = '<svg class="w-5 h-5 mr-2 -ml-1 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg> Pindai Ulang Wajah';
        }

        // --- 2. Timer Logic ---
        function startTimer() {
            const timerEl = document.getElementById('timer');
            let timeLeft = {{ $ujian ? $ujian->waktu_menit * 60 : 45 * 60 }}; 

            setInterval(() => {
                const min = Math.floor(timeLeft / 60);
                const sec = timeLeft % 60;
                timerEl.textContent = `${min.toString().padStart(2, '0')}:${sec.toString().padStart(2, '0')}`;
                
                if (timeLeft > 0) timeLeft--;
            }, 1000);
        }
    });
</script>
<style>
    .animate-fade-in {
        animation: fadeIn 0.8s ease-out forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush
@endsection
