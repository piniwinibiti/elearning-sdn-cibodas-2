<!DOCTYPE html>
<html lang="en" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - SDN Cibodas</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-gray-900 bg-[#f4f7ff]">

<div class="flex min-h-screen items-center justify-center p-4 sm:p-8">
    <!-- Login Form Container -->
    <div class="w-full max-w-md xl:max-w-lg bg-white rounded-3xl shadow-xl border border-gray-100 p-8 sm:p-12">
        <div class="w-full">
            
            <div class="mb-12 text-center">
                <!-- Logo -->
                <div class="flex items-center justify-center gap-4 mb-8">
                     <img src="{{ asset('images/rv,cibodas.png') }}" class="h-12 w-auto" alt="Logo SDN Cibodas">
                     <h2 class="text-2xl font-bold text-gray-800">SDN Cibodas</h2>
                </div>

                <h1 class="text-4xl font-bold text-gray-900 mb-3">Masuk Akun</h1>
                <p class="text-base text-gray-500">Selamat Datang kembali, silahkan login ke akun Anda untuk melanjutkan</p>
            </div>

            @if($errors->any())
            <div class="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                {{ $errors->first() }}
            </div>
            @endif

            <!-- Tab Buttons -->
            <div class="mb-6 border-b border-gray-200">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="login-tab" data-tabs-toggle="#myTabContent" role="tablist">
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg transition-colors border-blue-600 text-blue-600" id="manual-tab" data-tabs-target="#manual" type="button" role="tab" aria-controls="manual" aria-selected="true">Manual Login</button>
                    </li>
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 transition-colors border-transparent text-gray-500" id="face-tab" data-tabs-target="#face" type="button" role="tab" aria-controls="face" aria-selected="false">Face Login 📷</button>
                    </li>
                </ul>
            </div>

            <!-- Tab Content -->
            <div id="myTabContent">
                <!-- Manual Login Tab -->
                <div class="p-4 rounded-lg bg-transparent" id="manual" role="tabpanel" aria-labelledby="manual-tab">
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <div class="mb-5">
                            <label for="username" class="block mb-2 text-sm font-medium text-gray-700">Username / NIS / NIP</label>
                            <input type="text" id="username" name="username" value="{{ old('username') }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm" placeholder="Masukkan username" required />
                            <x-input-error name="username" />
                        </div>
                        <div class="mb-5">
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                            <div class="relative">
                                <input type="password" id="password" name="password" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm" placeholder="••••••••" required />
                                <button type="button" class="password-toggle absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 transition-colors" data-target="password">
                                    <svg class="eye-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg class="eye-off-icon w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                </button>
                            </div>
                            <x-input-error name="password" />
                        </div>
                        <div class="flex items-center justify-end mb-5">
                            <a href="#" class="text-sm font-medium text-blue-600 hover:underline">Lupa Password ?</a>
                        </div>
                        <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 text-center transition-colors shadow-md">Login</button>
                    </form>
                </div>
                <!-- Face Login Tab -->
                <div class="hidden p-4 rounded-lg bg-transparent" id="face" role="tabpanel" aria-labelledby="face-tab">
                    
                    <div class="relative w-full aspect-video bg-gray-900 rounded-lg overflow-hidden flex items-center justify-center mb-4 border-2 border-transparent focus-within:border-blue-500">
                        <video id="video-login" autoplay playsinline class="absolute top-0 left-0 w-full h-full object-cover transform scale-x-[-1]"></video>
                        
                        <!-- Overlay oval guide -->
                        <div class="absolute inset-x-0 inset-y-0 z-10 pointer-events-none flex items-center justify-center border-4 border-dashed border-white/50 rounded-[40%] px-16 py-20 m-4"></div>
                        
                        <!-- Scanning Laser Animation -->
                        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-transparent via-green-400 to-transparent z-10 animate-scan pointer-events-none opacity-80 shadow-[0_0_15px_rgba(74,222,128,1)] hidden" id="scan-laser-login"></div>
                        
                        <div id="video-login-loading" class="z-20 text-white flex flex-col items-center">
                            <svg class="animate-spin h-8 w-8 text-white mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-sm text-center px-4">Mengakses kamera... Pastikan izin diberikan.</span>
                        </div>
                    </div>

                    <button type="button" id="btn-capture-login" class="w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-3 text-center transition-colors shadow-md flex items-center justify-center disabled:opacity-50">
                        <svg class="w-5 h-5 mr-2 -ml-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M4 18V8a1 1 0 0 1 1-1h1.5l1.7-1.7A1 1 0 0 1 9 5h6a1 1 0 0 1 .7.3l1.7 1.7H19a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Z"/><path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                        Scan Wajah & Login
                    </button>
                    <!-- Hidden Canvas for capture -->
                    <canvas id="canvas-login" class="hidden"></canvas>
                    
                    <div id="login-status-msg" class="mt-4 text-sm text-center hidden p-3 rounded-lg"></div>
                </div>
            </div> <!-- End Tab Content -->
            
            <div class="mt-8 text-center text-sm text-gray-500">
                <p>Perlu Bantuan ? <a href="#" class="text-blue-600 hover:underline">Hubungi Bagian Tata Usaha</a></p>
            </div>

        </div> <!-- End max-w-md -->
    </div> <!-- End Login Form Container -->
</div> <!-- End Flex Container -->

<!-- Face Login Success Popup -->
<div id="face-success-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full p-8 text-center transform transition-all scale-95 opacity-0" id="face-success-modal-box">
        <div class="mx-auto mb-4 flex items-center justify-center w-16 h-16 rounded-full bg-green-100">
            <svg class="w-9 h-9 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-1" id="face-success-modal-title">Login Berhasil!</h3>
        <p class="text-sm text-gray-500 mb-4" id="face-success-modal-message"></p>

        <div class="mb-2 flex items-center justify-between text-sm">
            <span class="text-gray-600">Tingkat Kecocokan</span>
            <span class="font-semibold text-green-600" id="face-success-modal-confidence-value">0%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-6">
            <div id="face-success-modal-confidence-bar" class="bg-green-600 h-2.5 rounded-full transition-all duration-700 ease-out" style="width: 0%"></div>
        </div>

        <p class="text-xs text-gray-400" id="face-success-modal-countdown">Mengalihkan ke dashboard...</p>
    </div>
</div>

<script>
    const video = document.getElementById('video-login');
    const canvas = document.getElementById('canvas-login');
    const captureBtn = document.getElementById('btn-capture-login');
    const loadingUI = document.getElementById('video-login-loading');
    const statusMsg = document.getElementById('login-status-msg');
    let streamActive = null;

    // Detect tab switch to only start camera when Face Login is active
    document.querySelectorAll('[data-tabs-target]').forEach(tabBtn => {
        tabBtn.addEventListener('click', function(e) {
            
            // Adjust active styles manually as Flowbite might not handle border colors purely via data-attributes cleanly
            document.querySelectorAll('[data-tabs-target]').forEach(btn => {
                btn.classList.remove('border-blue-600', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            e.currentTarget.classList.remove('border-transparent', 'text-gray-500');
            e.currentTarget.classList.add('border-blue-600', 'text-blue-600');

            if(this.getAttribute('data-tabs-target') === '#face') {
                document.getElementById('manual').classList.add('hidden');
                document.getElementById('face').classList.remove('hidden');
                startCamera();
            } else {
                document.getElementById('face').classList.add('hidden');
                document.getElementById('manual').classList.remove('hidden');
                stopCamera();
            }
        });
    });

    function startCamera() {
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            loadingUI.classList.remove('hidden');
            captureBtn.disabled = true;

            navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } })
                .then(function(stream) {
                    streamActive = stream;
                    video.srcObject = stream;
                    
                    video.onloadedmetadata = () => {
                        loadingUI.classList.add('hidden');
                        captureBtn.disabled = false;
                    };
                })
                .catch(function(error) {
                    console.error("Camera access denied:", error);
                    loadingUI.classList.add('hidden');
                    showStatus("Gagal mengakses kamera. Pastikan izin telah diberikan pada browser Anda.", "error");
                });
        } else {
            showStatus("Browser Anda tidak mendukung akses kamera.", "error");
        }
    }

    function stopCamera() {
        if(streamActive) {
            streamActive.getTracks().forEach(track => track.stop());
            video.srcObject = null;
        }
        statusMsg.classList.add('hidden');
    }

    // Capture & sending logic remains same
    captureBtn.addEventListener('click', () => {
        showStatus("Memproses wajah Anda...", "loading");
        captureBtn.disabled = true;
        captureBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-3 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memindai...';
        
        // Show scanning laser animation
        document.getElementById('scan-laser-login').classList.remove('hidden');

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext('2d');
        
        context.translate(canvas.width, 0);
        context.scale(-1, 1);
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
        
        fetch("{{ route('login.face') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ image: dataUrl })
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
            document.getElementById('scan-laser-login').classList.add('hidden');
            if(data.success) {
                stopCamera();
                showSuccessModal(data.message, data.confidence, data.redirect || '/dashboard');
            } else {
                showStatus(data.message, "error");
                resetCaptureBtn();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('scan-laser-login').classList.add('hidden');
            showStatus("Terjadi kesalahan jaringan/server saat memproses.", "error");
            resetCaptureBtn();
        });
    });

    function showStatus(message, type) {
        statusMsg.textContent = message;
        statusMsg.className = `mt-4 text-sm text-center p-3 rounded-lg block font-medium `;
        
        if (type === 'success') {
            statusMsg.classList.add('bg-green-100', 'text-green-800');
        } else if (type === 'error') {
            statusMsg.classList.add('bg-red-100', 'text-red-800');
        } else if (type === 'loading') {
            statusMsg.classList.add('bg-blue-100', 'text-blue-800');
        }
    }

    function showSuccessModal(message, confidence, redirectUrl) {
        const modal = document.getElementById('face-success-modal');
        const box = document.getElementById('face-success-modal-box');
        const confidenceValue = (typeof confidence === 'number') ? confidence : 0;

        document.getElementById('face-success-modal-message').textContent = message;
        document.getElementById('face-success-modal-confidence-value').textContent = confidenceValue + '%';

        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            box.classList.remove('scale-95', 'opacity-0');
            box.classList.add('scale-100', 'opacity-100');
            document.getElementById('face-success-modal-confidence-bar').style.width = confidenceValue + '%';
        });

        let secondsLeft = 3;
        const countdownEl = document.getElementById('face-success-modal-countdown');
        countdownEl.textContent = `Mengalihkan ke dashboard dalam ${secondsLeft}...`;
        const countdownInterval = setInterval(() => {
            secondsLeft -= 1;
            if (secondsLeft <= 0) {
                clearInterval(countdownInterval);
                countdownEl.textContent = 'Mengalihkan...';
            } else {
                countdownEl.textContent = `Mengalihkan ke dashboard dalam ${secondsLeft}...`;
            }
        }, 1000);

        setTimeout(() => {
            window.location.href = redirectUrl;
        }, 3000);
    }

    function resetCaptureBtn() {
        captureBtn.disabled = false;
        captureBtn.innerHTML = '<svg class="w-5 h-5 mr-2 -ml-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M4 18V8a1 1 0 0 1 1-1h1.5l1.7-1.7A1 1 0 0 1 9 5h6a1 1 0 0 1 .7.3l1.7 1.7H19a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Z"/><path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg> Scan Wajah & Coba Lagi';
    }

    // Manual Password Toggle
    document.addEventListener('click', function(e) {
        if (e.target.closest('.password-toggle')) {
            const button = e.target.closest('.password-toggle');
            const targetId = button.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const eyeIcon = button.querySelector('.eye-icon');
            const eyeOffIcon = button.querySelector('.eye-off-icon');

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }
    });
</script>

</body>
</html>
