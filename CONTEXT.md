# E-Learning SDN Cibodas 2

Platform pembelajaran daring untuk Sekolah Dasar dengan tiga peran pengguna (Admin, Guru, Siswa), mencakup materi, tugas, ujian, jadwal pelajaran, dan presensi berbasis pengenalan wajah.

## Language

### Identitas & Peran

**User**:
Akun login di tabel `users`. Punya `role` (`admin` / `guru` / `siswa`) dan `username`, bukan email.
_Avoid_: Account, akun (saat merujuk baris `users` secara teknis)

**Siswa**:
Profil akademik seorang **User** ber-role `siswa`, di tabel `siswas`. Identitas akademiknya adalah **NIS**.
_Avoid_: Murid, pelajar, student

**Guru**:
Profil kepegawaian seorang **User** ber-role `guru`, di tabel `gurus`. Identitas kepegawaiannya adalah **NIP**.
_Avoid_: Pengajar, teacher

**NIP**:
Nomor Induk Pegawai — identitas kepegawaian **Guru**, di `gurus.nip` yang `unique`. **Hanya angka**, tanpa huruf, spasi, tanda hubung, atau simbol. Disimpan sebagai `string`, bukan bilangan: leading zero-nya bermakna (`0993485667` ≠ `993485667`), jadi jangan pernah di-cast ke integer.
_Avoid_: ID Pegawai, nomor pegawai, employee ID

**NIS**:
Nomor Induk Siswa — identitas akademik **Siswa**, di `siswas.nis` yang `unique`. Aturannya sama dengan **NIP**: hanya angka, disimpan sebagai `string`, leading zero bermakna.
_Avoid_: NISN (itu nomor nasional yang berbeda), nomor absen, student ID

**Username**:
Kredensial login. Berisi **NIP** untuk Guru, **NIS** untuk Siswa, atau literal `admin`. Bukan alamat email. Karena Username diturunkan langsung dari NIP/NIS, keunikan harus dijaga di *dua* tempat: `gurus.nip`/`siswas.nis` dan `users.username`.

**Wali Kelas**:
Guru yang memegang satu kelas secara penuh, ditandai oleh `gurus.id_kelas_wali` yang terisi. Berbeda dari **Guru Bidang** yang hanya mengajar mapel tertentu di banyak kelas.

### Struktur Sekolah

**Kelas**:
Rombongan belajar (mis. `1A`, `6B`). Master data di tabel `kelas`, kolom identitasnya `nama_kelas` yang `unique`.
_Avoid_: Rombel, grade, classroom

**Mapel**:
Mata pelajaran. Master data di tabel `mapels` dengan `kode` dan `nama_mapel`, keduanya `unique`.
_Avoid_: Subject, pelajaran (saat merujuk master data)

**Jadwal**:
Satu slot pelajaran: kombinasi **Kelas** + **Mapel** + **Guru** + `hari` + rentang `jam_mulai`–`jam_selesai`. Menjadi penentu apakah **Presensi Mandiri** boleh dilakukan pada suatu saat.
_Avoid_: Timetable, schedule slot

### Presensi

**Absensi**:
Catatan kehadiran satu **Siswa** untuk satu **Mapel** pada satu `tanggal`. Kuncinya majemuk — bukan satu baris per hari, tapi satu baris per mapel per hari.
_Avoid_: Kehadiran, presence

**Status Kehadiran**:
Nilai `absensis.status`. Lima kemungkinan: **Hadir**, **Terlambat**, **Izin**, **Sakit**, **Alpha**. Hadir dan Terlambat sama-sama dihitung sebagai kehadiran dalam persentase; Izin dan Sakit adalah ketidakhadiran berketerangan; Alpha adalah ketidakhadiran tanpa keterangan — juga nilai default yang ditampilkan form guru untuk siswa yang belum ditandai.
_Avoid_: Bolos, absen (sebagai status)

**Presensi Mandiri**:
Alur di mana **Siswa** memindai wajahnya sendiri untuk mencatat **Absensi**. Hanya sah bila ada **Jadwal** yang aktif pada saat itu dan siswa belum absen di mapel tersebut hari itu.
_Avoid_: Absen sendiri, self check-in

**Scanner Guru**:
Alur di mana **Guru** memindai wajah siswa-siswa dari perangkatnya untuk mencatat **Absensi** massal per kelas. Berjalan paralel dengan **Presensi Mandiri**, bukan penggantinya.

### Penilaian

**Tugas**:
Pekerjaan yang dibuat **Guru** untuk satu **Kelas**, punya `deadline` dan lampiran opsional.

**Jawaban Tugas**:
Submission satu **Siswa** atas satu **Tugas**. Dinilai manual oleh Guru lewat kolom `nilai`.
_Avoid_: Submission, pengumpulan

**Ujian**:
Asesmen bertipe `ganda` (pilihan ganda, dinilai otomatis) atau `essay` (dinilai manual). Wajib melewati **Verifikasi Wajah** sebelum siswa boleh submit.

