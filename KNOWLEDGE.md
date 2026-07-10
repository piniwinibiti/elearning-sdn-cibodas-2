# Knowledge Base — E-Learning SDN Cibodas 2

Dokumen ini berisi rangkuman teknis mendetail mengenai aplikasi **E-Learning SD**, sebuah platform pembelajaran daring untuk Sekolah Dasar yang dibangun dengan Laravel 12. Tujuannya sebagai referensi cepat bagi siapa pun (termasuk AI assistant) yang melanjutkan pengembangan project ini.

---

## 1. Ringkasan Project

- **Nama aplikasi:** E-Learning SD (`APP_NAME="E-Learning SD"`, lihat `.env.example`)
- **Domain:** Aplikasi manajemen pembelajaran untuk Sekolah Dasar (SD) dengan 3 peran pengguna: **Admin**, **Guru**, **Siswa**.
- **Bahasa/locale default:** Indonesia (`APP_LOCALE=id`, `APP_FAKER_LOCALE=id_ID`).

---

## 2. Versi & Requirement Environment

| Komponen | Versi/Requirement |
|---|---|
| PHP | `^8.2` |
| Laravel Framework | `^12.0` |
| Composer | `^2` |
| Node.js | `^18`+ (NPM) |
| MySQL | `^8.0` |
| Python | `^3.8` (khusus fitur Face Recognition) |
| Laravel Tinker | `^2.10.1` |

### Dependency Composer (backend)
- `barryvdh/laravel-dompdf: ^3.1` — generate/export PDF (laporan).
- `laravel/framework: ^12.0`
- `laravel/tinker: ^2.10.1`

### Dev dependency Composer
- `fakerphp/faker: ^1.23` — data dummy/seeder (locale `id_ID`).
- `laravel/pail: ^1.2.2` — real-time log viewer di terminal.
- `laravel/pint: ^1.24` — code style fixer (PSR-12 based).
- `laravel/sail: ^1.41` — Docker dev environment (tersedia tapi project lebih umum dijalankan via Laragon/XAMPP lokal).
- `mockery/mockery: ^1.6`, `phpunit/phpunit: ^11.5.3`, `nunomaduro/collision: ^8.6` — testing.

### Dependency NPM (frontend build)
- `vite: ^7.0.7` — build tool utama.
- `laravel-vite-plugin: ^2.0.0` — integrasi Vite ↔ Laravel (Blade `@vite` directive).
- `tailwindcss: ^4.2.1` + `@tailwindcss/vite: ^4.0.0` — utility-first CSS (Tailwind v4, pakai plugin Vite langsung, tanpa `tailwind.config.js` klasik).
- `autoprefixer: ^10.4.24`, `postcss: ^8.5.6`
- `flowbite: ^4.0.1` — komponen UI berbasis Tailwind (dropdown, modal, dsb).
- `axios: ^1.11.0` — HTTP client sisi frontend (AJAX ke endpoint face recognition, dsb).
- `concurrently: ^9.0.1` — menjalankan banyak proses dev sekaligus (lihat script `composer dev`).

---

## 3. Arsitektur & Stack

- **Backend:** Laravel 12 (PHP 8.2+), pola MVC standar Laravel — tanpa API terpisah (bukan SPA), render via Blade templating.
- **Frontend:** Blade + Vite + Tailwind CSS v4, ditambah library **Flowbite** untuk komponen interaktif. Tidak ada framework JS (React/Vue) — interaksi dinamis (mis. kamera untuk face scan) memakai vanilla JS + `axios`.
- **Autentikasi:** Session-based auth bawaan Laravel (`AuthController`), **bukan** Laravel Breeze/Fortify/Jetstream — custom login form dengan tambahan **face login** (`login.face` route).
- **Otorisasi:** Middleware kustom `RoleMiddleware` (`app/Http/Middleware/RoleMiddleware.php`) — cek `auth()->user()->role` terhadap parameter role yang di-pass di route (`role:admin`, `role:guru`, `role:siswa`). Bukan pakai Spatie Permission atau Gate/Policy formal.
- **Database:** MySQL 8.0 (`DB_CONNECTION=mysql`), nama database default `elearning_sd`.
- **Session/Cache/Queue:** Semua menggunakan driver `database` (`SESSION_DRIVER=database`, `CACHE_STORE=database`, `QUEUE_CONNECTION=database`) — tidak bergantung pada Redis meskipun konfigurasi Redis tersedia di `.env.example` (opsional/tidak dipakai aktif).
- **Mail:** Driver `log` secara default (belum ada integrasi SMTP produksi).
- **PDF Export:** `barryvdh/laravel-dompdf` untuk generate laporan (nilai, absensi) dalam format PDF (lihat `resources/views/admin/laporan_pdf.blade.php` dan route `laporan.pdf` / `laporan.download`).
- **Face Recognition:** Implementasi **hybrid PHP + Python**, dijelaskan detail di bagian 6.

