# Panduan Face Recognition — E-Learning SDN Cibodas 2

Dokumen ini fokus khusus pada fitur **Face Recognition** di aplikasi ini: cara kerjanya, cara pemakaian dari sisi user, cara melakukan perubahan manual (tuning/konfigurasi), dan langkah-langkah pengetesan. Untuk gambaran umum project, lihat `KNOWLEDGE.md`.

---

## 1. Gambaran Umum

Fitur ini memakai arsitektur **hybrid PHP (Laravel) + Python (OpenCV)**:

- **Laravel** menangani HTTP request, validasi, penyimpanan file sementara, dan orkestrasi proses.
- **Python** melakukan pekerjaan berat (deteksi & pengenalan wajah) lewat 2 script mandiri:
  - `storage/app/public/train.py` — melatih model dari dataset foto.
  - `storage/app/public/recognize.py` — mengenali wajah dari 1 foto, output JSON.
- Komunikasi PHP ↔ Python **bukan** lewat library binding, melainkan **spawn proses CLI** via `App\Services\PythonRunner` (`proc_open()` manual, karena `Illuminate\Process` terbukti hang di Windows).
- Algoritma: **LBPH (Local Binary Patterns Histograms)** dari `cv2.face` + deteksi wajah **Haar Cascade** (`haarcascade_frontalface_default.xml`). Bukan deep learning — cocok untuk beban ringan tanpa GPU.

### Titik pemakaian fitur ini di aplikasi

| Fitur | Route | Controller@method | Siapa yang pakai |
|---|---|---|---|
| Login dengan wajah | `POST /login/face` (`login.face`) | `AuthController@faceLogin` | Semua role (halaman login, tab "Face Login") |
| Pendaftaran/registrasi wajah | `POST /face/register` (`face.register`) | `FaceRecognitionController@registerFaceDataset` | Guru & Siswa (modal di dashboard masing-masing) |
| Training model (manual, admin) | `POST /admin/face/train` (`admin.face.train`) | `FaceRecognitionController@trainAdmin` | Admin (tombol di dashboard admin) |
| Presensi mandiri siswa | `POST /siswa/absensi/scan` (`siswa.absensi.scan`) | `FaceRecognitionController@recognize` | Siswa (modal "Presensi Wajah" di dashboard/absensi) |
| Scanner absensi guru | `POST /guru/scanner/process` (`guru.scanner.process`) legacy, atau `POST /guru/absensi/scanner/process` (`guru.absensi.scanner.process`) | `FaceRecognitionController@processScanner` / `GuruAbsensiController@scannerProcess` | Guru (halaman scanner absensi kelas) |
| Verifikasi identitas sebelum ujian | `POST /siswa/ujian/verify-face` (`siswa.ujian.verify`) | `FaceRecognitionController@verifySiswaAuth` | Siswa (gateway sebelum mengerjakan ujian) |

Semua endpoint di atas menerima `image` berupa **base64 data URL** (`data:image/jpeg;base64,...`) yang di-capture dari elemen `<video>` browser (`getUserMedia`) lalu digambar ke `<canvas>` dan di-export via `canvas.toDataURL('image/jpeg', 0.8)`. Tidak ada library JS pihak ketiga untuk kamera — murni native Web API.

---

## 2. Alur Penggunaan (User Flow)

### 2.1 Registrasi/Pendaftaran Wajah (wajib sebelum fitur lain bisa dipakai)

Dilakukan oleh **Guru** atau **Siswa** dari dashboard masing-masing (tombol "Daftar Wajah" / sejenis → membuka modal kamera).