**Soal Ujian**:
Butir pertanyaan milik **Ujian** bertipe `ganda`, dengan empat opsi (`opsi_a`–`opsi_d`) dan satu `jawaban_benar` (`A`–`D`).

**KKM**:
Kriteria Ketuntasan Minimal — ambang nilai rata-rata yang dipakai **Kenaikan Kelas** untuk merekomendasikan seorang siswa naik. Default `75`.

**Kenaikan Kelas**:
Proses massal di akhir tahun ajaran yang memindahkan sekumpulan **Siswa** ke **Kelas** tujuan.

### Pengenalan Wajah

**Dataset Wajah**:
Kumpulan foto latih di `storage/app/public/dataset/`, dinamai `User.[USER_ID].[SAMPLE].jpg`. Satu **User** menyumbang 20 sample.

**Trainer**:
Model LBPH terlatih (`trainer.yml`) hasil `train.py`. Tidak disimpan di git karena berisi data biometrik.

**Confidence**:
Skor kemiripan `0`–`99.9` yang dikembalikan `recognize.py`, dihitung sebagai `100 - distance`. Ambang penerimaan berbeda per titik pakai.
_Avoid_: Akurasi, similarity, tingkat kecocokan (di kode; boleh di UI)

**Verifikasi Wajah**:
Pemeriksaan bahwa wajah yang terpindai cocok dengan **User** yang sedang login. Wajib sebelum submit **Ujian**. Berbeda dari **Face Login**, yang mengenali user tanpa ada sesi login sebelumnya.

## Flagged ambiguities

**`id_kelas` bukan foreign key.**
Meski namanya berawalan `id_`, kolom `id_kelas` di tabel `siswas`, `gurus.id_kelas_wali`, `absensis`, `materis`, `tugas`, `ujians`, dan `jadwals` bertipe `string` dan berisi **`nama_kelas`** (mis. `"1A"`) — *bukan* `kelas.id`. Konsekuensi: rule validasi yang benar adalah `exists:kelas,nama_kelas`, dan `exists:kelas,id` akan salah. Skema ini dipertahankan apa adanya; penamaannya tercatat sebagai tech-debt.

**`mata_pelajaran` vs `nama_mapel` — satu konsep, dua nama kolom.**
Tabel `absensis`, `materis`, `tugas`, dan `ujians` memakai kolom `mata_pelajaran`; tabel `jadwals` dan `guru_mapels` memakai `nama_mapel`. Keduanya berisi hal yang sama, yaitu `mapels.nama_mapel` sebagai string. Rule validasi keduanya `exists:mapels,nama_mapel`. Istilah kanonik dalam pembicaraan tetap **Mapel**.

**Enum `absensis.status` di database belum mencakup seluruh Status Kehadiran.**
Kolomnya didefinisikan `enum('status', ['hadir', 'terlambat', 'alpha'])` — tanpa `izin` dan `sakit`. Padahal form absensi guru menyediakan tombol Izin dan Sakit, dan `SiswaAkademikController` sudah menghitung statistik kelimanya. Konsekuensinya menandai siswa Izin atau Sakit gagal disimpan. Perbaikannya menunggu keputusan; lihat `docs/analysis/2026-07-30-validasi-form-05-absensi-auth-face.md` §4.1. Istilah kanoniknya tetap kelima nilai di **Status Kehadiran** — database yang perlu menyusul, bukan sebaliknya.

**`nilai` dipakai di dua tabel berbeda.**
`jawaban_tugas.nilai` adalah nilai tugas; `jawaban_ujian_essays.nilai` adalah nilai ujian essay. Keduanya berskala `0`–`100`. **Kenaikan Kelas** merata-ratakan keduanya tanpa pembobotan.

## Example dialogue

> **Dev:** Kalau siswa scan wajah jam 9 pagi tapi nggak ada pelajaran, absennya masuk ke mana?
>
> **Domain expert:** Nggak masuk ke mana-mana. **Presensi Mandiri** cuma sah kalau ada **Jadwal** yang aktif jam itu untuk kelasnya. Nggak ada jadwal, nggak ada absen.
>
> **Dev:** Jadi satu siswa bisa punya beberapa **Absensi** dalam satu hari?
>
> **Domain expert:** Betul, satu per **Mapel**. Kalau hari Senin dia ada Matematika dan IPA, ya dua baris. Bukan satu baris "hadir hari ini".
>
> **Dev:** Kalau dua **Jadwal** kebetulan jamnya beririsan di kelas yang sama?
>
> **Domain expert:** Itu nggak boleh terjadi. Satu kelas nggak mungkin belajar dua mapel sekaligus, dan satu **Guru** juga nggak bisa ngajar di dua tempat barengan — di sini nggak ada team teaching. Sistem harus menolaknya waktu admin nyimpan jadwal.
>
> **Dev:** Waktu ngisi **Jadwal**, `id_kelas` itu saya isi angka ID-nya atau nama kelasnya?
>
> **Domain expert:** Nama kelasnya, `"1A"`. Namanya memang menyesatkan — anggap saja itu kolom nama, bukan ID.
