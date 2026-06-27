<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});



Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/login/face', [AuthController::class, 'faceLogin'])->name('login.face');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/face/register', [\App\Http\Controllers\FaceRecognitionController::class, 'registerFaceDataset'])->name('face.register');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        
        // Kelola Guru
        Route::get('/guru', [\App\Http\Controllers\AdminController::class, 'indexGuru'])->name('guru.index');
        Route::post('/guru', [\App\Http\Controllers\AdminController::class, 'storeGuru'])->name('guru.store');
        Route::put('/guru/{id}', [\App\Http\Controllers\AdminController::class, 'updateGuru'])->name('guru.update');
        Route::delete('/guru/{id}', [\App\Http\Controllers\AdminController::class, 'destroyGuru'])->name('guru.destroy');
        Route::delete('/guru', [\App\Http\Controllers\AdminController::class, 'bulkDestroyGuru'])->name('guru.bulk_destroy');
        
        // Core Management
        Route::get('/siswa', [\App\Http\Controllers\AdminController::class, 'indexSiswa'])->name('siswa.index');
        Route::post('/siswa', [\App\Http\Controllers\AdminController::class, 'storeSiswa'])->name('siswa.store');
        Route::put('/siswa/{id}', [\App\Http\Controllers\AdminController::class, 'updateSiswa'])->name('siswa.update');
        Route::delete('/siswa/{id}', [\App\Http\Controllers\AdminController::class, 'destroySiswa'])->name('siswa.destroy');
        Route::delete('/siswa', [\App\Http\Controllers\AdminController::class, 'bulkDestroySiswa'])->name('siswa.bulk_destroy');

        // Kenaikan Kelas
        Route::get('/kenaikan-kelas', [\App\Http\Controllers\Admin\KenaikanKelasController::class, 'index'])->name('kenaikan.index');
        Route::post('/kenaikan-kelas', [\App\Http\Controllers\Admin\KenaikanKelasController::class, 'process'])->name('kenaikan.process');

        Route::resource('/kelas', \App\Http\Controllers\Admin\KelasController::class)->names('kelas');
        Route::resource('/mapel', \App\Http\Controllers\Admin\MapelController::class)->names('mapel');
        Route::get('/jadwal/guru-mapels/{id}', [\App\Http\Controllers\Admin\JadwalController::class, 'getGuruMapels'])->name('jadwal.guru-mapels');
        Route::resource('/jadwal', \App\Http\Controllers\Admin\JadwalController::class)->names('jadwal');

        // Face Recognition
        Route::post('/face/train', [\App\Http\Controllers\FaceRecognitionController::class, 'trainAdmin'])->name('face.train');
        
        // Laporan Admin
        Route::get('/laporan/download', [\App\Http\Controllers\AdminController::class, 'downloadLaporanBulananHadir'])->name('laporan.download');
    });

    // Guru Routes
    Route::middleware('role:guru')->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'guruDashboard'])->name('dashboard');
        
        // Materi
        Route::get('/materi', [\App\Http\Controllers\MateriController::class, 'indexGuru'])->name('materi.index');
        Route::get('/materi/create', [\App\Http\Controllers\MateriController::class, 'create'])->name('materi.create');
        Route::post('/materi', [\App\Http\Controllers\MateriController::class, 'store'])->name('materi.store');
        Route::put('/materi/{id}', [\App\Http\Controllers\MateriController::class, 'update'])->name('materi.update');
        Route::delete('/materi/{id}', [\App\Http\Controllers\MateriController::class, 'destroy'])->name('materi.destroy');
        Route::delete('/materi', [\App\Http\Controllers\MateriController::class, 'bulkDestroy'])->name('materi.bulk_destroy');
        
        // Tugas
        Route::get('/tugas', [\App\Http\Controllers\TugasController::class, 'indexGuru'])->name('tugas.index');
        Route::get('/tugas/create', [\App\Http\Controllers\TugasController::class, 'create'])->name('tugas.create');
        Route::post('/tugas', [\App\Http\Controllers\TugasController::class, 'store'])->name('tugas.store');
        Route::put('/tugas/{id}', [\App\Http\Controllers\TugasController::class, 'update'])->name('tugas.update');
        Route::delete('/tugas/{id}', [\App\Http\Controllers\TugasController::class, 'destroy'])->name('tugas.destroy');
        Route::delete('/tugas', [\App\Http\Controllers\TugasController::class, 'bulkDestroy'])->name('tugas.bulk_destroy');
        Route::get('/tugas/{id}/submissions', [\App\Http\Controllers\TugasController::class, 'submissions'])->name('tugas.submissions');
        Route::post('/tugas/grade/{jawaban_id}', [\App\Http\Controllers\TugasController::class, 'grade'])->name('tugas.grade');
        
        // Laporan
        Route::get('/laporan', [\App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/pdf', [\App\Http\Controllers\LaporanController::class, 'downloadPdf'])->name('laporan.pdf');

        // Ujian (Guru)
        Route::get('/ujian', [\App\Http\Controllers\UjianController::class, 'indexGuru'])->name('ujian.index');
        Route::get('/ujian/create', [\App\Http\Controllers\UjianController::class, 'create'])->name('ujian.create');
        Route::post('/ujian', [\App\Http\Controllers\UjianController::class, 'store'])->name('ujian.store');
        Route::get('/ujian/{id}/edit', [\App\Http\Controllers\UjianController::class, 'edit'])->name('ujian.edit');
        Route::put('/ujian/{id}', [\App\Http\Controllers\UjianController::class, 'update'])->name('ujian.update');
        Route::delete('/ujian/{id}', [\App\Http\Controllers\UjianController::class, 'destroy'])->name('ujian.destroy');
        Route::get('/ujian/{id}/jawaban', [\App\Http\Controllers\UjianController::class, 'jawaban'])->name('ujian.jawaban');
        Route::post('/ujian-essay/{id}/nilai', [\App\Http\Controllers\UjianController::class, 'nilaiEssay'])->name('ujian.nilai');

        // Scanner Absensi Khusus Guru (Legacy Dashboard Scanner - keep or redirect)
        Route::get('/scanner', [\App\Http\Controllers\FaceRecognitionController::class, 'scannerGuru'])->name('scanner');
        Route::post('/scanner/process', [\App\Http\Controllers\FaceRecognitionController::class, 'processScanner'])->name('scanner.process');

        // New Dedicated Absensi Module
        Route::get('/absensi', [\App\Http\Controllers\GuruAbsensiController::class, 'index'])->name('absensi.index');
        Route::post('/absensi/store', [\App\Http\Controllers\GuruAbsensiController::class, 'store'])->name('absensi.store');
        Route::post('/absensi/scanner/process', [\App\Http\Controllers\GuruAbsensiController::class, 'scannerProcess'])->name('absensi.scanner.process');
        Route::get('/absensi/students', [\App\Http\Controllers\GuruAbsensiController::class, 'getStudents'])->name('absensi.students');

        // Absensi Manual oleh Guru (Legacy - remove later if redundant)
        Route::post('/absensi/manual', [\App\Http\Controllers\AbsensiController::class, 'storeManual'])->name('absensi.manual');
        Route::delete('/absensi/{id}', [\App\Http\Controllers\AbsensiController::class, 'destroy'])->name('absensi.destroy');
    });

    // Siswa Routes
    Route::middleware('role:siswa')->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'siswaDashboard'])->name('dashboard');
        
        // Akademik
        Route::get('/rekap-presensi', [\App\Http\Controllers\SiswaAkademikController::class, 'indexPresensi'])->name('akademik.presensi');
        Route::get('/laporan-nilai', [\App\Http\Controllers\SiswaAkademikController::class, 'indexNilai'])->name('akademik.nilai');

        // Absensi
        Route::get('/absensi', [\App\Http\Controllers\AbsensiController::class, 'index'])->name('absensi.index');
        Route::post('/absensi/store', [\App\Http\Controllers\AbsensiController::class, 'store'])->name('absensi.store');
        Route::post('/absensi/scan', [\App\Http\Controllers\FaceRecognitionController::class, 'recognize'])->name('absensi.scan');
        
        // Materi
        Route::get('/materi', [\App\Http\Controllers\MateriController::class, 'indexSiswa'])->name('materi.index');
        
        // Tugas
        Route::get('/tugas', [\App\Http\Controllers\TugasController::class, 'indexSiswa'])->name('tugas.index');
        Route::get('/tugas/{id}', [\App\Http\Controllers\TugasController::class, 'showSiswa'])->name('tugas.show');
        Route::post('/tugas/{id}/upload', [\App\Http\Controllers\TugasController::class, 'uploadJawaban'])->name('tugas.upload');

        // Ujian & Face ID Verification
        Route::get('/ujian', [\App\Http\Controllers\UjianController::class, 'index'])->name('ujian.index');
        Route::post('/ujian/verify-face', [\App\Http\Controllers\FaceRecognitionController::class, 'verifySiswaAuth'])->name('ujian.verify');
        Route::post('/ujian/{id}/submit', [\App\Http\Controllers\UjianController::class, 'submit'])->name('ujian.submit');
    });
});
