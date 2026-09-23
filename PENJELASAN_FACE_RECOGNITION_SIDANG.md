# Penjelasan Algoritma Face Recognition (Bahasa Awam) — Bekal Sidang Skripsi

> Ringkasan santai untuk menjelaskan cara kerja fitur pengenalan wajah di sistem ini ke penguji.

## 1. Gambaran Besar

Sistem ini **bukan** pakai face-api.js atau AI/deep learning modern (FaceNet, ArcFace, dsb). Arsitekturnya hybrid:

- **Laravel (PHP)** → urus tampilan, validasi, simpan file, dan keputusan bisnis (siswa ini boleh absen atau tidak, dsb).
- **Python + OpenCV** → yang benar-benar mengenali wajah, dipanggil dari Laravel seperti menjalankan program terpisah (bukan library yang menyatu).

**Alur singkat:** kamera di browser ambil foto → dikirim ke server → Laravel memanggil script Python → Python mengembalikan hasil "ini wajah siapa, seberapa mirip" → Laravel yang memutuskan langkah selanjutnya (login, catat absen, tolak, dll).

Kamera diakses murni pakai Web API bawaan browser (`getUserMedia`, `<canvas>`, `toDataURL`) — tidak ada library JS pihak ketiga untuk wajah sama sekali.

## 2. Algoritma — Ada 2 Tahap

### Tahap 1: Deteksi Wajah — "di foto ini, mana posisi wajahnya?"

Pakai **Haar Cascade Classifier**, algoritma klasik dari OpenCV (bukan deep learning).

- Sistem punya "pola/cetakan" khas wajah manusia (misal: area mata lebih gelap dari pipi, area hidung lebih terang), lalu pola ini digeser ke seluruh area foto untuk mencari bagian yang cocok.
- Kalau ada beberapa wajah terdeteksi dalam satu foto, sistem ambil **kotak yang paling besar** (dianggap wajah utama/paling dekat kamera).
- Bekerja di citra **grayscale** (hitam-putih), bukan warna.

Analoginya: seperti mencari wajah di foto ramai pakai "cetakan bentuk wajah" — bukan benar-benar *mengerti* wajah, tapi mengenali pola tekstur yang mirip wajah.

### Tahap 2: Pengenalan Wajah — "wajah ini punya siapa?"

Setelah wajah ketemu dan di-crop, dibandingkan pakai **LBPH (Local Binary Pattern Histogram)**.

Cara kerjanya secara awam:
1. Wajah dibagi jadi kotak-kotak kecil.
2. Tiap piksel dibandingkan dengan piksel tetangganya (lebih terang/lebih gelap) → menghasilkan "pola tekstur lokal".
3. Pola-pola ini dikumpulkan jadi histogram → ini yang jadi semacam **"sidik jari tekstur wajah"** orang tersebut.

Saat **registrasi**, sistem mengumpulkan sidik jari tekstur dari 20 foto per orang lalu "belajar" dari situ — hasilnya disimpan dalam satu file model bernama `trainer.yml`.

Saat ada yang mau **login/absen**, foto barunya dibuat sidik jari teksturnya juga, lalu **dibandingkan jaraknya** (Euclidean distance) dengan semua sidik jari yang sudah dipelajari. Makin kecil jaraknya = makin mirip.

## 3. Soal "Confidence" dan Threshold (bagian yang sering membingungkan)

Di OpenCV, nilai `confidence` itu sebenarnya adalah **jarak/selisih**, bukan skor kemiripan — jadi **makin kecil angkanya, makin mirip** (kebalikan dari makna kata "confidence" pada umumnya). Supaya masuk akal ditampilkan, dikonversi:

```
skor_persen = 100 - jarak
```

Contoh: jarak = 30 → hasil ditampilkan sebagai "70% mirip".

Ada dua lapis penyaringan:

| Lapisan | Lokasi | Aturan |
|---|---|---|
| 1 | Python (`recognize.py`) | Kalau jarak ≥ 80 → langsung dianggap "tidak ada yang cocok" |
| 2 | Laravel (Controller) | Skor hasil konversi harus **> 20%** baru dianggap valid |

> Catatan jujur untuk sidang: karena threshold 80 di Python otomatis membuat skor konversi selalu di atas 20%, threshold 20% di Laravel itu praktiknya cuma pengaman tambahan (redundant). Ini bisa diakui sebagai keterbatasan/celah untuk saran pengembangan (mis. dinaikkan ke >50% biar lebih ketat).

## 4. Alur Pemakaian di Sistem