Alur teknis (lihat `resources/views/siswa/dashboard.blade.php` & `resources/views/guru/dashboard.blade.php`):
1. User klik tombol buka modal pendaftaran wajah.
2. Browser minta izin kamera (`getUserMedia`), lalu ada **countdown 3 detik** sebelum mulai capture.
3. Sistem otomatis mengambil **20 sample foto** (`maxSamples = 20`), 1 foto setiap **150ms**, masing-masing langsung dikirim ke `face.register` secara berurutan.
4. Setiap foto disimpan di server dengan format nama **`User.{USER_ID}.{SAMPLE_KE}.jpg`** di folder `storage/app/public/dataset/`.
5. Saat sample ke-20 tercapai, backend **otomatis memicu training** (`train.py`) sehingga `trainer.yml` langsung ter-update tanpa perlu aksi tambahan dari admin.
6. UI menampilkan progress bar (`sampleCount / maxSamples`) dan pesan sukses di akhir.

> Semakin banyak user mendaftar wajah, semakin besar folder `dataset/` dan semakin lama proses training (karena `train.py` membaca ulang **seluruh** file di `dataset/`, bukan incremental).

### 2.2 Login dengan Wajah

1. Di halaman login (`/login`), pilih tab **"Face Login 📷"**.
2. Kamera aktif otomatis saat tab dipilih (kamera **tidak** aktif di tab "Login biasa" untuk menghemat resource & privasi).
3. User klik tombol capture → 1 foto diambil → dikirim ke `login.face`.
4. Backend menjalankan `recognize.py`, jika `confidence > 20%` dan `user_id` ditemukan → user langsung di-login (`Auth::login($user)`) dan diarahkan ke dashboard sesuai role.
5. Jika gagal, muncul pesan error beserta skor confidence yang didapat (untuk debugging user).

### 2.3 Presensi Mandiri Siswa (berbasis jadwal)

1. Siswa membuka modal "Presensi Wajah" dari dashboard.
2. Sistem **mengecek jadwal pelajaran aktif** untuk kelas siswa tsb (hari & jam sekarang harus berada dalam rentang `jam_mulai`–`jam_selesai` di tabel `jadwals`). Jika tidak ada jadwal aktif → ditolak dengan pesan "Tidak ada jadwal pelajaran aktif".
3. Sistem cek apakah siswa **sudah absen untuk mata pelajaran itu hari ini** → jika sudah, ditolak (mencegah presensi ganda per mapel per hari).
4. Foto dikirim ke `recognize.py`. Diterima jika `confidence > 20%` **dan** `user_id` hasil pengenalan **cocok dengan siswa yang sedang login** (mencegah orang lain absen atas nama siswa tsb).
5. Jika lolos, record `Absensi` dibuat dengan status `hadir`, foto bukti disimpan di `storage/app/public/absensi/`.

### 2.4 Scanner Absensi oleh Guru

1. Guru membuka menu Scanner Absensi (kelas tertentu).
2. Guru mengarahkan kamera ke siswa satu per satu (bukan siswa yang scan sendiri, tapi guru yang scan wajah siswa).
3. Sistem mengenali `user_id` dari wajah yang di-scan (tanpa syarat harus cocok dengan user yang login, karena yang login adalah guru, bukan siswa tsb).
4. Jika ditemukan `user_id` yang terhubung ke data Siswa → absensi otomatis dicatat/di-update (`updateOrCreate` berdasarkan `siswa_id` + `tanggal`).

### 2.5 Verifikasi Wajah Sebelum Ujian

1. Siswa membuka ujian → sebelum masuk ke halaman soal, ada **gateway verifikasi wajah** (`resources/views/siswa/ujian/gateway.blade.php`).
2. Sistem capture foto → kirim ke `siswa.ujian.verify`.
3. Diterima hanya jika `confidence > 20%` **dan** `user_id` cocok dengan user yang sedang login. Ini mencegah joki ujian (orang lain mengerjakan ujian atas nama siswa).
4. Jika lolos, siswa baru bisa lanjut ke halaman submit ujian.

---

## 3. Jika Ingin Mengubah Sistem Secara Manual

Berikut titik-titik konfigurasi yang paling sering perlu disesuaikan, beserta lokasinya di kode.

### 3.1 Mengubah Threshold Kecocokan (Confidence)

Saat ini threshold diterapkan di **dua tempat berbeda dengan makna terbalik** — penting dipahami agar tidak salah ubah:

