# Coding Conventions — E-Learning SDN Cibodas 2

Standar ini **diturunkan dari kode yang sudah ada** (inferred), bukan diimpor dari luar. Dokumen ini dibuat saat analisis "Validasi Form Menyeluruh" (2026-07-30) karena pekerjaan itu memperkenalkan satu pola baru (FormRequest) yang perlu dicatat agar konsisten ke depan.

Ketika dokumen ini bertentangan dengan kode lama, **dokumen ini yang menang** — kode lama dianggap tech-debt, tapi *jangan* dirapikan di luar cakupan task yang sedang dikerjakan.

---

## 1. Stack & Arsitektur

- **Laravel 12**, PHP 8.2+, MVC standar Laravel. Bukan SPA, bukan hexagonal, tanpa service layer formal.
- Render via **Blade**. Tidak ada React/Vue. Interaksi dinamis pakai vanilla JS + `axios`.
- **Tailwind CSS v4** via plugin Vite (`@tailwindcss/vite`) — **tidak ada `tailwind.config.js`**.
- Komponen UI: **Flowbite** (dimuat via CDN di `resources/views/layouts/app.blade.php`, bukan dari npm bundle).
- **SweetAlert2** via CDN, dipakai untuk konfirmasi hapus (`.form-delete`).
- Semua route di `routes/web.php`. **Tidak ada `api.php` aktif** — endpoint AJAX pun tinggal di `web.php` dengan middleware `auth`.

## 2. Struktur Direktori

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Sub-namespace controller khusus admin
│   │   └── *.php           # Controller lintas-role di root
│   ├── Middleware/RoleMiddleware.php
│   └── Requests/           # FormRequest — lihat §4
├── Models/                 # Eloquent, flat (tanpa sub-folder)
├── Rules/                  # Custom validation rule — lihat §4.4
└── Services/               # Wrapper proses eksternal (PythonRunner)
resources/views/
├── layouts/app.blade.php   # Layout tunggal untuk semua halaman ter-autentikasi
├── components/             # Komponen Blade anonim
├── auth/ admin/ guru/ siswa/
lang/id/                    # Terjemahan validasi Bahasa Indonesia
```

## 3. Penamaan

| Elemen | Konvensi | Contoh nyata |
|---|---|---|
| Controller | `PascalCase` + suffix `Controller` | `GuruAbsensiController` |
| Model | `PascalCase`, singular | `Materi`, `JawabanTugas` |
| Tabel | `snake_case` plural | `siswas`, `jawaban_ujian_gandas` |
| Kolom | `snake_case` | `nama_lengkap`, `jam_mulai` |
| Route name | dot-notation ber-prefix role | `admin.jadwal.store`, `guru.tugas.grade` |
| View | folder per role, `snake_case` file | `guru/ujian/jawaban_essay.blade.php` |
| FormRequest | `<Aksi><Entitas>Request` | `StoreJadwalRequest` |
| Custom Rule | `PascalCase`, deskriptif | `NoJadwalConflict` |

**Bahasa:** nama domain pakai **Bahasa Indonesia** (`Materi`, `nama_mapel`, `jam_mulai`). Kata kerja teknis pakai **English** (`store`, `update`, `bulkDestroy`, `index`). Jangan campur dalam satu identifier.

Istilah domain yang benar ada di [`CONTEXT.md`](../CONTEXT.md) — **baca itu sebelum menamai apa pun**.

## 4. Validasi

### 4.1 FormRequest adalah tempat kanonik validasi

Sejak 2026-07-30, validasi **tidak lagi ditulis inline** sebagai `$request->validate([...])` di controller. Setiap endpoint yang menerima input menaruh rules-nya di FormRequest sendiri di `app/Http/Requests/`.

```php
// ✗ Pola lama — jangan tambah yang baru seperti ini
public function store(Request $request)
{
    $request->validate(['judul' => 'required|string|max:255']);
}

