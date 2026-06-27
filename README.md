# 📚 E-Learning SD

Aplikasi **E-Learning** berbasis web untuk **Sekolah Dasar (SD)** yang dibangun menggunakan **Laravel 12**. Aplikasi ini mendukung tiga peran pengguna: **Admin**, **Guru**, dan **Siswa**, dengan fitur manajemen materi, tugas, ujian, absensi, dan jadwal pelajaran.

---

## ✨ Fitur Utama

| Fitur | Admin | Guru | Siswa |
|---|:---:|:---:|:---:|
| Manajemen User (Guru & Siswa) | ✅ | - | - |
| Manajemen Kelas & Mata Pelajaran | ✅ | - | - |
| Upload & Kelola Materi | - | ✅ | 👁️ |
| Buat & Nilai Tugas | - | ✅ | ✅ |
| Buat & Kelola Ujian (PG & Essay) | - | ✅ | ✅ |
| Absensi Siswa | - | ✅ | - |
| Jadwal Pelajaran | ✅ | ✅ | ✅ |
| Dashboard Statistik | ✅ | ✅ | ✅ |

---

## ⚙️ Requirements

Pastikan sudah terinstall:

- **PHP** `^8.2`
- **Composer** `^2`
- **Node.js** `^18` + **NPM**
- **MySQL** `^8.0` (disarankan pakai [Laragon](https://laragon.org/download/) atau XAMPP)
- **Python** `^3.8` (untuk fitur Face Recognition)
- **Git**

---

## 🚀 Cara Install (Lokal)

### 1. Clone Repository

```bash
git clone https://github.com/YOUR_USERNAME/elearning-sd.git
cd elearning-sd
```

> Ganti `YOUR_USERNAME` dengan username GitHub yang sesuai.

### 2. Install Dependencies PHP

```bash
composer install
```

### 3. Buat File `.env`

```bash
cp .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Konfigurasi Database

Buka file `.env` dan sesuaikan bagian database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=elearning_sd   # ← buat database ini dulu di phpMyAdmin/HeidiSQL
DB_USERNAME=root
DB_PASSWORD=               # ← isi kalau ada password
```

> **Laragon / XAMPP:** Buka `http://localhost/phpmyadmin`, buat database baru dengan nama `elearning_sd`.

### 6. Jalankan Migrasi & Seeder

```bash
php artisan migrate --seed
```

Perintah ini akan membuat semua tabel dan mengisi data awal (admin, guru, siswa dummy).

### 7. Buat Symbolic Link Storage

```bash
php artisan storage:link
```

### 8. Install Dependencies Node & Build Assets

```bash
npm install
npm run build
```

### 9. Jalankan Aplikasi

```bash
php artisan serve
```

Buka browser dan akses: **[http://localhost:8000](http://localhost:8000)**

### 10. Setup Face Recognition

Aplikasi ini menggunakan Python untuk fitur pengenalan wajah. Lakukan langkah berikut:

1. Pastikan **Python** sudah terinstall dan masuk ke PATH.
2. Buka terminal/cmd dan install library yang dibutuhkan:
   ```bash
   pip install opencv-contrib-python numpy pillow
   ```
3. Karena `trainer.yml` (model wajah) tidak disertakan di repositori ini demi privasi, Anda **wajib** melakukan *training* ulang.
4. Login ke aplikasi, masuk ke menu pendaftaran/kelola wajah, dan daftarkan wajah siswa/user untuk men-generate file `trainer.yml` yang baru.

---

## 🔑 Akun Default (Setelah Seeder)

| Role | Username | Password |
|---|---|---|
| Admin | `admin` | `password` |
| Guru | `1234567890` | `password` |
| Siswa | `987654321` | `password` |

> ⚠️ **Penting:** Segera ganti password setelah login pertama kali!

---

## 🛠️ Development Mode (Hot Reload)

Untuk development dengan Vite hot-reload, jalankan dua terminal secara bersamaan:

**Terminal 1:**
```bash
php artisan serve
```

**Terminal 2:**
```bash
npm run dev
```

---

## 📁 Struktur Direktori Penting

```
elearning-sd/
├── app/
│   ├── Http/Controllers/    # Controller per role (Admin, Guru, Siswa)
│   └── Models/              # Eloquent Models
├── database/
│   ├── migrations/          # Skema database
│   └── seeders/             # Data awal (dummy data)
├── resources/
│   └── views/               # Blade templates (admin, guru, siswa)
├── routes/
│   └── web.php              # Definisi semua route
└── public/                  # File publik (gambar, CSS, JS)
```

---

## 🐛 Troubleshooting

**Error: `SQLSTATE[HY000] [1049] Unknown database`**
→ Buat dulu database `elearning_sd` di phpMyAdmin.

**Error: `php artisan` not found**
→ Pastikan kamu berada di folder project: `cd elearning-sd`

**Halaman blank / CSS tidak muncul**
→ Jalankan `npm run build` terlebih dahulu.

**Error permission `storage/`**
→ Jalankan: `php artisan storage:link`

**Error Face Recognition / OpenCV tidak jalan**
→ Pastikan Python sudah terinstall. Buka terminal dan jalankan `pip install opencv-contrib-python numpy pillow`. Pastikan juga Anda sudah melakukan proses "Training Wajah" di dalam aplikasi agar file `trainer.yml` terbentuk.

---

## 📝 Tech Stack

- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend:** Blade + Vite + Tailwind CSS (atau Bootstrap)
- **Database:** MySQL
- **PDF Export:** barryvdh/laravel-dompdf
- **Auth:** Laravel built-in session auth

---

## 📄 Lisensi

Project ini dibuat untuk keperluan **tugas / skripsi / portofolio**. Bebas digunakan dan dimodifikasi.
