<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PythonRunner;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = auth()->user()->role;
            if ($role === 'admin') return redirect()->route('admin.dashboard');
            if ($role === 'guru')  return redirect()->route('guru.dashboard');
            if ($role === 'siswa') return redirect()->route('siswa.dashboard');

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function faceLogin(Request $request)
    {
        $request->validate([
            'image' => 'required|string',
        ]);

        // Decode Base64 image & simpan ke file temp
        $imageParts  = explode(";base64,", $request->image);
        $imageBase64 = base64_decode($imageParts[1]);

        $tempDir  = storage_path('app/public/temp');
        $tempPath = $tempDir . DIRECTORY_SEPARATOR . 'face_login_' . time() . '.jpg';

        if (!file_exists($tempDir)) mkdir($tempDir, 0777, true);
        file_put_contents($tempPath, $imageBase64);

        // Jalankan Python dengan PythonRunner (aman di Windows, tidak hang)
        $scriptPath = storage_path('app/public/recognize.py');
        $output     = PythonRunner::run($scriptPath, [$tempPath]);

        @unlink($tempPath);

        if (!$output) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menjalankan pengenalan wajah.'
            ], 500);
        }

        // Threshold diturunkan sesuai diskusi (lebih besar dari 20% match)
        if (isset($output['success']) && $output['success'] &&
            isset($output['confidence']) && $output['confidence'] > 20 &&
            isset($output['user_id'])) {

            $user = \App\Models\User::find($output['user_id']);

            if ($user) {
                Auth::login($user);
                $request->session()->regenerate();

                $redirectUrl = route('dashboard');
                if ($user->role === 'admin') $redirectUrl = route('admin.dashboard');
                if ($user->role === 'guru')  $redirectUrl = route('guru.dashboard');
                if ($user->role === 'siswa') $redirectUrl = route('siswa.dashboard');

                return response()->json([
                    'success'  => true,
                    'message'  => 'Login Berhasil! Selamat datang, ' . $user->nama_lengkap,
                    'redirect' => $redirectUrl
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => $output['message'] ?? 'Wajah tidak dikenali atau tingkat kecocokan rendah.',
            'confidence' => $output['confidence'] ?? 0
        ], 401);
    }
}