// ✓ Pola yang benar
public function store(StoreMateriRequest $request)
{
    $data = $request->validated();
}
```

Alasan: rules store & update terduplikasi di mana-mana pada pola lama, dan pesan error kustom Bahasa Indonesia tidak punya rumah yang jelas.

### 4.2 Anatomi FormRequest

```php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMateriRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Cek KEPEMILIKAN, bukan cuma role — role sudah dijaga RoleMiddleware.
        return true;
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return ['judul' => 'judul materi'];
    }

    public function messages(): array
    {
        // Hanya untuk pesan yang butuh konteks domain.
        // Pesan generik sudah ditangani lang/id/validation.php.
        return [];
    }
}
```

- Rules ditulis sebagai **array**, bukan string pipe (`['required', 'string']` bukan `'required|string'`). Lebih mudah dibaca dan disisipi `Rule::` object.
- `authorize()` memeriksa **kepemilikan** resource. `RoleMiddleware` sudah memastikan perannya; FormRequest memastikan *dia pemiliknya*.
- `attributes()` memberi nama field yang manusiawi dalam Bahasa Indonesia.
- Rules yang dibagi antara Store & Update diekstrak ke method `protected function baseRules(): array` di parent request, atau di-`array_merge`.

### 4.3 Referensi master data adalah string, bukan ID

**Ini jebakan paling berbahaya di project ini.** Kolom `id_kelas` bertipe `string` dan berisi `nama_kelas` (mis. `"1A"`) — **bukan** `kelas.id`. Sama untuk `mata_pelajaran` / `nama_mapel` yang berisi `mapels.nama_mapel`.

```php
// ✗ SALAH — akan menolak semua input yang sah
'id_kelas' => ['required', 'exists:kelas,id'],

// ✓ BENAR
'id_kelas'       => ['required', 'string', 'exists:kelas,nama_kelas'],
'mata_pelajaran' => ['required', 'string', 'exists:mapels,nama_mapel'],
'nama_mapel'     => ['required', 'string', 'exists:mapels,nama_mapel'],
```

Skema ini **dipertahankan apa adanya**. Jangan mengubahnya jadi foreign key.

### 4.4 Custom Rule

Logika validasi yang butuh query atau parsing non-trivial masuk ke `app/Rules/` sebagai class yang meng-implement `ValidationRule`:

```php
namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Base64Image implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! str_contains($value, ';base64,')) {
            $fail('Format gambar tidak valid.');
        }
    }
}
```

### 4.5 File upload divalidasi ganda

`mimes:` hanya memeriksa ekstensi — bisa dipalsukan. Selalu sertakan `mimetypes:` yang memeriksa isi file:

```php
'file_materi' => [
    'required', 'file', 'max:20480',
    'mimes:pdf,mp4,mkv',
    'mimetypes:application/pdf,video/mp4,video/x-matroska',
],
```

### 4.6 Respons validasi

- **Form biasa (Blade)** → biarkan Laravel redirect back otomatis dengan `$errors` + `withInput()`. Jangan tangkap manual.
- **Endpoint AJAX** → FormRequest otomatis membalas **422 JSON** bila request-nya `Accept: application/json` atau `X-Requested-With: XMLHttpRequest`. Pastikan sisi JS mengirim header itu.

## 5. Tampilan Error di Blade

- Error per-field **wajib** pakai komponen `<x-input-error :messages="$errors->get('nama_field')" />`. Jangan tulis `@error` mentah berulang-ulang.
- Input **wajib** repopulate dengan `old()`: `value="{{ old('judul', $materi->judul ?? '') }}"`.
- Blok ringkasan error & flash message ada di `layouts/app.blade.php` secara global — **jangan duplikasi** blok `@if($errors->any())` di tiap view.
- Form di dalam Flowbite modal menandai dirinya lewat flash key agar modal terbuka ulang saat validasi gagal (lihat dokumen analisis Fondasi).

## 6. Controller

- Controller **tipis**: terima FormRequest, panggil Eloquent, redirect. Tanpa logika validasi.
- **Jangan** `Model::create($request->all())` — selalu `$request->validated()` atau array eksplisit. Pola `$request->all()` adalah lubang mass-assignment.
- Selalu redirect dengan flash: `->with('success', '...')` / `->with('error', '...')`. Pesan flash dalam **Bahasa Indonesia**.
- Query yang sudah menyaring kepemilikan (`Ujian::where('guru_id', ...)->findOrFail($id)`) tetap dipertahankan meski `authorize()` sudah memeriksa — dua lapis tidak merugikan.

## 7. Otorisasi

- **Role** dijaga `RoleMiddleware` di level route (`role:admin`, `role:guru`, `role:siswa`). Bukan Gate/Policy.
- **Kepemilikan** dijaga `authorize()` di FormRequest. Ini yang mencegah guru A mengubah data guru B.
- Tidak memakai Spatie Permission. Jangan menambahnya.

## 8. Format Kode

- **PSR-12** via **Laravel Pint** (`vendor/bin/pint`). Jalankan sebelum commit.
- `.editorconfig` yang ada adalah otoritas untuk indentasi & line ending.
- Import class di blok `use` di atas — **jangan** pakai FQCN inline seperti `\App\Models\GuruMapel::create(...)` di badan method (pola ini ada di kode lama; jangan ditiru).

## 9. Bahasa Pesan ke Pengguna

Semua yang dibaca pengguna akhir — pesan validasi, flash message, label form, teks tombol — dalam **Bahasa Indonesia**. `APP_LOCALE=id`.

Komentar kode dan nama identifier teknis boleh English.
