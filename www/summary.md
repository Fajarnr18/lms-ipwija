# Summary Percakapan

## 1. Perbaikan Tema Dashboard (Putih Bersih)
- Sidebar dari **dark gradient** → **putih** dengan border tipis
- Body background dari `#f1f5f9` → **putih** (`#fff`)
- Semua card, stat card, tool card: border `#f1f5f9`, tanpa shadow
- Mobile menu dari hitam → putih
- Hover/active sidebar link: indigo gradient untuk active, light gray untuk hover

## 2. Fitur Halaman Login
- **Remember Me checkbox**: input `name="remember"` + styling
- **Loading spinner**: animasi muter di tombol "Masuk" (gak gelap-gelapan)
- **Alert sukses registrasi**: notif hijau "Registrasi berhasil! Silakan login."

## 3. Perbaikan Route Binding (Error 500)
- **Route parameter** `{borrowing}` → `{borowing}` (satu 'r') biar cocok sama controller variable `$borowing`
- **Model `Borowing`**: nambah `getRouteKeyName()` return `id_borrowing`

## 4. Alur Registrasi
- Setelah registrasi, **gak auto-login lagi**
- Redirect ke halaman **login** dengan pesan sukses

## 5. Database Migration
- Tambah kolom `remember_token` ke tabel `users` (biar Remember Me gak error)
- Migrasi dijalankan via `docker exec laravel_app php artisan migrate`

## 6. Fix Error "headers already sent"
- `public/index.php` ada kata **"bagi"** sebelum `<?php` — udah dihapus
# Summary Perbaikan Halaman Login

## Perubahan yang Dilakukan

### 1. Brand Content — Posisi & Alignment
- **File:** `resources/views/auth/login.blade.php`
- **CSS `.brand-side`:** `align-items: center; justify-content: center` → `align-items: flex-start; justify-content: flex-start` dengan `padding-top: 60px`
  - Konten brand (logo, judul, tagline, deskripsi) naik ke atas
  - Copyright tetap di bawah via `position: absolute; bottom: 24px`
- **CSS `.brand-content`:** Tambah `margin: 0 auto` → center horizontal sejajar dengan copyright
- **CSS `.copyright`:** Tambah `left: 0; right: 0; text-align: center` → copyright center

### 2. Ikon Input — Ukuran
- Semua ikon SVG input dari `18px` → **`14px`** (user, gembok, mata, eye-off, panah tombol)
- **CSS `.input-group .input-wrap svg`:** `width: 18px; height: 18px` → `width: 14px; height: 14px`
- Semua inline SVG attributes: `width="18" height="18"` → `width="14" height="14"`

### 3. Visual Box Input — Restruktur Border
- **CSS `.input-wrap`:** Tambah `border: 1.5px solid #E5E7EB; border-radius: 10px; background: #fff`
- **CSS `input`:** Hapus `border`, `border-radius`, `background` → `border: none; border-radius: 0; background: transparent`
- **CSS fokus:** Pindah ke `.input-wrap:focus-within` biar border biru melingkupi wrapper
- **CSS `input:focus`:** Cuma `outline: none`

### 4. Ikon Mata / Toggle Password
- **CSS `.toggle-pw`:** Tambah `display: flex; align-items: center; justify-content: center`
- **Posisi:** `right: 12px` → `14px` → `4px` → `8px` → **`20px`** (final, aman dari border-radius)
- **Padding:** `padding: 4px` → `2px` → **`2px`** (final)

### 5. Padding Input Password
- **CSS `.pw-input`:** `padding-right: 44px` → `36px` → `28px` → `32px` → `40px` → **`48px`** (final)
  - Agar teks password tidak bertumpuk dengan ikon mata

### 6. Tombol Masuk
- Tambah **ikon panah masuk** (SVG login/arrow) ukuran `14px`
- CSS `.btn-icon` dengan `flex-shrink: 0`
- Saat loading, `.btn-icon` ikut tersembunyi seperti `.btn-text`

