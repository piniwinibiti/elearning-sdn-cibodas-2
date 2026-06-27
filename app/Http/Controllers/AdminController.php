<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;

class AdminController extends Controller
{
    public function downloadLaporanBulananHadir()
    {
        $bulanSekarang = now()->format('Y-m');
        $namaBulan = now()->translatedFormat('F Y');
        
        // Ambil data absensi bulan ini dengan relasi siswa
        $absensis = \App\Models\Absensi::with('siswa.user')
            ->where('tanggal', 'like', "$bulanSekarang-%")
            ->orderBy('tanggal', 'asc')
            ->get();
            
        // Rekap per siswa
        $rekapSiswa = [];
        foreach ($absensis as $absen) {
            $siswaId = $absen->siswa_id;
            if (!isset($rekapSiswa[$siswaId])) {
                $rekapSiswa[$siswaId] = [
                    'nama' => $absen->siswa->user->nama_lengkap ?? 'Tanpa Nama',
                    'kelas' => $absen->siswa->id_kelas ?? '-',
                    'hadir' => 0,
                    'terlambat' => 0,
                    'alpha' => 0
                ];
            }
            
            if ($absen->status == 'hadir') $rekapSiswa[$siswaId]['hadir']++;
            elseif ($absen->status == 'terlambat') $rekapSiswa[$siswaId]['terlambat']++;
            elseif ($absen->status == 'alpha') $rekapSiswa[$siswaId]['alpha']++;
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan_pdf', compact('rekapSiswa', 'namaBulan'));
        
        return $pdf->download('Laporan_Absensi_Bulanan_SD_Elearning_'. $namaBulan .'.pdf');
    }

    public function indexGuru(Request $request)
    {
        $query = Guru::with('user');

        // Pencarian (Search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            })->orWhere('nip', 'like', "%{$search}%")
              ->orWhere('mapel_ajar', 'like', "%{$search}%");
        }

        // Filter berdasarkan Mapel
        if ($request->filled('mapel')) {
            $query->where('mapel_ajar', $request->mapel);
        }

        $gurus = $query->latest()->paginate(10)->withQueryString();
        
        // Check face dataset for each guru
        $datasetPath = storage_path('app/public/dataset');
        foreach ($gurus as $guru) {
            $pattern = $datasetPath . DIRECTORY_SEPARATOR . "User.{$guru->user_id}.1.jpg";
            $guru->has_face_dataset = file_exists($pattern);
        }
        
        // Ambil list unik mapel_ajar untuk dropdown filter
        $mapels = Guru::select('mapel_ajar')->distinct()->pluck('mapel_ajar');

        // Master data untuk form create/edit
        $mapelOptions = Mapel::orderBy('nama_mapel')->pluck('nama_mapel');
        $kelasOptions = Kelas::orderBy('nama_kelas')->pluck('nama_kelas');

        return view('admin.guru.index', compact('gurus', 'mapels', 'mapelOptions', 'kelasOptions'));
    }