1. **Registrasi wajah** — user difoto 20x berturut-turut (tiap ±150ms) lewat kamera browser. Foto disimpan sebagai file JPEG biasa. Begitu 20 foto lengkap, sistem otomatis **melatih ulang seluruh dataset** (bukan cuma menambah data orang itu — semua database wajah dilatih ulang dari nol tiap ada pendaftar baru).
2. **Login pakai wajah** — user ketik username dulu, baru ambil foto. Sistem cek: apakah wajah ini cocok dengan akun yang diklaim itu (verifikasi 1-lawan-1), bukan asal mencari "wajah ini mirip siapa saja di database" (1-lawan-banyak) — jadi lebih aman.
3. **Absensi mandiri siswa** — mirip login; wajah harus cocok dengan siswa yang sedang login.
4. **Scanner absensi oleh guru** — guru men-scan wajah siswa satu per satu untuk absen massal.
5. **Verifikasi sebelum ujian** — mencegah joki ujian; wajah harus cocok dengan user yang login.

## 5. Penyimpanan Data

Penting untuk ditekankan: **database (MySQL) sama sekali tidak menyimpan data wajah** (tidak ada kolom vector/embedding). Yang disimpan di database hanya path/lokasi file.

Data wajah aslinya disimpan sebagai **file di filesystem**:
- Foto mentah training → `storage/app/public/dataset/User.{ID}.{SAMPLE}.jpg`
- Model hasil training (representasi tekstur semua user) → `storage/app/public/trainer.yml` (satu file untuk semua user, format biner)
- Foto bukti absensi → `storage/app/public/absensi/*.jpg`, direferensikan lewat kolom `foto_bukti` (string path saja)

## 6. Guard Anti-Duplikat Wajah

Fitur tambahan (`FaceDuplicateGuard.php`) supaya **satu wajah fisik tidak bisa terdaftar di dua akun berbeda**.

- Saat ada yang mendaftarkan wajah baru, sistem diam-diam mengecek dulu: "wajah ini sudah dikenali sebagai orang lain belum di data yang ada?"
- Kalau kemiripannya **di atas 50%** dengan akun lain → pendaftaran ditolak dengan pesan "wajah ini sudah terdaftar atas nama X".
- Dibarengi perbaikan proses login: dulu sistem asal mencari "wajah ini mirip siapa di seluruh database" (rawan wajah mirip nyasar login ke akun orang lain), sekarang wajib cocok dengan akun yang diklaim saja.

## 7. Poin Penting untuk Dijelaskan ke Penguji

- Algoritma ini **klasik/machine learning tradisional** (Haar Cascade + LBPH), bukan deep learning — pilihan yang **ringan, tidak butuh GPU**, cocok untuk skala sekolah. Trade-off-nya: akurasi lebih rentan terhadap perubahan pencahayaan dan sudut wajah dibanding deep learning modern (FaceNet/ArcFace/dlib).
- Training bersifat **retrain-all** (bukan incremental) — tiap ada registrasi baru, seluruh folder `dataset/` dibaca ulang dan `trainer.yml` ditulis ulang total.
- Sistem membedakan **verifikasi (1:1)** untuk login/absen/ujian (wajah harus cocok akun yang diklaim) vs **identifikasi (1:N)** yang dihindari demi keamanan.
- Ada dokumen `FACE_RECOGNITION.md` di root project yang membahas ini lebih detail — bisa dibuka sekali lagi sebelum sidang untuk menyamakan istilah.

## 8. Antisipasi Pertanyaan Penguji

**Q: Kenapa tidak pakai deep learning/face-api.js?**
A: Dipilih LBPH karena ringan, tidak butuh GPU/server kuat, cukup akurat untuk skala penggunaan sekolah, dan implementasinya sederhana lewat OpenCV.

**Q: Data wajah disimpan di mana? Apakah aman?**
A: Tidak disimpan di database sebagai data mentah yang gampang dibaca — hanya file foto JPEG dan satu file model biner (`trainer.yml`) hasil training OpenCV di server, database hanya menyimpan path/referensi file.

**Q: Bagaimana kalau ada dua wajah mirip (misal saudara kembar)?**
A: Ada threshold kemiripan (`>20%` untuk match biasa, `>50%` untuk guard anti-duplikat) — kalau memang sangat mirip secara tekstur, sistem bisa saja salah kenali; ini keterbatasan LBPH yang diakui, dan bisa jadi saran pengembangan (menaikkan threshold atau upgrade ke algoritma deep learning).

**Q: Kenapa harus ketik username dulu sebelum face login?**
A: Supaya prosesnya jadi verifikasi 1-lawan-1 (memastikan wajah cocok dengan akun yang diklaim), bukan identifikasi 1-lawan-banyak yang lebih rawan salah kenali dan celah keamanan.