### 7. Ikon Lainnya
- **Ikon mata (show):** SVG ukuran `14px` dengan path mata standar
- **Ikon mata tertutup (hide):** SVG ukuran `14px` dengan path mata + garis silang
- **Ikon gembok (password):** SVG ukuran `14px`
- **Ikon user (email):** SVG ukuran `14px`

## Status Fitur Login

| Fitur | Status |
|-------|--------|
| Logo + brand teks center horizontal + di atas | ✅ |
| Copyright center horizontal + di bawah | ✅ |
| Ikon input (user, gembok) 14px | ✅ |
| Ikon mata toggle rapi di dalam kotak | ✅ |
| Password input padding cukup | ✅ |
| Tombol "Masuk" + ikon panah 14px | ✅ |
| Focus biru melingkupi wrapper | ✅ |
| Role switch Admin/Mahasiswa | ✅ |
| Test credentials dinamis | ✅ |
| Alert success/error | ✅ |
| Lupa password + Ingat saya | ✅ |
| Register link | ✅ |

## Perubahan Terbaru Dashboard & Auth

### 1. Role Auto-Detect Login
- Input `@` → cari di `email` → login sebagai **Admin**
- Input 16 digit → cari di `nuptk` → login sebagai **Dosen**
- Input 12 digit → cari di `nim` → login sebagai **Mahasiswa**
- **Redirect:** Admin → `admin.dashboard`, Dosen → `dosen.dashboard`, Mahasiswa → `mhs.dashboard`
- **Fix:** `redirect()->intended()` → `redirect()->route()` (hindari 403 karena session lama)

### 2. Role Auto-Detect Register
- Input 12 digit → simpan di `nim`, role = **mahasiswa**
- Input 16 digit → simpan di `nuptk`, role = **dosen**
- Validasi: `regex:/^([0-9]{12}|[0-9]{16})$/` (pake array syntax biar `|` nggak error)
- Uniqueness dicek di kolom `nim` **dan** `nuptk`

### 3. Dashboard Dosen
| # | Perubahan | Detail |
|---|-----------|--------|
| 1 | **Controller baru** | `app/Http/Controllers/Dosen/DashboardController.php` |
| 2 | **View baru** | `resources/views/dosen/dashboard/index.blade.php` (copy dari mahasiswa) |
| 3 | **Gate baru** | `dosen-access` → `role === 'dosen' && is_active` |
| 4 | **Route baru** | `/dosen/dashboard` → name: `dosen.dashboard` |
| 5 | **Gate mahasiswa** | Kembali ke `role === 'mahasiswa'` aja |
| 6 | **Root redirect** | Dosen → `dosen.dashboard` |
| 7 | **API redirect** | Dosen → `/dashboard/dosen` |

### 4. Database Migration
- **`2026_06_08_000001_add_nuptk_and_update_role_to_users_table.php`**
- Kolom baru: `nuptk` (string 18, unique, nullable)
- Role ENUM: `['admin', 'mahasiswa']` → `['admin', 'dosen', 'mahasiswa']`
- Model `User.php` → `nuptk` ditambah ke `#[Fillable]`

### 5. Regex Validation Fix
- **Masalah:** Pipe `|` di regex dikira Laravel sebagai pemisah rule
- **Solusi:** `'required|string|regex:...'` → `['required', 'string', 'regex:...']` (array syntax)

## File yang Berubah
| File | Perubahan |
|------|-----------|
| `database/migrations/2026_06_08_*` | +nuptk, +dosen role |
| `app/Models/User.php` | +nuptk fillable |
| `app/Providers/AppServiceProvider.php` | +dosen-access gate |
| `app/Http/Controllers/Web/AuthController.php` | login + register auto-detect |
| `app/Http/Controllers/Api/AuthController.php` | login + register auto-detect |
| `app/Http/Requests/RegisterRequest.php` | regex 12/16 digit |
| `app/Http/Controllers/Dosen/DashboardController.php` | baru |
| `resources/views/dosen/dashboard/index.blade.php` | baru (copy mhs) |
| `routes/web.php` | +dosen route group |
| `resources/views/auth/login.blade.php` | hapus role switch, auto-detect |
| `resources/views/auth/register.blade.php` | NIM/NUPTK label |