---

## 4. Struktur Direktori Penting

```
elearning-sd/
├── app/
│   ├── Http/Controllers/       # Controller per fitur & per role
│   │   ├── Admin/              # Sub-namespace controller khusus admin (Kelas, Mapel, Jadwal, KenaikanKelas)
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── FaceRecognitionController.php
│   │   ├── AbsensiController.php / GuruAbsensiController.php
│   │   ├── MateriController.php
│   │   ├── TugasController.php
│   │   ├── UjianController.php
│   │   ├── LaporanController.php
│   │   └── SiswaAkademikController.php
│   ├── Http/Middleware/RoleMiddleware.php
│   ├── Models/                 # Eloquent Models (lihat bagian 5)
│   └── Services/PythonRunner.php  # Wrapper aman proc_open() untuk jalankan script Python di Windows
├── database/
│   ├── migrations/             # Skema database (lihat bagian 5)
│   └── seeders/DatabaseSeeder.php  # Data dummy: 1 admin, 21 guru, 51 siswa, 30 materi, 30 tugas
├── resources/views/
│   ├── layouts/                # Layout Blade bersama
│   ├── auth/                   # Halaman login
│   ├── admin/                  # View khusus admin (guru, siswa, kelas, mapel, jadwal, kenaikan, laporan_pdf)
│   ├── guru/                   # View khusus guru (materi, tugas, ujian, absensi, laporan, scanner)
│   └── siswa/                  # View khusus siswa (materi, tugas, ujian, absensi, akademik)
├── routes/web.php              # Semua route didefinisikan di sini (tidak ada api.php aktif)
├── storage/app/public/         # Berisi script Python (recognize.py, train.py), dataset wajah, trainer.yml, hasil absensi
└── public/                     # Asset publik hasil build Vite
```

---

## 5. Skema Database & Model (Eloquent)

Berdasarkan urutan migrasi di `database/migrations/`:

| Tabel | Migrasi | Model | Keterangan |
|---|---|---|---|
| `users` | bawaan Laravel | `User.php` | Field kustom: `username`, `password`, `nama_lengkap`, `role` (admin/guru/siswa). **Bukan** pakai `email` sebagai login. |
| `siswas` | `2026_02_23_231248` | `Siswa.php` | Profil siswa: `user_id`, `nis`, `id_kelas`. |
| `gurus` | `2026_02_23_231623` + `2026_02_26` (tambah `id_kelas_wali`) | `Guru.php` | Profil guru: `user_id`, `nip`, `mapel_ajar`, `id_kelas_wali` (wali kelas). |
| `absensis` | `2026_02_23_231624` + `2026_04_15` (tambah context) | `Absensi.php` | Data presensi: `siswa_id`, `id_kelas`, `mata_pelajaran`, `tanggal`, `jam_masuk`, `status`, `foto_bukti`. |
| `materis` | `2026_02_23_232914` | `Materi.php` | Materi pembelajaran: `guru_id`, `id_kelas`, `judul`, `type` (pdf/video), `file_path`. |
| `tugas` | `2026_02_23_232942` + `2026_04_15` (tambah `file_tugas`) | `Tugas.php` | Tugas: `guru_id`, `id_kelas`, `mata_pelajaran`, `judul`, `instruksi`, `file_tugas`, `deadline`. |
| `jawaban_tugas` | `2026_02_23_232942` | `JawabanTugas.php` | Jawaban/submission siswa atas tugas (relasi `hasMany` dari `Tugas`), termasuk proses penilaian (`grade`). |
| `ujians` | `2026_03_08_212633` + tambahan `tipe`, `file_soal` | `Ujian.php` | Ujian: `guru_id`, `id_kelas`, `mata_pelajaran`, `judul`, `waktu_menit`, `tipe` (pilihan ganda/essay), `teks_essay`, `file_soal`. |
| `soal_ujians` | `2026_03_08_212634` | `SoalUjian.php` | Soal untuk ujian pilihan ganda. |
| `jawaban_ujian_essays` | `2026_03_08_230837` | `JawabanUjianEssay.php` | Jawaban ujian essay siswa (dinilai manual oleh guru — `nilaiEssay`). |
| `jawaban_ujian_gandas` | `2026_06_16_050413` | `JawabanUjianGanda.php` | Jawaban ujian pilihan ganda siswa (dinilai otomatis). |
| `kelas` | `2026_04_15_041914` | `Kelas.php` | Data kelas (mis. `1A`–`6B`). |
| `mapels` | `2026_04_15_053110` + `2026_04_16` (tambah `kode`) | `Mapel.php` | Data mata pelajaran. |
| `guru_mapels` | `2026_04_15_120545` | `GuruMapel.php` | Relasi pivot guru ↔ mata pelajaran yang diajar. |
| `jadwals` | `2026_04_15_123455` | `Jadwal.php` | Jadwal pelajaran: `id_kelas`, `hari`, `jam_mulai`, `jam_selesai`, `nama_mapel` — dipakai untuk validasi presensi mandiri (cek jadwal aktif). |