    public function storeGuru(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'required|string|unique:gurus,nip',
            'mapel_ajar' => 'nullable|array',
            'mapel_ajar.*' => 'string',
            'password' => 'required|string|min:6',
            'id_kelas_wali' => 'nullable|string|max:50',
            'face_samples' => 'nullable|array',
            'face_samples.*' => 'string'
        ]);

        $user = null;

        DB::transaction(function () use ($request, &$user) {
            $user = User::create([
                'username' => $request->nip,
                'nama_lengkap' => $request->nama_lengkap,
                'password' => Hash::make($request->password),
                'role' => 'guru'
            ]);

            $guru = Guru::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'mapel_ajar' => $request->mapel_ajar ? implode(', ', $request->mapel_ajar) : '-', 
                'id_kelas_wali' => $request->id_kelas_wali,
            ]);
            
            if ($request->mapel_ajar) {
                foreach ($request->mapel_ajar as $mapel) {
                    \App\Models\GuruMapel::create([
                        'guru_id' => $guru->id,
                        'nama_mapel' => $mapel
                    ]);
                }
            }
        });

        // Simpan Biometrik jika ada
        if ($request->has('face_samples') && count($request->face_samples) > 0) {
            $datasetPath = storage_path('app/public/dataset');
            if (!file_exists($datasetPath)) {
                mkdir($datasetPath, 0777, true);
            }

            foreach ($request->face_samples as $index => $base64Image) {
                $imageParts = explode(";base64,", $base64Image);
                if (count($imageParts) == 2) {
                    $decodedImage = base64_decode($imageParts[1]);
                    // Format required by train.py: User.[USER_ID].[Index].jpg
                    $fileName = "User.{$user->id}." . ($index + 1) . ".jpg";
                    file_put_contents($datasetPath . DIRECTORY_SEPARATOR . $fileName, $decodedImage);
                }
            }

            // Jalankan training otomatis setelah simpan foto wajah
            $pythonScriptPath = storage_path('app/public/train.py');
            if (file_exists($pythonScriptPath)) {
                $output = \App\Services\PythonRunner::runRaw($pythonScriptPath);
                
                if (file_exists(storage_path('app/public/trainer.yml'))) {
                    return back()->with('success', 'Data Guru & Biometrik wajah berhasil ditambahkan. Model wajah sudah otomatis diperbarui.');
                } else {
                    return back()->with('warning', 'Data Guru & foto wajah tersimpan, namun training model gagal: ' . $output . '. Silakan training manual dari halaman admin.');
                }
            }
            
            return back()->with('success', 'Data Guru & Biometrik berhasil ditambahkan (training dilewati: script tidak ditemukan).');
        }

        return back()->with('success', 'Data Guru dan Akun berhasil ditambahkan tanpa biometrik wajah.');
    }

    public function updateGuru(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);
        $user = $guru->user;

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'required|string|unique:gurus,nip,'.$guru->id,
            'mapel_ajar' => 'nullable|array',
            'mapel_ajar.*' => 'string',
            'password' => 'nullable|string|min:6',
            'id_kelas_wali' => 'nullable|string|max:50',
            'face_samples' => 'nullable|array',
            'face_samples.*' => 'string'
        ]);

        DB::transaction(function () use ($request, $guru, $user) {
            $userData = [
                'username' => $request->nip,
                'nama_lengkap' => $request->nama_lengkap,
            ];
            
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            $guru->update([
                'nip' => $request->nip,
                'mapel_ajar' => $request->mapel_ajar ? implode(', ', $request->mapel_ajar) : '-',
                'id_kelas_wali' => $request->id_kelas_wali,
            ]);

            // Update mapping
            $guru->mapels()->delete();
            if ($request->mapel_ajar) {
                foreach ($request->mapel_ajar as $mapel) {
                    \App\Models\GuruMapel::create([
                        'guru_id' => $guru->id,
                        'nama_mapel' => $mapel
                    ]);
                }
            }
        });

        if ($request->has('face_samples') && count($request->face_samples) > 0) {
            $datasetPath = storage_path('app/public/dataset');
            
            // Delete old samples
            $files = glob($datasetPath . "/User.{$user->id}.*.jpg");
            foreach ($files as $file) {
                if (is_file($file)) unlink($file);
            }

            if (!file_exists($datasetPath)) {
                mkdir($datasetPath, 0777, true);
            }

            // Save new samples
            foreach ($request->face_samples as $index => $base64Image) {
                $imageParts = explode(";base64,", $base64Image);
                if (count($imageParts) == 2) {
                    $decodedImage = base64_decode($imageParts[1]);
                    $fileName = "User.{$user->id}." . ($index + 1) . ".jpg";
                    file_put_contents($datasetPath . DIRECTORY_SEPARATOR . $fileName, $decodedImage);
                }
            }

            // Retrain
            $pythonScriptPath = storage_path('app/public/train.py');
            if (file_exists($pythonScriptPath)) {
                $output = \App\Services\PythonRunner::runRaw($pythonScriptPath);
                if (!file_exists(storage_path('app/public/trainer.yml'))) {
                    return back()->with('warning', 'Data Guru diperbarui, namun training model gagal: ' . $output);
                }
            }
            return back()->with('success', 'Data Guru dan biometrik wajah berhasil diperbarui.');
        }

        return back()->with('success', 'Data Guru berhasil diperbarui.');
    }

    public function destroyGuru($id)
    {
        $guru = Guru::findOrFail($id);
        $user = $guru->user;
        
        DB::transaction(function () use ($guru, $user) {
            $guru->delete();
            $user->delete();
        });

        // Hapus biometrik dataset (berdasarkan User ID)
        $datasetPath = storage_path('app/public/dataset');
        $files = glob($datasetPath . "/User.{$user->id}.*.jpg");
        foreach ($files as $file) {
            if (is_file($file)) unlink($file);
        }

        // Retrain model
        $pythonScriptPath = storage_path('app/public/train.py');
        if (file_exists($pythonScriptPath)) {
            \App\Services\PythonRunner::runRaw($pythonScriptPath);
        }

        return back()->with('success', 'Data Guru dan biometrik wajah berhasil dihapus.');
    }

    public function bulkDestroyGuru(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:gurus,id'
        ]);

        $gurus = Guru::whereIn('id', $request->ids)->with('user')->get();
        $datasetPath = storage_path('app/public/dataset');

        DB::transaction(function () use ($gurus, $datasetPath) {
            foreach ($gurus as $guru) {
                $user = $guru->user;
                // Hapus biometrik
                if ($user) {
                    $files = glob($datasetPath . "/User.{$user->id}.*.jpg");
                    foreach ($files as $file) {
                        if (is_file($file)) unlink($file);
                    }
                }

                $guru->delete();
                if ($user) $user->delete();
            }
        });

        // Retrain model menggunakan PythonRunner
        $pythonScriptPath = storage_path('app/public/train.py');
        if (file_exists($pythonScriptPath)) {
            \App\Services\PythonRunner::runRaw($pythonScriptPath);
        }

        return back()->with('success', count($gurus) . ' Data Guru dan biometrik wajah berhasil dihapus secara massal.');
    }

    public function indexSiswa(Request $request)
    {
        $query = Siswa::with('user');

        // Pencarian (Search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            })->orWhere('nis', 'like', "%{$search}%");
        }

        // Filter berdasarkan Kelas
        if ($request->filled('kelas')) {
            $query->where('id_kelas', $request->kelas);
        }

        $siswas = $query->latest()->paginate(10)->withQueryString();
        
        // Ambil list semua nama kelas dari tabel master Kelas untuk dropdown filter
        $kelasOptions = Kelas::orderBy('nama_kelas')->pluck('nama_kelas');

        return view('admin.siswa.index', compact('siswas', 'kelasOptions'));
    }

    public function storeSiswa(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nis' => 'required|string|unique:siswas,nis',
            'id_kelas' => 'required|string|max:50',
            'password' => 'required|string|min:6',
            'face_samples' => 'nullable|array',
            'face_samples.*' => 'string'
        ]);

        $siswaId = null;
        $user = null;

        DB::transaction(function () use ($request, &$siswaId, &$user) {
            $user = User::create([
                'username' => $request->nis, // NIS acts as username
                'nama_lengkap' => $request->nama_lengkap,
                'password' => Hash::make($request->password),
                'role' => 'siswa'
            ]);

            $siswa = Siswa::create([
                'user_id' => $user->id,
                'nis' => $request->nis,
                'id_kelas' => $request->id_kelas,
            ]);
            
            $siswaId = $siswa->id;
        });

        // Simpan Biometrik jika ada
        if ($request->has('face_samples') && count($request->face_samples) > 0) {
            $datasetPath = storage_path('app/public/dataset');
            if (!file_exists($datasetPath)) {
                mkdir($datasetPath, 0777, true);
            }

            foreach ($request->face_samples as $index => $base64Image) {
                $imageParts = explode(";base64,", $base64Image);
                if (count($imageParts) == 2) {
                    $decodedImage = base64_decode($imageParts[1]);
                    // Format required by train.py: User.[USER_ID].[Index].jpg
                    $fileName = "User.{$user->id}." . ($index + 1) . ".jpg";
                    file_put_contents($datasetPath . DIRECTORY_SEPARATOR . $fileName, $decodedImage);
                }
            }

            // Jalankan training otomatis setelah simpan foto wajah
            $pythonScriptPath = storage_path('app/public/train.py');
            if (file_exists($pythonScriptPath)) {
                $output = \App\Services\PythonRunner::runRaw($pythonScriptPath);
                
                if (file_exists(storage_path('app/public/trainer.yml'))) {
                    return back()->with('success', 'Data Siswa & Biometrik wajah berhasil ditambahkan. Model wajah sudah otomatis diperbarui.');
                } else {
                    return back()->with('warning', 'Data Siswa & foto wajah tersimpan, namun training model gagal: ' . $output . '. Silakan training manual dari halaman admin.');
                }
            }
            
            return back()->with('success', 'Data Siswa & Biometrik berhasil ditambahkan (training dilewati: script tidak ditemukan).');
        }

        return back()->with('success', 'Data Siswa dan Akun berhasil ditambahkan tanpa biometrik wajah.');
    }

    public function updateSiswa(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        $user = $siswa->user;

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nis' => 'required|string|unique:siswas,nis,'.$siswa->id,
            'id_kelas' => 'required|string|max:50',
            'password' => 'nullable|string|min:6',
            'face_samples' => 'nullable|array',
            'face_samples.*' => 'string'
        ]);

        DB::transaction(function () use ($request, $siswa, $user) {
            $userData = [
                'username' => $request->nis,
                'nama_lengkap' => $request->nama_lengkap,
            ];
            
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            $siswa->update([
                'nis' => $request->nis,
                'id_kelas' => $request->id_kelas,
            ]);
        });

        if ($request->has('face_samples') && count($request->face_samples) > 0) {
            $datasetPath = storage_path('app/public/dataset');
            
            // Delete old samples
            $files = glob($datasetPath . "/User.{$user->id}.*.jpg");
            foreach ($files as $file) {
                if (is_file($file)) unlink($file);
            }

            if (!file_exists($datasetPath)) {
                mkdir($datasetPath, 0777, true);
            }

            // Save new samples
            foreach ($request->face_samples as $index => $base64Image) {
                $imageParts = explode(";base64,", $base64Image);
                if (count($imageParts) == 2) {
                    $decodedImage = base64_decode($imageParts[1]);
                    $fileName = "User.{$user->id}." . ($index + 1) . ".jpg";
                    file_put_contents($datasetPath . DIRECTORY_SEPARATOR . $fileName, $decodedImage);
                }
            }

            // Retrain
            $pythonScriptPath = storage_path('app/public/train.py');
            if (file_exists($pythonScriptPath)) {
                $output = \App\Services\PythonRunner::runRaw($pythonScriptPath);
                if (!file_exists(storage_path('app/public/trainer.yml'))) {
                    return back()->with('warning', 'Data Siswa diperbarui, namun training model gagal: ' . $output);
                }
            }
            return back()->with('success', 'Data Siswa dan biometrik wajah berhasil diperbarui.');
        }

        return back()->with('success', 'Data Siswa berhasil diperbarui.');
    }

    public function destroySiswa($id)
    {
        $siswa = Siswa::findOrFail($id);
        $user = $siswa->user;
        
        DB::transaction(function () use ($siswa, $user) {
            $siswa->delete();
            $user->delete();
        });

        // Hapus biometrik dataset (berdasarkan User ID)
        $datasetPath = storage_path('app/public/dataset');
        $files = glob($datasetPath . "/User.{$user->id}.*.jpg");
        foreach ($files as $file) {
            if (is_file($file)) unlink($file);
        }

        // Retrain model
        $pythonScriptPath = storage_path('app/public/train.py');
        if (file_exists($pythonScriptPath)) {
            \App\Services\PythonRunner::runRaw($pythonScriptPath);
        }

        return back()->with('success', 'Data Siswa dan biometrik wajah berhasil dihapus.');
    }

    public function bulkDestroySiswa(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:siswas,id'
        ]);

        $siswas = Siswa::whereIn('id', $request->ids)->with('user')->get();
        $datasetPath = storage_path('app/public/dataset');

        DB::transaction(function () use ($siswas, $datasetPath) {
            foreach ($siswas as $siswa) {
                $user = $siswa->user;
                // Hapus biometrik
                $files = glob($datasetPath . "/User.{$user->id}.*.jpg");
                foreach ($files as $file) {
                    if (is_file($file)) unlink($file);
                }

                $siswa->delete();
                if ($user) $user->delete();
            }
        });

        // Retrain model menggunakan PythonRunner
        $pythonScriptPath = storage_path('app/public/train.py');
        if (file_exists($pythonScriptPath)) {
            \App\Services\PythonRunner::runRaw($pythonScriptPath);
        }

        return back()->with('success', count($siswas) . ' Data Siswa dan biometrik wajah berhasil dihapus secara massal.');
    }
}