---

## 🆕 9 Juni 2026 — NUPTK → NIM + Dosen Routes Lengkap

### 1. Register: 16 digit (NUPTK) disimpan ke kolom `nim`
- **Perubahan:** Dosen register pake 16 digit → masuk ke kolom `nim` (bukan `nuptk`)
- **File:** `Web/AuthController.php`, `Api/AuthController.php`
- Login juga cari 16 digit di `nim`
- `nuptk` column ga dipake lagi (bisa di-drop nanti)

### 2. Drop UNIQUE constraint dari `nim`
- **File:** `database/migrations/2026_06_08_000002_drop_unique_nim_from_users.php`
- Biar 12 digit (mhs) dan 16 digit (dosen) bisa sama-sama disimpen di `nim`

### 3. Dosen Routes Lengkap
| Route | Controller | View |
|-------|-----------|------|
| `/dosen/dashboard` | `Dosen\DashboardController` | `dosen/dashboard/index.blade.php` |
| `/dosen/catalog` | `Dosen\CatalogController` | `dosen/catalog/index.blade.php` |
| `/dosen/cart` | `Dosen\CartController` | `dosen/cart/index.blade.php` |
| `/dosen/cart/add/{id}` | `Dosen\CartController::add` | — |
| `/dosen/cart/update` | `Dosen\CartController::update` | — |
| `/dosen/cart/remove/{id}` | `Dosen\CartController::remove` | — |
| `/dosen/cart/submit` | `Dosen\CartController::submit` | — |
| `/dosen/borrowings` | `Dosen\BorrowingController::index` | `dosen/borrowings/index.blade.php` |
| `/dosen/borrowings/{id}` | `Dosen\BorrowingController::show` | `dosen/borrowings/show.blade.php` |
| `/dosen/profile` | `Dosen\ProfileController::index` | `dosen/profile/index.blade.php` |
| `/dosen/profile/update` | `Dosen\ProfileController::update` | — |

### 4. Sidebar Dosen (layout)
- **File:** `layouts/app.blade.php`
- `@elseif($role === 'dosen')` → nav links pake `route('dosen.*')`

### 5. File Baru
| # | File |
|---|------|
| 1 | `app/Http/Controllers/Dosen/CatalogController.php` |
| 2 | `app/Http/Controllers/Dosen/CartController.php` |
| 3 | `app/Http/Controllers/Dosen/BorrowingController.php` |
| 4 | `app/Http/Controllers/Dosen/ProfileController.php` |
| 5 | `resources/views/dosen/catalog/index.blade.php` |
| 6 | `resources/views/dosen/cart/index.blade.php` |
| 7 | `resources/views/dosen/borrowings/index.blade.php` |
| 8 | `resources/views/dosen/borrowings/show.blade.php` |
| 9 | `resources/views/dosen/profile/index.blade.php` |
| 10 | `resources/views/dosen/dashboard/index.blade.php` (udah ada) |
| 11 | `database/migrations/2026_06_08_000002_drop_unique_nim_from_users.php` |

### ⚠️ Yang perlu dijalankan
```bash
docker exec laravel_app php artisan migrate
```


### 6. Admin Users — Tampilin Dosen Juga
- **Masalah:** `Admin/UserController.php` pake `where('role', 'mahasiswa')` → dosen gak kelihatan
- **Fix:** Ganti jadi `whereIn('role', ['mahasiswa', 'dosen'])`
- **View:** Tambah kolom **Role** (badge biru=Mahasiswa, ungu=Dosen)
- Colspan di empty state dari 6 → 7

---

## 🆕 10 Juni 2026 — Rewrite Sistem Peminjaman Alat Lab (16 PBI)

### Goal
Build a complete university asset borrowing system (16 PBIs) matching the exact specification, including routes, UI design, controllers, views, error handling, and webhook integration.