**Catatan relasi kunci:**
- `User` → `hasOne(Siswa)` dan `hasOne(Guru)` — satu akun bisa berperan sebagai siswa **atau** guru (role ditentukan field `role` di tabel `users`).
- `Ujian` → `belongsTo(Guru)`, `hasMany(SoalUjian)`, `hasMany(JawabanUjianGanda)`.
- `Tugas` → `belongsTo(Guru)`, `hasMany(JawabanTugas)`.

**Akun default hasil seeder** (`php artisan migrate --seed`):

| Role | Username | Password |
|---|---|---|
| Admin | `admin` | `password` |
| Guru | `1234567890` | `password` |
| Siswa | `987654321` | `password` |

Seeder juga membuat 20 guru dummy dan 50 siswa dummy, 30 materi dummy, dan 30 tugas dummy (memakai Faker locale `id_ID`).

---

## 6. Fitur Utama (Detail)

Berdasarkan tabel fitur di README dan penelusuran route/controller:

### 6.1 Manajemen Pengguna & Struktur Sekolah (Admin)
- CRUD Guru & Siswa (single dan bulk delete) — `AdminController`.
- CRUD Kelas & Mata Pelajaran — `Admin\KelasController`, `Admin\MapelController` (route resource penuh).
- Manajemen Jadwal Pelajaran — `Admin\JadwalController`, termasuk endpoint AJAX `guru-mapels/{id}` untuk mengambil mapel yang diampu guru tertentu (dependent dropdown).
- **Kenaikan Kelas** — `Admin\KenaikanKelasController`, fitur untuk memproses kenaikan kelas siswa secara massal di akhir tahun ajaran.
- Laporan bulanan kehadiran (download) — `AdminController::downloadLaporanBulananHadir`.

### 6.2 Materi Pembelajaran (Guru buat, Siswa lihat)
- Guru: create/update/delete materi (single & bulk), tipe materi `pdf` atau `video` (`MateriController`).
- Siswa: melihat daftar materi sesuai kelasnya (read-only).

### 6.3 Tugas
- Guru: CRUD tugas (dengan deadline & file lampiran), melihat daftar submission siswa, dan menilai jawaban (`grade`).
- Siswa: melihat daftar tugas, detail tugas, dan upload jawaban (file).

### 6.4 Ujian (Pilihan Ganda & Essay)
- Guru: CRUD ujian, dua tipe soal (pilihan ganda otomatis dinilai via `JawabanUjianGanda`, essay dinilai manual via `nilaiEssay`), melihat rekap jawaban siswa.
- Siswa: mengerjakan ujian dengan **verifikasi wajah wajib sebelum submit** (`ujian.verify` → `FaceRecognitionController::verifySiswaAuth`), lalu submit jawaban (`ujian.submit`).