- **Di `recognize.py`** (baris `if confidence < 80:`) — ini adalah **jarak (distance)** dari algoritma LBPH, semakin kecil semakin mirip. `80` adalah batas maksimum jarak yang masih dianggap "match". Jika distance lolos, dikonversi ke skor persentase: `100 - distance` (dibatasi maksimal 99.9).
- **Di sisi PHP** (semua controller: `AuthController::faceLogin`, `FaceRecognitionController::recognize/processScanner/verifySiswaAuth`) — cek `$output['confidence'] > 20`. Ini adalah **skor persentase hasil konversi**, bukan distance mentah.

**Untuk mengetatkan/melonggarkan akurasi:**
- Ubah angka `80` di `recognize.py` (distance LBPH) → makin kecil makin ketat (lebih sedikit false positive, tapi makin sering "wajah tidak dikenali").
- Ubah angka `> 20` di controller PHP (skor persentase) → makin besar makin ketat.
- **Rekomendasi:** jika ingin akurasi lebih tinggi untuk keperluan produksi/skripsi, naikkan threshold PHP ke `> 50` atau lebih, dan turunkan distance limit di `recognize.py` ke `< 60`–`70`. Sesuaikan berdasarkan hasil testing nyata (lihat bagian 4).
- Threshold ini **tersebar di banyak file** — pastikan konsisten jika diubah:
  - `app/Http/Controllers/AuthController.php`
  - `app/Http/Controllers/FaceRecognitionController.php` (4 method: `recognize`, `processScanner`, `verifySiswaAuth`, dan implisit di `registerFaceDataset` tidak ada threshold)
  - `storage/app/public/recognize.py`

### 3.2 Mengubah Jumlah Sample Foto Saat Registrasi

- Default: **20 sample** per user.
- Diatur di **dua tempat yang harus disamakan**:
  - Frontend: `const maxSamples = 20;` di `resources/views/siswa/dashboard.blade.php` dan `resources/views/guru/dashboard.blade.php`.
  - Backend: kondisi `if ($sample >= 20)` di `FaceRecognitionController::registerFaceDataset` (pemicu auto-training).
- Jika ingin menambah akurasi, naikkan jumlah sample (mis. 30–40) — tapi akan menambah waktu registrasi (kalikan dengan interval capture 150ms) dan waktu training.
- Interval capture (`150ms` di JS, dalam `setInterval`) juga bisa disesuaikan jika kamera/device lambat menangkap gambar jernih.

### 3.3 Mengganti Algoritma Deteksi/Pengenalan Wajah

Jika Haar Cascade + LBPH dirasa kurang akurat (rentan terhadap pencahayaan, sudut wajah, dsb), penggantian dilakukan di **kedua script Python**:
- Ganti `cv2.CascadeClassifier(...)` dengan detector lain (mis. DNN face detector OpenCV, atau `mediapipe`/`face_recognition` berbasis dlib) — perlu install dependency baru via `pip`.
- Ganti `cv2.face.LBPHFaceRecognizer_create()` dengan algoritma lain (`EigenFaceRecognizer`, `FisherFaceRecognizer`, atau model embedding modern seperti FaceNet/ArcFace via `face_recognition`/`deepface`).
- **Penting:** Jika mengganti recognizer, format `trainer.yml` akan berubah total → **wajib retraining ulang semua user** (`trainer.yml` lama tidak kompatibel).
- Format kontrak I/O JSON (`{success, confidence, user_id, message}`) di stdout **harus dipertahankan** agar PHP tetap bisa membaca hasilnya tanpa mengubah controller.

### 3.4 Mengubah Path Python / Environment

- Env var `PYTHON_PATH` di `.env` menentukan interpreter Python yang dipanggil (default: `python`). Jika Python di server memakai virtualenv atau nama executable berbeda (mis. `python3`, atau path lengkap seperti `C:\Python311\python.exe`), set `PYTHON_PATH` sesuai kebutuhan.
- Timeout proses Python bisa diubah di parameter `$timeout` saat memanggil `PythonRunner::run()` / `runRaw()` (default 30 detik untuk `run()`, 60 detik untuk `runRaw()` / training).

