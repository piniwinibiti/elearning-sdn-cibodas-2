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