### 6.5 Absensi (Presensi)
Ada **dua alur** presensi yang berjalan paralel:
1. **Presensi mandiri siswa berbasis jadwal** — siswa scan wajah sendiri (`AbsensiController` + `FaceRecognitionController::recognize`), sistem otomatis mengecek jadwal pelajaran aktif saat itu (hari & jam), memastikan siswa belum absen untuk mapel tersebut hari ini, baru mencatat kehadiran per mata pelajaran.
2. **Scanner absensi oleh guru** — guru menjalankan scanner wajah dari dashboard/menu absensi khusus (`GuruAbsensiController`, juga ada legacy `scannerGuru`/`processScanner`), untuk absensi manual atau massal per kelas.
- Guru juga bisa input/hapus absensi manual (`AbsensiController::storeManual`, `destroy`).

### 6.6 Jadwal Pelajaran
- Dilihat oleh ketiga role (admin kelola, guru & siswa lihat sesuai konteks masing-masing).

### 6.7 Dashboard Statistik
- Dashboard berbeda per role: `DashboardController::adminDashboard`, `guruDashboard`, `siswaDashboard` — menampilkan ringkasan statistik relevan (jumlah siswa/guru, tugas belum dinilai, jadwal hari ini, dsb).

### 6.8 Laporan (Guru & Siswa)
- Guru: laporan nilai/kehadiran kelas yang diajar, bisa diunduh sebagai PDF (`LaporanController`, dompdf).
- Siswa: rekap presensi pribadi (`SiswaAkademikController::indexPresensi`) dan laporan nilai pribadi (`indexNilai`).

### 6.9 Face Recognition (fitur khas project ini)
Dijelaskan detail terpisah di bagian 7 karena kompleksitasnya.

---

## 7. Face Recognition — Detail Implementasi

Ini adalah fitur paling teknis & unik dari project ini: **autentikasi/verifikasi berbasis wajah** menggunakan kombinasi **Laravel (PHP) sebagai orchestrator** dan **Python + OpenCV sebagai engine pengenalan wajah**.

### 7.1 Alur Kerja
1. **PHP tidak melakukan image processing sendiri** — ia hanya menerima gambar (base64 dari kamera browser via JS), menyimpannya sementara (`storage/app/temp`), lalu memanggil script Python eksternal via `App\Services\PythonRunner`.
2. **`PythonRunner`** (`app/Services/PythonRunner.php`) adalah service kustom yang menjalankan proses Python menggunakan `proc_open()` manual (bukan `Illuminate\Process` atau `shell_exec`), karena ditemukan bahwa `Process::run()` bawaan Laravel **hang/deadlock di Windows** akibat buffer pipe stdout/stderr penuh. Solusinya: baca (drain) pipe stdout & stderr secara non-blocking dalam loop sampai proses selesai atau timeout (default 30 detik untuk `run()`, 60 detik untuk `runRaw()`).
3. Path interpreter Python dikonfigurasi lewat env `PYTHON_PATH` (default `python`).

### 7.2 Algoritma Pengenalan Wajah
- Menggunakan **OpenCV (`opencv-contrib-python`)** dengan algoritma **LBPH (Local Binary Patterns Histograms) Face Recognizer** (`cv2.face.LBPHFaceRecognizer_create()`).
- Deteksi wajah memakai **Haar Cascade Classifier** (`haarcascade_frontalface_default.xml`), bukan deep learning/DNN modern — pilihan ini konsisten dengan tujuan project sebagai tugas akhir (ringan, tidak butuh GPU, mudah dijelaskan).
- Dependency Python: `opencv-contrib-python`, `numpy`, `pillow`.

### 7.3 Dua Script Python Inti (`storage/app/public/`)
- **`train.py`** — Membaca semua foto di folder `storage/app/public/dataset/` dengan format nama file `User.[USER_ID].[SAMPLE].jpg`, mendeteksi wajah di tiap foto, lalu melatih model LBPH dan menyimpannya sebagai `trainer.yml` di folder yang sama. Model ini **tidak disertakan di repo** (privasi) — wajib di-generate ulang di tiap instalasi baru.
- **`recognize.py`** — Menerima path gambar sebagai argumen CLI, load `trainer.yml` + cascade, deteksi wajah terbesar dalam frame, jalankan `recognizer.predict()`, lalu kembalikan hasil sebagai **JSON ke stdout**: `{success, confidence, user_id, message}`. Threshold internal: confidence diterima jika `distance < 80` (dikonversi ke skor kemiripan `100 - distance`, dibatasi maksimum 99.9%).