**Q: Apa itu "guard anti-duplikat wajah"?**
A: Mekanisme yang mencegah satu wajah fisik terdaftar di lebih dari satu akun — dicek otomatis setiap ada pendaftaran wajah baru, dengan membandingkan ke seluruh data wajah yang sudah ada di model.

---

## 9. Alur Algoritma Detail — Step by Step + Lokasi Kode

Ada 3 alur besar yang saling berkaitan. Kalau ditanya "coba jelasin alurnya dari awal sampai akhir", pakai 3 alur ini.

### A. Alur Registrasi/Pendaftaran Wajah

File: `app/Http/Controllers/FaceRecognitionController.php` → method `registerFaceDataset()` (baris 274–325)

```
1. Browser ambil 1 foto dari webcam (via getUserMedia + canvas)
2. Foto dikirim ke server sebagai base64, sekaligus nomor "sample_count" (1..20)
3. Request divalidasi oleh RegisterFaceDatasetRequest (cek format & ukuran gambar)
4. KALAU sample_count == 1 (baris 285-297):
     → panggil FaceDuplicateGuard::findConflict() untuk cek wajah ini sudah
       terdaftar sebagai user lain atau belum
     → kalau ketemu konflik (mirip >= 50%) -> tolak, proses berhenti di sini
5. Foto disimpan sebagai file: dataset/User.{userId}.{sample}.jpg   (baris 300-306)
6. KALAU sample_count >= 20 (baris 310-316):
     → otomatis jalankan train.py (retrain SELURUH dataset, bukan cuma user ini)
     → trainer.yml ditimpa dengan model yang baru
7. Ulangi langkah 1-6 sampai 20 sample terkumpul (dikontrol loop JS di frontend,
   lihat resources/views/siswa/dashboard.blade.php baris ~407-479)
```

### B. Alur Pengenalan Wajah (dipakai untuk: login, absensi mandiri, scan guru, verifikasi ujian)

Keempatnya memanggil `recognize.py` yang **sama persis** — bedanya cuma di aturan bisnis sesudahnya. File Python: `storage/app/public/recognize.py`.

```
1. Baca file model trainer.yml (baris 42-43) -> kalau belum ada, langsung gagal
2. Baca gambar yang dikirim, ubah ke grayscale (baris 48-55)
3. DETEKSI WAJAH pakai Haar Cascade: detectMultiScale(...)          (baris 60)
4. Kalau tidak ada wajah terdeteksi -> gagal, "Wajah tidak terdeteksi" (baris 62-69)
5. Kalau wajah > 1 -> ambil kotak (bounding box) paling besar           (baris 73-74)
6. Crop area wajah itu, masukkan ke LBPH recognizer.predict(...)     (baris 79)
   -> hasil: id_siswa (siapa) + confidence (sebenarnya JARAK, makin kecil makin mirip)
7. Kalau jarak < 80  -> konversi ke skor: 100 - jarak, dibatasi max 99.9%  (baris 81-93)
   Kalau jarak >= 80 -> dianggap TIDAK COCOK, confidence dipaksa 0        (baris 94-100)
8. Python cetak hasil JSON ke stdout: {success, confidence, user_id, message}
9. PHP (lewat PythonRunner::run, app/Services/PythonRunner.php) membaca
   stdout itu dan men-decode JSON-nya (baris 86 PythonRunner.php)
10. Controller yang manggil menerapkan ATURAN TAMBAHAN di atas hasil Python:
    - confidence harus > 20                                    (rule PHP, lapis ke-2)
    - DAN (kecuali di scanner guru) user_id hasil harus == user yang login  (rule 1:1)
11. Kalau lolos semua -> aksi sesuai konteks (login, catat absen, tandai verified)
    Kalau tidak -> ditolak dengan pesan generik
```

Titik panggil alur B ini ada di 4 tempat (tunjukkan salah satu saat demo, cukup 1-2):
| Konteks | File & Method | Baris |
|---|---|---|
| Login pakai wajah | `AuthController.php` → `faceLogin()` | 54-131 |
| Absensi mandiri siswa | `FaceRecognitionController.php` → `recognize()` | 18-122 |
| Scanner absensi guru | `FaceRecognitionController.php` → `processScanner()` | 148-223 |
| Verifikasi sebelum ujian | `FaceRecognitionController.php` → `verifySiswaAuth()` | 226-271 |

### C. Alur Training Model

File: `storage/app/public/train.py`, fungsi `train_model()` (baris 6-77)

