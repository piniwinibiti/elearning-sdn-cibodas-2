<?php

namespace App\Http\Controllers;

use App\Http\Requests\FaceLoginRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\PythonRunner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = auth()->user()->role;
            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            if ($role === 'guru') {
                return redirect()->route('guru.dashboard');
            }
            if ($role === 'siswa') {
                return redirect()->route('siswa.dashboard');
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function faceLogin(FaceLoginRequest $request)
    {
        $request->validated();

        // Verifikasi 1:1 (bukan identifikasi 1:N): user mengklaim identitas lewat
        // username lebih dulu, wajah hanya dicocokkan ke akun yang diklaim itu.
        // Ini mencegah kasus 2 akun ter-training wajah yang sama (human error saat
        // registrasi) menyebabkan login "nyasar" ke akun/role yang salah — hasil
        // terburuknya jadi ditolak, bukan berhasil login sebagai orang lain.
        $claimedUser = User::where('username', $request->username)->first();

        // Decode Base64 image & simpan ke file temp
        $imageParts = explode(';base64,', $request->image);
        $imageBase64 = base64_decode($imageParts[1]);

        $tempDir = storage_path('app/public/temp');
        $tempPath = $tempDir.DIRECTORY_SEPARATOR.'face_login_'.time().'.jpg';

        if (! file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }
        file_put_contents($tempPath, $imageBase64);

        // Jalankan Python dengan PythonRunner (aman di Windows, tidak hang)
        $scriptPath = storage_path('app/public/recognize.py');
        $output = PythonRunner::run($scriptPath, [$tempPath]);

        @unlink($tempPath);

        if (! $output) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menjalankan pengenalan wajah.',
            ], 500);
        }

        $noMatchResponse = response()->json([
            'success' => false,
            'message' => 'Wajah tidak cocok dengan akun yang dimasukkan, atau tingkat kecocokan rendah.',
            'confidence' => $output['confidence'] ?? 0,
        ], 401);

        // Tetap jalankan pengenalan wajah di atas walau username tidak ditemukan
        // (menjaga waktu respons konsisten), tapi jangan bocorkan bahwa username
        // tidak terdaftar — balas dengan pesan generik yang sama.
        if (! $claimedUser) {
            return $noMatchResponse;
        }

        // Threshold diturunkan sesuai diskusi (lebih besar dari 20% match)
        if (isset($output['success']) && $output['success'] &&
            isset($output['confidence']) && $output['confidence'] > 20 &&
            isset($output['user_id']) && (int) $output['user_id'] === $claimedUser->id) {

            Auth::login($claimedUser);
            $request->session()->regenerate();

            $redirectUrl = route('dashboard');
            if ($claimedUser->role === 'admin') {
                $redirectUrl = route('admin.dashboard');
            }
            if ($claimedUser->role === 'guru') {
                $redirectUrl = route('guru.dashboard');
            }
            if ($claimedUser->role === 'siswa') {
                $redirectUrl = route('siswa.dashboard');
            }

            return response()->json([
                'success' => true,
                'message' => 'Login Berhasil! Selamat datang, '.$claimedUser->nama_lengkap,
                'confidence' => $output['confidence'],
                'redirect' => $redirectUrl,
            ]);
        }

        return $noMatchResponse;
    }
}