### Constraints & Preferences
- **Design:** Biru tua #1E3A5F primary, Biru muda #3B82F6 accent, Abu #F8FAFC background, Inter font, rounded-lg cards, rounded-md buttons, sidebar 250px + main content layout
- **Status badges:** MENUNGGU=Kuning#F59E0B, DISETUJUI=Biru#3B82F6, DITOLAK=Merah#EF4444, DIPINJAM=Ungu#8B5CF6, DIKEMBALIKAN=Hijau#10B981, Nonaktif=Abu#6B7280
- **Status values (exact):** Borrowing → MENUNGGU/DISETUJUI/DITOLAK/DIPINJAM/DIKEMBALIKAN, Tool → TERSEDIA/MAINTENANCE/RUSAK
- **Exact error messages per PBI spec**, inline field validation
- **bcrypt hash** salt rounds=10, JWT token via Sanctum/session, httpOnly cookie
- **N8N webhook events:** submitted, approved, rejected, returned (fire-and-forget)
- **Routes:** `/register`, `/login`, `/admin/alat/*`, `/admin/peminjaman/*`, `/admin/inventaris/*`, `/admin/laporan`, `/admin/audit-trail`, `/katalog`, `/keranjang`, `/peminjaman`, `/peminjaman/riwayat`, `/profil`

### Done
- Explored existing Laravel 13.x project with models (User, Borowing, BorrowingItem, Tool, Item, ItemMutation, AuditLog), existing controllers, migrations, routes, services, and Blade views
- Rewrote `routes/web.php` with exact spec route names, grouped by middleware (guest, auth, admin/mahasiswa/dosen gates)
- Rewrote `app/Http/Controllers/Web/AuthController.php` with PBI-001 register (validasi, duplikasi cek, bcrypt hash, role MAHASISWA) and PBI-002 login (email/NIM lookup, is_active check, redirect by role)
- Rewrote `app/Services/N8NWebhookService.php` (fire-and-forget, error logged only)
- Rewrote `app/Services/AuditLogService.php` (time_stamp, dilakukan_oleh, role_pelaku, modul, aksi, id_record, data_sebelum, data_sesudah, ip_address)
- Rewrote `app/Http/Controllers/Admin/*` (7 controllers: Dashboard, Tool, Borrowing, Item, Report, Audit, User) with exact spec logic and status values
- Rewrote `app/Http/Controllers/Mahasiswa/*` (5 controllers: Dashboard, Catalog, Cart, Borrowing, Profile) with exact spec logic
- Rewrote `app/Http/Controllers/Dosen/*` (5 controllers) with same logic as Mahasiswa but dosen route namespace
- Added `alasan_penolakan` to Borowing model fillable and migration
- Updated migration enum values: tools.status_alat → [TERSEDIA, MAINTENANCE, RUSAK]; borowings.status → [MENUNGGU, DISETUJUI, DITOLAK, DIPINJAM, DIKEMBALIKAN]
- Updated Tool model `tersedia` scope to use 'TERSEDIA'
- Updated all Blade views (31 files) with exact design system
- Fixed route ordering (static `/peminjaman/aktif` before parameterized `/peminjaman/{borowing}`)
- Fixed HTTP method mismatch (Catat Kembali form)
- Fixed hardcoded URL paths
- Fixed status enum values in views (TERSEDIA, not Tersedia)
- Fixed inventaris create/edit missing fields
- Removed 13 orphaned view files

### Key Decisions
- Kept session-based auth for Blade views (spec JWT note interpreted as Sanctum cookie-based approach for web routes)
- Updated existing migration files with new enum values rather than creating new migration
- Added `alasan_penolakan` column to borowings table for PBI-005 reject reason

### Design Update (10 Juni 2026) — Dashboard & Manajemen Alat
- **Dashboard Admin — Stat Card:** Putih + border kiri 4px bewarna + icon bulat gradasi di kanan
  - Total Alat → border biru #3B82F6
  - Peminjaman Aktif → border ungu #8B5CF6
  - Stok Rendah → border merah #EF4444
  - Total Mahasiswa → border hijau #10B981
  - Total Dosen → border kuning #F59E0B