```
1. Baca semua file foto di folder dataset/                         (baris 31)
2. Untuk setiap foto:
   a. Ambil user_id dari nama file "User.[ID].[SAMPLE].jpg"        (baris 52)
   b. Deteksi wajah di foto itu pakai Haar Cascade                 (baris 54)
   c. Crop area wajahnya, simpan ke daftar faceSamples[] + ids[]   (baris 56-59)
3. Latih model LBPH dari SELURUH faceSamples + ids sekaligus       (baris 67)
4. Simpan hasilnya ke trainer.yml, timpa file lama                 (baris 71)
```

Bisa dipicu dari 2 tempat: otomatis saat sample registrasi ke-20 (`registerFaceDataset()` baris 310-316), atau manual oleh admin (`FaceRecognitionController::trainAdmin()` baris 125-140, tombol training di dashboard admin).

---

## 10. Semua Parameter yang Dipakai (Tabel Lengkap)

| Parameter | Nilai | Fungsi | Lokasi |
|---|---|---|---|
| `scaleFactor` | 1.2 | Seberapa besar gambar diperkecil tiap tahap pencarian multi-skala wajah | `recognize.py:60`, `train.py:54` (default) |
| `minNeighbors` | 5 | Makin tinggi makin ketat/selektif, mengurangi deteksi wajah palsu (false positive) | `recognize.py:60` |
| `minSize` | (30, 30) px | Ukuran minimum kotak wajah yang dianggap valid | `recognize.py:60` |
| Threshold jarak LBPH (Python) | `< 80` | Batas mentah algoritma: jarak di bawah ini dianggap "match" | `recognize.py:81` |
| Threshold skor (PHP, match biasa) | `> 20 %` | Ambang kedua di level bisnis untuk login/absen/verifikasi | `FaceRecognitionController.php:90`, `AuthController.php:105`, dst |
| Threshold skor (PHP, guard anti-duplikat) | `>= 50 %` | Ambang lebih ketat khusus untuk mendeteksi wajah yang sama persis dengan user lain | `FaceDuplicateGuard.php:12` |
| Skor maksimum | `99.9 %` | Skor kemiripan dibatasi, tidak pernah dianggap 100% sempurna | `recognize.py:86` |
| Jumlah sample registrasi | 20 foto/orang | Banyaknya foto yang diambil saat enrollment sebelum auto-training | `RegisterFaceDatasetRequest.php:22`, `FaceRecognitionController.php:310` |
| Interval capture sample | ~150 ms | Jeda antar pengambilan foto otomatis saat registrasi | `dashboard.blade.php` (JS `setInterval`) |
| Kualitas JPEG hasil capture | 0.8 (80%) | Kompresi gambar dari canvas sebelum dikirim ke server | `dashboard.blade.php` (`toDataURL('image/jpeg', 0.8)`) |
| Ukuran maksimum gambar — registrasi | 2048 KB | Batas ukuran file setelah di-decode dari base64 | `RegisterFaceDatasetRequest.php:19` |
| Ukuran maksimum gambar — login/absen/verifikasi | 4096 KB | Batas ukuran untuk konteks lain | `FaceLoginRequest.php:22`, dst |
| MIME type gambar diizinkan | `image/jpeg`, `image/jpg`, `image/png` | Format file yang diterima | `Base64Image.php:13` |
| Timeout proses Python (recognize) | 30 detik | Batas waktu tunggu sebelum proses Python dipaksa berhenti | `PythonRunner.php:22` (`$timeout = 30`) |
| Timeout proses Python (training) | 60 detik | Training lebih berat, timeout lebih longgar | `PythonRunner.php:98` (`$timeout = 60`) |
| Warna citra | Grayscale (`COLOR_BGR2GRAY`) | Haar Cascade & LBPH hanya bekerja di hitam-putih | `recognize.py:55`, `train.py:47` |
| Format nama file dataset | `User.{id}.{sample}.jpg` | Nama file dipakai sebagai label saat training | `train.py:52`, `FaceRecognitionController.php:300` |

---

## 11. Rules yang Ada di Sistem

Kalau penguji tanya "ada berapa rules di sistem ini", jawabannya tergantung maksud "rules" — jelaskan ada **2 kategori**:

### a) Validation Rules (Laravel Form Request) — 5 buah