### 3.5 Melatih Ulang Model Secara Manual

- **Via UI Admin:** Dashboard Admin → tombol "Train Model Wajah" → memanggil `admin.face.train` → menjalankan `train.py` untuk **seluruh dataset** yang ada.
- **Via CLI langsung** (berguna untuk debugging tanpa lewat HTTP):
  ```bash
  cd storage/app/public
  python train.py
  ```
  Output akan menampilkan jumlah ID wajah unik yang berhasil dilatih, atau pesan error jika dataset kosong/tidak ada wajah terdeteksi.
- **Menghapus data wajah user tertentu:** hapus manual file `User.{USER_ID}.*.jpg` di `storage/app/public/dataset/`, lalu jalankan ulang training (baik via UI atau CLI) agar `trainer.yml` tidak lagi mengenali user tsb.
- **Reset total:** hapus seluruh isi folder `dataset/` dan file `trainer.yml`, lalu minta semua user registrasi ulang.

### 3.6 Mengubah Aturan Bisnis Presensi (bukan bagian face recognition murni, tapi terkait)

- Aturan "harus ada jadwal aktif" dan "tidak boleh absen dobel per mapel per hari" ada di `FaceRecognitionController::recognize()` — bisa dilonggarkan/diperketat di sana jika kebijakan sekolah berubah.

---

## 4. Panduan Pengetesan (Testing)

### 4.1 Prasyarat Environment

1. **Python terpasang** dan bisa dipanggil dari terminal (`python --version`). Jika tidak ada di PATH, set `PYTHON_PATH` di `.env` ke path lengkap.
2. Install dependency Python:
   ```bash
   pip install opencv-contrib-python numpy pillow
   ```
   > Harus `opencv-contrib-python`, **bukan** `opencv-python` biasa — modul `cv2.face` (LBPH) hanya ada di paket `contrib`.
3. Pastikan file `storage/app/public/haarcascade_frontalface_default.xml` ada (dibutuhkan oleh kedua script). Jika belum ada, unduh dari repo resmi OpenCV (`data/haarcascades/haarcascade_frontalface_default.xml`).
4. `storage:link` sudah dijalankan (`php artisan storage:link`) agar folder `storage/app/public` bisa diakses dan file bisa dibaca/ditulis dengan benar oleh Laravel.

### 4.2 Test Cepat Script Python (Tanpa Laravel)

Berguna untuk memastikan environment Python & OpenCV sudah benar sebelum menguji lewat browser.

```bash
cd storage/app/public

# 1. Cek modul terpasang
python -c "import cv2; print(cv2.__version__); print(hasattr(cv2, 'face'))"
# Harus print versi OpenCV dan "True" (jika False berarti opencv-contrib belum terpasang)

# 2. Test training (butuh minimal 1 foto valid di folder dataset/)
python train.py

# 3. Test recognize dengan 1 file gambar contoh
python recognize.py "path/ke/foto_test.jpg"
# Output harus berupa JSON valid, misal:
# {"success": true, "confidence": 87.5, "user_id": 3, "message": "..."}
```

Jika `recognize.py` gagal print JSON valid (mis. muncul traceback Python di stdout), berarti ada error yang **tidak akan** ter-parse oleh `PythonRunner` (akan dianggap gagal generik) — cek `storage/logs/laravel.log` untuk detail stderr yang ditangkap.

### 4.3 Test End-to-End via Aplikasi (Manual/UI)

Urutan pengetesan yang disarankan, dari fitur paling dasar ke paling bergantung:

1. **Registrasi wajah dulu** (wajib pertama kali):
   - Login sebagai siswa/guru dummy dari seeder (lihat kredensial di `KNOWLEDGE.md` bagian 5).
   - Buka dashboard → modal pendaftaran wajah → izinkan kamera → tunggu 20 sample selesai + auto-training.
   - Verifikasi: cek folder `storage/app/public/dataset/` berisi 20 file `User.{ID}.*.jpg`, dan file `trainer.yml` ter-update (cek timestamp modifikasi file).