- **Dashboard Admin — Grafik:** Bar chart "Peminjam per Bulan" 6 bulan terakhir
- **Dashboard Admin — Modul Stok Rendah:** Tabel alat & barang stok ≤ 3
- **Dashboard Admin — Data:** `$totalMahasiswa`, `$totalDosen`, `$lowStockTools`, `$lowStockItems`, `$chartLabels`, `$chartData`
- **Manajemen Alat — Toolbar (satu box):** Search "Nama atau kode alat" + Dropdown Kategori + Dropdown Status
- **Manajemen Alat — Stat Card (4):** Total Inventaris (biru), Kondisi Baik (hijau), Sedang Dipinjam (ungu), Butuh Perbaikan (merah)
- **Manajemen Alat — Tabel:** Kolom Kode, Nama Alat, Kategori, Stok, Status, Aksi (icon mata/detail, pensil/edit, tong sampah/hapus)
- **Manajemen Alat — Backend:** Route baru `GET /alat/{id_alat}` (show), `DELETE /alat/{id_alat}` (destroy), stat queries (totalInventaris, kondisiBaik, sedangDipinjam, butuhPerbaikan)

### Next Steps
- Run `php artisan migrate:fresh --seed` to apply updated migrations
- Test all routes against spec (16 PBIs)
- Verify N8N webhook events fire correctly on approve/reject/return
- Test error handling messages match spec exactly

---

## 🆕 11 Juni 2026 — Form Alat Redesign + Logout Modern

### 1. Form Tambah Alat (create.blade.php) — Redesign Total
- **Layout:** Dua kolom — kiri "Informasi Utama" (1fr), kanan "Status & Kondisi" + "Media Alat" (320px)
- **Header actions:** Tombol **Batal** + **Simpan Data** dengan ikon SVG
- **Informasi Utama card:** Icon biru + header + divider line
  - Kode Alat / Kategori → side-by-side (2 kolom)
  - Nama Alat → full width
  - Deskripsi Alat → textarea full width
  - Lokasi / Stok Total / Stok Tersedia → 3 kolom
- **Status & Kondisi card:** Icon ungu + header + divider
  - Status Operasional → radio button Tersedia / Maintenance / Rusak (`flex-wrap` biar gak overflow)
  - Kondisi Fisik → dropdown (Baik, Rusak Ringan, Rusak Berat)
- **Media Alat card:** Icon kuning + header + divider
  - Upload file (png, jpeg, jpg, webp, max 2MB)

### 2. Form Edit Alat (edit.blade.php) — Sama Persis
- Layout, card, field, styling identik dengan create
- Value diisi dari `$tool` + `old()` untuk validation flash
- Preview foto lama ditampilkan jika sudah ada
- Tombol "Simpan Perubahan" (bukan "Simpan Data")

### 3. Detail Alat (show.blade.php)
- Menampilkan **Kondisi Fisik**
- Menampilkan **Foto Alat** (jika ada)

### 4. Database — Kolom kondisi_fisik
- Migration baru: `2026_06_11_000001_add_kondisi_fisik_to_tools_table.php`
- Kolom `string('kondisi_fisik', 50)->nullable()` setelah `foto_alat`

### 5. Controller — Upload & Delete Foto
- `store()`: validasi `kondisi_fisik`, `lokasi`, `foto_alat` (image, max 2MB)
- `update()`: validasi sama + hapus foto lama (`Storage::disk('public')->delete()`) kalo diganti
- Import `Illuminate\Support\Facades\Storage`

### 6. Model — Fillable
- `kondisi_fisik` ditambahkan ke `#[Fillable]`

### 7. Koneksi Database
- `.env`: `DB_HOST=172.24.80.1`, `DB_PORT=33061` (port MySQL via DBeaver)

### 8. Logout Button — Modern & Gedhe
- **CSS redesign:** Padding 12px 16px, font 14px weight 600
- **Border merah** transparan + background merah soft
- **Hover:** Naik 1px (`translateY(-1px)`), shadow merah glow, border merah solid
- **Ikon 20px** dengan efek `translateX(2px)` saat hover
- Warna teks: `#fca5a5` → `#fff` saat hover

---

## ✅ 11 Juni 2026 — Testing Manajemen Alat

