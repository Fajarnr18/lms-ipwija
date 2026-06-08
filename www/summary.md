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
