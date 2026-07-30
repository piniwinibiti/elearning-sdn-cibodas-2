# Conventions Default — Personal Baseline (Polyglot)

> Standar pribadi default yang ditawarkan `/system-analis` saat **greenfield** (repo kosong).
> Saat dipakai, salin ke `docs/conventions.md` proyek lalu sesuaikan dengan stack target.
> Edit file ini kapan saja agar mencerminkan gaya kodingmu yang sebenarnya.
>
> **Bentuk dokumen:** satu **inti netral-bahasa** (prinsip + 9 keputusan) + **3 lampiran klaster**
> (OOP-container / wiring-eksplisit / komponen-frontend). Stack didukung: Laravel, Nest,
> Angular, Go, FastAPI, Next, Nuxt.

---

## 0. North-Star: Traceability

**Satu klik = satu tujuan nyata.** Dari UI bisa ditrace ke logic, dari logic balik ke UI,
tanpa "muter-muter". Setiap aturan di bawah melayani prinsip ini — kalau sebuah aturan
membuat `ctrl+click` mendarat di tempat kosong/ambigu, aturan itu salah.

Biang "muter-muter" yang dilarang di seluruh dokumen ini:
- Indirection tanpa manfaat nyata (interface satu-implementasi).
- Binding/wiring yang **tersebar & tersembunyi** (anotasi di mana-mana, auto-discovery, config string).
- Penghubung berbasis **string**, bukan panggilan fungsi (dispatch-by-string, event bus).

Prinsip pendukung:
- **Reuse sebelum bikin baru**; bila baru, patuhi pola di dokumen ini.
- **Deep module**: interface kecil, fungsionalitas kaya, jarang berubah. Interface dangkal
  (satu impl) dilarang — itu sumber muter-muter.
- **Hindari over-engineering** — struktur secukupnya untuk skala fitur.

---

## 1. Arsitektur — Peran, Bukan Nama

Yang konsisten lintas bahasa adalah **peran**, bukan kata "Controller/Service". Inti berpikir
dalam 4 peran; tiap bahasa memetakan ke idiomnya (lihat Lampiran).

| Peran | Tanggung jawab |
|---|---|
| **Entry-point** | Terima request, validasi boundary, panggil orchestration. Tipis. |
| **Orchestration** | Logika alur / use-case. Tidak query data langsung. |
| **Data-access** | Ambil/simpan data. Satu-satunya yang menyentuh storage. |
| **Domain** | Aturan bisnis & bentuk data. Inti yang jarang berubah. |

Dependency **mengarah ke dalam**: Entry → Orchestration → Data-access → Domain. Tidak ada arah balik.

---

## 2. Interface — Konkret Default (Aturan A)

- **Default: class/tipe konkret.** `ctrl+click` harus mendarat langsung di kode nyata.
- **Interface lahir hanya saat ada ≥2 implementasi nyata** — refactor saat itu juga, **bukan**
  diantisipasi "untuk jaga-jaga".
- Interface satu-implementasi = **dilarang** (shallow module, biang muter-muter).

> Catatan: ini sengaja membuang aturan lama "Repository selalu di balik interface" — itu melawan
> traceability.

---

## 3. Binding / DI — Terkumpul Satu File (Aturan B)

- Container DI **boleh** (kalau framework menyediakannya), TAPI **semua binding
  `interface → impl konkret` wajib terkumpul di satu file yang bisa di-`ctrl+click`.**
- **Dilarang** binding tersebar di anotasi / auto-discovery / config string.
- Dengan Aturan A, mayoritas objek konkret → `ctrl+click` mendarat langsung (0 hop).
  Sisa kasus interface-sejati → **1 hop deterministik** ke file binding. Tidak pernah muter.
- Bahasa tanpa container idiomatik (Go, FastAPI) mewujudkan ini sebagai **composition root manual**
  (`new`/wiring eksplisit di satu tempat). Prinsip sama, mekanisme beda.

---

## 4. Error Handling

### 4a. Propagasi — error naik, jangan mati di tempat
- Error **menggelembung naik** ke pemanggil sambil **menumpuk konteks** tiap lapis. Entry-point
  (Controller) harus tahu kalau lapisan dalam gagal — bukan proses berhenti diam-diam di dalam.
- **Penanganan final hanya di satu lapisan jelas** (boundary/entry-point), bukan di-catch
  sembarangan di tengah logic.
- Jejak akhir berbentuk rantai: `checkout gagal: charge gagal: Midtrans timeout ke api.midtrans.com`.

### 4b. Konten — anti pesan generic
- **Log / developer-facing**: se-spesifik mungkin — **titik X + sebab Y** + konteks (id, target).
  Selalu lengkap.
- **User-facing**:
  - Error **actionable** (kartu ditolak, saldo kurang, OTP salah) → **wajib pesan spesifik & jelas**.
    "Terjadi kesalahan" generik **dilarang** di sini.
  - Error **internal/teknis** (timeout, bug, DB down) → pesan generic-aman + **Error ID**.
- **Error ID**: UUID **acak** (bukan auto-increment), **kunci-only** (tak mengandung data sensitif),
  dipetakan ke detail di **log server-side**. Aman dibocorkan ke user untuk pelacakan support.

---

## 5. Naming — Tunduk Linter Resmi

- **Ikuti gaya idiomatik bahasa, ditegakkan formatter standarnya.** Jangan lawan tooling —
  naming non-idiomatik merusak navigasi IDE (= merusak traceability).
  - Go → `gofmt`/`golangci-lint` · Python → `ruff`/`black` · TS → ESLint+Prettier · PHP → Pint.
  - Khusus Go: huruf besar/kecil = **semantik visibility** (public/private), bukan estetika.