### 7.4 Titik Pemakaian di Aplikasi (`FaceRecognitionController`)
| Fungsi | Konteks | Threshold penerimaan |
|---|---|---|
| `registerFaceDataset` | Pendaftaran wajah user (siswa/guru) — ambil 20 sample foto dari kamera, simpan ke `dataset/`, auto-training saat sample ke-20 tercapai | — |
| `trainAdmin` | Admin memicu training ulang model secara manual | — |
| `recognize` | Presensi mandiri siswa — wajib ada jadwal aktif & belum absen di mapel tsb hari ini | confidence > 20 **dan** `user_id` cocok dengan user yang login |
| `processScanner` / `scannerGuru` | Scanner absensi dari sisi guru (bisa untuk siapa saja yang terdeteksi, bukan hanya user login) | confidence > 20 |
| `verifySiswaAuth` | Verifikasi identitas wajib sebelum siswa submit ujian | confidence > 20 **dan** `user_id` cocok dengan user yang login |

**Catatan keamanan/kualitas yang perlu diperhatikan** (untuk pengembangan lanjutan):
- Threshold 20% relatif rendah untuk standar keamanan biometrik — cukup untuk tugas akhir, tapi bukan untuk produksi sungguhan.
- Model LBPH + Haar Cascade rentan terhadap variasi pencahayaan/sudut, dan tidak ada liveness detection (rawan spoofing dengan foto).
- Data wajah (dataset & trainer.yml) disimpan di `storage/app/public/`, artinya bisa diakses publik jika `storage:link` dan permission tidak dikontrol dengan hati-hati.

---

## 8. Alur Development & Build

### Setup awal
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
pip install opencv-contrib-python numpy pillow   # untuk face recognition
```
Composer juga menyediakan script `composer setup` yang menjalankan urutan ini secara otomatis (kecuali bagian Python).

### Mode development
```bash
composer dev
```
Menjalankan **4 proses sekaligus secara paralel** (via `concurrently`): `php artisan serve`, `php artisan queue:listen`, `php artisan pail` (log viewer real-time), dan `npm run dev` (Vite HMR).

### Build production
```bash
npm run build   # kompilasi asset (Tailwind v4 + Vite)
```

### Testing
```bash
composer test    # clear config lalu jalankan php artisan test (PHPUnit 11)
```

---

## 9. Hal-Hal yang Perlu Diketahui Pengembang Baru

1. **Login pakai `username`, bukan `email`** — field `username` di tabel `users` bisa berisi NIP (guru) atau NIS (siswa) atau `admin`.
2. **Role-based access sepenuhnya via middleware string-based** (`role:admin`, dst), bukan Gate/Policy — jika menambah role baru, cukup daftarkan di middleware group routing.
3. **Tidak ada route API terpisah** — semua interaksi AJAX (face scan, dependent dropdown jadwal) tetap lewat `routes/web.php` dengan middleware `auth` biasa.
4. **File Python bukan bagian dari Laravel app lifecycle** — mereka dieksekusi sebagai proses eksternal per-request, jadi performa dan reliability bergantung pada environment Python di server (path `PYTHON_PATH`, dependency terpasang).
5. **`trainer.yml` dan folder `dataset/` tidak ada di git** (untuk privasi data wajah) — instalasi baru harus melakukan pendaftaran & training wajah ulang sebelum fitur absensi/verifikasi wajah bisa dipakai.
6. **Tailwind v4** dipakai lewat plugin Vite langsung (`@tailwindcss/vite`), sehingga **tidak ada file `tailwind.config.js`** klasik seperti Tailwind v3 — konfigurasi ada di CSS lewat `@import "tailwindcss"` atau sejenisnya di file CSS entry point Vite.
7. **Session, cache, dan queue semuanya pakai driver `database`** — tidak butuh Redis untuk menjalankan project ini secara lokal, meskipun konfigurasi Redis tersedia sebagai opsi di `.env.example`.