2. **Test Login dengan Wajah:**
   - Logout, buka `/login`, pilih tab Face Login, capture wajah yang sama.
   - Harus berhasil login dan redirect ke dashboard sesuai role.
   - Coba dengan wajah orang lain / foto berbeda → harus ditolak dengan pesan "tidak dikenali".

3. **Test Presensi Mandiri Siswa:**
   - Pastikan ada data di tabel `jadwals` yang **aktif untuk hari & jam pengujian saat ini** (kalau tidak ada, tambahkan manual lewat menu Admin → Jadwal, atau via Tinker/seeder, dengan `jam_mulai`/`jam_selesai` mencakup waktu sekarang).
   - Login sebagai siswa, buka modal presensi wajah, capture.
   - Harus tercatat di tabel `absensis` dengan `status = hadir`.
   - Coba capture kedua kali di mapel yang sama pada hari yang sama → harus ditolak (mencegah dobel absen).

4. **Test Scanner Absensi Guru:**
   - Login sebagai guru, buka menu scanner absensi.
   - Scan wajah siswa yang sudah terdaftar → cek absensi ter-update di tabel `absensis` untuk siswa tsb.

5. **Test Verifikasi Wajah Sebelum Ujian:**
   - Login sebagai siswa, buka salah satu ujian yang tersedia untuk kelasnya.
   - Harus muncul gateway verifikasi wajah sebelum bisa masuk ke halaman soal.
   - Coba gagal verifikasi (tutup kamera dengan tangan / pakai wajah lain) → harus tertahan di gateway.

### 4.4 Edge Case yang Perlu Diuji Khusus

- **Tidak ada `trainer.yml` sama sekali** (instalasi baru, belum ada yang registrasi) → semua fitur recognize harus mengembalikan pesan error yang jelas ("Model wajah belum dibuat"), bukan crash 500.
- **Pencahayaan buruk / wajah tertutup sebagian** → pastikan pesan "Wajah tidak terdeteksi" muncul dengan baik (bukan hasil pengenalan asal-asalan).
- **Proses Python timeout** (mis. environment lambat) → `PythonRunner` akan mengembalikan `null` setelah 30/60 detik, pastikan UI menampilkan pesan gagal yang wajar, bukan hang selamanya.
- **User ganda mencoba absen mengatasnamakan siswa lain** via scanner guru → karena `processScanner` tidak mengecek kecocokan dengan user login (memang guru yang scan siswa), pastikan hanya guru yang berwenang yang bisa mengakses halaman scanner (dicek lewat middleware `role:guru`, bukan face verification tambahan).
- **Bandwidth/ukuran payload** — karena setiap sample registrasi & tiap percobaan recognize mengirim base64 JPEG penuh lewat HTTP POST, uji juga di koneksi lambat/mobile untuk memastikan tidak ada request yang silently gagal.

### 4.5 Automated Testing (opsional, belum ada di project ini)

Project ini **belum memiliki test otomatis (PHPUnit) untuk fitur face recognition** — seluruh alur di atas saat ini hanya bisa diuji manual karena bergantung pada kamera fisik & proses eksternal Python. Jika ingin menambahkan test otomatis:

- Untuk unit test PHP, **mock** `App\Services\PythonRunner` (bukan menjalankan Python asli) agar test tidak bergantung pada environment Python/kamera — gunakan `PythonRunner` sebagai class yang bisa di-fake/dependency-inject, atau bungkus pemanggilannya di belakang interface agar mudah di-mock di `TugasController`/`FaceRecognitionController` test.
- Untuk test script Python sendiri, bisa dibuat unit test Python (`pytest`) terpisah yang menjalankan `recognize.py`/`train.py` terhadap dataset foto contoh yang disiapkan khusus untuk testing (bukan data privasi user asli).