### Acceptance Criteria — Semua Pass
| Kriteria | Status |
|----------|--------|
| Form tambah alat menampilkan semua field (Kode, Kategori, Nama, Deskripsi, Lokasi, Stok Total/Tersedia, Status, Kondisi Fisik, Foto) | ✅ |
| Data alat berhasil tersimpan di database setelah submit | ✅ |
| Alat baru langsung muncul di daftar alat | ✅ |
| Aksi tercatat di audit trail (ALAT - CREATE) | ✅ |
| Kode alat tidak bisa sama dengan alat lain | ✅ |

### Scenario Handling — Semua Pass
| Skenario | Expected | Result |
|----------|----------|--------|
| Kode alat sudah dipakai | "Kode alat sudah digunakan." | ✅ |
| Stok tersedia > stok total | "Stok tersedia tidak boleh melebihi stok total." | ✅ |
| Field wajib kosong | Error di field yang kosong | ✅ |
| Semua data valid | Tersimpan + daftar + audit trail | ✅ |

---

## 🆕 11 Juni 2026 — Fix ENUM Status & Cleanup View

### 1. Migrasi `update_enums_in_tools_table` Gagal
- **Masalah:** Data lama di `tools.status_alat` pakai nilai `'Tersedia'`, `'Dipinjam'`, `'Dalam Perbaikan'` (dari Web controller), gak cocok sama ENUM baru `TERSEDIA/MAINTENANCE/RUSAK`, jadi `ALTER TABLE MODIFY COLUMN` error "Data truncated"
- **Fix:** Ditambah `DB::statement("UPDATE tools SET status_alat = 'TERSEDIA' WHERE status_alat NOT IN ('TERSEDIA', 'MAINTENANCE', 'RUSAK')")` **sebelum** ALTER di `database/migrations/2026_06_11_000002_update_enums_in_tools_table.php`

### 2. Controller & Request — Validasi Status
| File | Perubahan |
|------|-----------|
| `app/Http/Controllers/Web/ToolController.php` | `in:Tersedia,...` → `in:TERSEDIA,MAINTENANCE,RUSAK` |
| `app/Http/Requests/Tool/StoreToolRequest.php` | `in:Tersedia,...` → `in:TERSEDIA,MAINTENANCE,RUSAK` |
| `app/Http/Requests/Tool/UpdateToolRequest.php` | `in:Tersedia,...` → `in:TERSEDIA,MAINTENANCE,RUSAK` |

### 3. Blade Views — Bersihin Old Title-Case Fallback
- **12 file view** dihapus old title-case fallback (`'Dipinjam'`, `'Disetujui'`, `'Menunggu'`, `'Ditolak'`, `'Dikembalikan'`) dari `match()` badge block dan `in_array()` — sekarang cuma pake uppercase
- **Dihapus:** `resources/views/admin/reports/index.blade.php` (orphaned view, gak ada route yang指向)

### File yang Berubah
| File | Perubahan |
|------|-----------|
| `database/migrations/2026_06_11_000002_update_enums_in_tools_table.php` | +UPDATE data lama sebelum ALTER |
| `app/Http/Controllers/Web/ToolController.php` | Validasi `in:TERSEDIA,...` |
| `app/Http/Requests/Tool/StoreToolRequest.php` | Validasi `in:TERSEDIA,...` |
| `app/Http/Requests/Tool/UpdateToolRequest.php` | Validasi `in:TERSEDIA,...` |
| `resources/views/admin/peminjaman/index.blade.php` | match() + in_array — uppercase only |
| `resources/views/admin/peminjaman/show.blade.php` | match() + in_array — uppercase only |
| `resources/views/admin/peminjaman/aktif.blade.php` | match() — uppercase only |
| `resources/views/admin/borrowings/index.blade.php` | match() + in_array — uppercase only |
| `resources/views/admin/borrowings/show.blade.php` | match() + in_array — uppercase only |
| `resources/views/dosen/peminjaman/index.blade.php` | match() — uppercase only |
| `resources/views/dosen/peminjaman/show.blade.php` | match() + in_array — uppercase only |
| `resources/views/dosen/dashboard/index.blade.php` | match() + whereIn — uppercase only |
| `resources/views/mahasiswa/peminjaman/index.blade.php` | match() — uppercase only |
| `resources/views/mahasiswa/peminjaman/riwayat.blade.php` | match() — uppercase only |
| `resources/views/mahasiswa/peminjaman/show.blade.php` | match() + in_array — uppercase only |
| `resources/views/mahasiswa/dashboard/index.blade.php` | match() + whereIn — uppercase only |
| `resources/views/admin/reports/index.blade.php` | **Dihapus** (orphaned) |