- **Dua prinsip naming netral-bahasa yang dipertahankan** (melayani traceability):
  1. **Interface dinamai peran, impl konkret menyebut detail** — `PaymentGateway` ← `MidtransPaymentGateway`.
     Nama langsung memberi tahu "yang mana yang nyata". (Go: idiom `-er`, mis. `io.Reader`.)
  2. **Satu unit = satu tanggung jawab, namanya menebak isinya** — bisa menebak lokasi tanpa muter.
     (PHP/TS: satu type per file. Go/Python: satu file/package fokus jelas.)

---

## 6. Data

- Skema lewat **migration berversi**.
- **Di DB**: tabel plural `snake_case`, kolom `snake_case`. FK eksplisit, relasi terdokumentasi di ERD.
- **Di kode**: ikut konvensi ORM (Eloquent auto-plural, Prisma model PascalCase, dll) — nama di DB
  tetap `snake_case`.

---

## 7. Testing

- **Unit test** untuk Orchestration & aturan domain — **uji perilaku, bukan detail implementasi**.
- **Data-access** diuji lewat **integrasi**.
- **Satu test = satu perilaku.**
- Mekanisme idiomatik per stack ada di Lampiran (table-driven Go, pytest, Vitest/Jest, PHPUnit).

---

# Lampiran — Pemetaan Per Klaster

Tiga klaster mewujudkan keputusan inti dengan mekanisme berbeda.

## A. OOP + Container — Laravel · Nest · Angular(service)

| Peran | Laravel | Nest | Angular |
|---|---|---|---|
| Entry-point | Controller | Controller | (route) Component / Resolver |
| Orchestration | Service | Service `@Injectable` | Service `@Injectable` |
| Data-access | Repository | Repository/Provider | Service data |
| Domain | Model/Entity | Entity/Class | Model/Interface |

- **Binding (Aturan B)**: Laravel → satu `ServiceProvider` (`$this->app->bind(Iface::class, Impl::class)`).
  Nest → `providers` di `@Module`. Angular → `providers` di module. **Tidak boleh** tersebar.
- **Error**: exception spesifik per domain. **Jangan di-catch & ditelan di tengah** — biarkan
  menggelembung ke exception handler boundary. Perlu konteks → catch, bungkus exception baru
  (`previous`/cause), rethrow.
- **Naming**: PascalCase class, camelCase method/var, UPPER_SNAKE const. File: PSR-4 (Laravel),
  kebab-case (Nest/Angular).
- **Test**: PHPUnit / Jest.

## B. Wiring Eksplisit — Go · FastAPI/Python

| Peran | Go | FastAPI |
|---|---|---|
| Entry-point | `handler` | `router` (path operation) |
| Orchestration | service package | service function |
| Data-access | repo (interface implicit) | repo / module |
| Domain | `struct` | Pydantic model |

- **Binding (Aturan B)**: tidak ada container — **composition root manual**. Go → rakit `new`/struct
  di `main.go` (atau `wire`). FastAPI → `Depends()` terpusat di `dependencies.py`. Satu tempat, bisa diklik.
- **Interface**: Go pakai **interface implicit** (duck typing) — definisikan di sisi konsumen, kecil
  (`-er`). Python → `Protocol`/`ABC` hanya saat ≥2 impl.
- **Error — error-as-value (justru paling traceable)**:
  - Go → `if err != nil { return fmt.Errorf("checkout: %w", err) }` di tiap lapis. `panic` hanya untuk
    crash benar-benar fatal, bukan flow.
  - Python/FastAPI → exception + handler terpusat; bungkus dengan konteks saat naik.
- **Naming**: Go → `gofmt` (exported PascalCase = public). Python → `snake_case` fungsi/var, PascalCase
  class, UPPER_SNAKE const (PEP8). File `snake_case`.
- **Test**: Go table-driven `_test.go` · pytest.

## C. Komponen Frontend — Next(React) · Nuxt(Vue) · Angular(template)

> **Status: PROVISIONAL — panduan lunak.** Tidak ada MVC; "logic" dan "view" campur di komponen.
> Perketat saat sudah ketemu kasus nyata sendiri.

- **Definisi traceability frontend**: setiap aksi UI bisa ditrace `komponen → handler → hook/composable
  → service API`, **tanpa state global gaib**. Perbatasan ke backend = **kontrak API terdokumentasi**,
  bukan `ctrl+click` (klik tak bisa nyebrang proses browser→server).
- **Default (lunak) anti muter-muter frontend**:
  - **Akses state lewat method bertipe yang bisa diklik**, bukan dispatch string.
    Mis. `cart.addItem(x)` (Pinia/Zustand/RTK typed) ✅ — bukan `dispatch('cart/addItem', x)` ❌.
  - **Panggilan API lewat satu modul service** yang di-`import` langsung (fungsi bertipe).
  - **Hindari event bus global** (`emit('x')` / pub-sub) — itu string-matching, calon muter.
- **Kapan boleh dilanggar**: butuh decoupling sungguhan (notif/toast global, plugin, websocket event)
  → pub-sub boleh, tapi **sadari itu titik "muter" dan dokumentasikan**.
- **Tradeoff yang diterima**: coupling lebih erat (komponen import store/service konkret) ditukar
  dengan kejelasan yang bisa diklik.
- **Angular** straddle: untuk service/DI pakai **Klaster A**; untuk komponen/template pakai klaster ini.
- **Naming**: PascalCase komponen/type, camelCase fungsi/var; file kebab-case (Angular/Nuxt) atau
  PascalCase (React component). Tunduk ESLint+Prettier.