| # | Form Request | Rule Field | Lokasi |
|---|---|---|---|
| 1 | `RegisterFaceDatasetRequest` | `image` (Base64Image, max 2048 KB) + `sample_count` (integer 1-20) | `app/Http/Requests/RegisterFaceDatasetRequest.php:18-23` |
| 2 | `FaceLoginRequest` | `username` (required, string) + `image` (Base64Image, max 4096 KB) | `app/Http/Requests/FaceLoginRequest.php:18-23` |
| 3 | `RecognizeAbsensiRequest` | `image` (Base64Image, max 4096 KB) | `app/Http/Requests/RecognizeAbsensiRequest.php:15-19` |
| 4 | `ProcessScannerRequest` | `image` (Base64Image, max 4096 KB) | `app/Http/Requests/ProcessScannerRequest.php:15-19` |
| 5 | `VerifySiswaAuthRequest` | `image` (Base64Image, max 4096 KB) | `app/Http/Requests/VerifySiswaAuthRequest.php:15-19` |

Semuanya memakai 1 custom validation rule yang sama: **`Base64Image`** (`app/Rules/Base64Image.php`), yang di dalamnya melakukan **6 pengecekan berurutan**:
1. String tidak kosong (baris 18)
2. Mengandung format `;base64,` (baris 25)
3. MIME type termasuk yang diizinkan — jpeg/jpg/png (baris 34)
4. Base64 berhasil di-decode (strict mode) (baris 40)
5. Hasil decode valid sebagai gambar (`getimagesizefromstring`) (baris 47)
6. Ukuran file tidak melebihi batas KB yang ditentukan (baris 53)

### b) Business/Decision Rules (aturan penentu keputusan di algoritma) — 6 aturan inti

| # | Aturan | Nilai | Lokasi |
|---|---|---|---|
| 1 | Wajah dianggap match secara mentah kalau jarak LBPH < 80 | `confidence < 80` | `recognize.py:81` |
| 2 | Kalau wajah terdeteksi > 1 di satu foto, ambil kotak terbesar | `max(f[2]*f[3])` | `recognize.py:73` |
| 3 | Hasil pengenalan baru dianggap valid secara bisnis kalau skor > 20% | `confidence > 20` | 3 controller (`FaceRecognitionController.php:90,177,257`, `AuthController.php:105`) |
| 4 | Verifikasi identitas 1-lawan-1: `user_id` hasil harus sama dengan user yang login/diklaim (kecuali scanner guru, karena yang login guru bukan siswa) | `user_id == $user->id` | `FaceRecognitionController.php:91,258`, `AuthController.php:106` |
| 5 | Wajah dianggap "duplikat orang lain" kalau skor >= 50% dan `user_id` bukan diri sendiri | `confidence >= 50 && user_id != self` | `FaceDuplicateGuard.php:51` |
| 6 | Auto-training terpicu otomatis begitu sample registrasi mencapai 20 | `sample_count >= 20` | `FaceRecognitionController.php:310` |

---

## 12. Panduan Live Demo — Urutan Buka Kode Saat Sidang

Kalau diminta "coba tunjukkan di kodingannya", ini urutan file yang paling runtut untuk dibuka satu-satu:

1. **`resources/views/siswa/dashboard.blade.php`** — tunjukkan bagian JS `captureSamples()` / `getUserMedia`, jelaskan ini cuma ambil foto dari kamera, belum ada AI di sisi browser.
2. **`app/Http/Requests/RegisterFaceDatasetRequest.php`** + **`app/Rules/Base64Image.php`** — tunjukkan validasi gambar sebelum diproses.
3. **`app/Http/Controllers/FaceRecognitionController.php`** method `registerFaceDataset()` (baris 274) — tunjukkan penyimpanan file dataset + pemicu training otomatis di baris 310.
4. **`storage/app/public/train.py`** — tunjukkan `LBPHFaceRecognizer_create()` (baris 13) dan `recognizer.train(...)` (baris 67), ini titik inti "belajar".
5. **`storage/app/public/recognize.py`** — ini file paling penting untuk didemokan:
   - baris 42-43: load model
   - baris 60: deteksi wajah (Haar Cascade + parameter)
   - baris 79: prediksi LBPH (`recognizer.predict`)
   - baris 81-100: threshold & konversi skor
6. **`app/Services/PythonRunner.php`** method `run()` (baris 22) — tunjukkan bagaimana PHP memanggil Python via `proc_open` dan membaca hasil JSON-nya (baris 86).
7. **`app/Http/Controllers/FaceRecognitionController.php`** method `recognize()` (baris 18) atau **`AuthController.php`** method `faceLogin()` (baris 54) — tunjukkan aturan bisnis tambahan (`confidence > 20`, `user_id` harus cocok).
8. **`app/Services/FaceDuplicateGuard.php`** — kalau ditanya soal keamanan/duplikasi, ini bukti nyata upaya mencegah 1 wajah = 2 akun.

Tips: siapkan tab/file ini terbuka semua sebelum sidang dimulai, biar tinggal pindah tab pas ditanya, tidak perlu cari-cari lagi.