### 4. Status Testing
| Fitur | Status |
|-------|--------|
| Migrasi `update_enums_in_tools_table` | ✅ |
| Tambah alat (status `TERSEDIA/MAINTENANCE/RUSAK`) | ✅ |
| Edit alat | ✅ |
| Badge status peminjaman (all roles) | ✅ |
| Filter status di laporan & daftar | ✅ |
| Dashboard stat cards | ✅ |

---

## 🆕 11 Juni 2026 — Hapus Status "Rusak", Maintenance Jadi Merah

### 1. Hapus Opsi "Rusak" dari Manajemen Alat
- **Alasan:** User request — status operasional cuma butuh **Tersedia** & **Maintenance**
- Semua form, filter, validasi, badge, dan ENUM dibersihin dari `RUSAK`

### 2. Perubahan per File
| File | Perubahan |
|------|-----------|
| `resources/views/admin/alat/create.blade.php` | Hapus radio `value="RUSAK"` |
| `resources/views/admin/alat/edit.blade.php` | Hapus radio `value="RUSAK"` |
| `resources/views/admin/alat/index.blade.php` | Hapus dari filter dropdown + badge match `RUSAK` |
| `resources/views/admin/alat/show.blade.php` | Hapus dari badge match `RUSAK` |
| `resources/views/dosen/katalog/index.blade.php` | Hapus dari filter dropdown (already clean) |
| `resources/views/mahasiswa/katalog/index.blade.php` | Hapus dari filter dropdown (already clean) |
| `app/Http/Controllers/Admin/ToolController.php` | Validasi `in:TERSEDIA,MAINTENANCE` + stat card query |
| `app/Http/Controllers/Web/ToolController.php` | Validasi `in:TERSEDIA,MAINTENANCE` |
| `app/Http/Requests/Tool/StoreToolRequest.php` | Validasi + pesan error |
| `app/Http/Requests/Tool/UpdateToolRequest.php` | Validasi + pesan error |
| `database/migrations/2026_06_11_000002_update_enums_in_tools_table.php` | ENUM `TERSEDIA,MAINTENANCE` doang, `RUSAK` → `MAINTENANCE` |

### 3. Maintenance Badge — Hijau → Merah
- `resources/views/admin/alat/index.blade.php` — `badge-gray` → `badge-red`
- `resources/views/admin/alat/show.blade.php` — `badge-gray` → `badge-red`
- **Tersedia** tetap hijau (`badge-green`), **Maintenance** jadi merah (`badge-red`)

### 4. Status Testing
| Fitur | Status |
|-------|--------|
| Form tambah/edit — cuma Tersedia & Maintenance | ✅ |
| Filter status — cuma Tersedia & Maintenance | ✅ |
| Badge Maintenance merah di daftar & detail | ✅ |
| Data lama `RUSAK` keganti `MAINTENANCE` | ✅ |

---

## 🆕 11 Juni 2026 — Dashboard Admin: 5 Stat Card Sejajar

### Perubahan
- **File:** `resources/views/admin/dashboard/index.blade.php:13`
- **Before:** `grid-template-columns: repeat(auto-fit, minmax(200px, 1fr))` — otomatis wrap, 5 card bisa 2 baris
- **After:** `grid-template-columns: repeat(5, 1fr)` — paksa 5 kolom, semua sejajar dalam 1 baris
- **Testing:** ✅ 5 stat card (Total Alat, Peminjaman Aktif, Stok Rendah, Total Mahasiswa, Total Dosen) sejajar horizontal
