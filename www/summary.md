## 🆕 30 Juni 2026 — Redesign Audit Trail (Detail Page + Filter Aksi + 403 Protection)

### Perubahan
1. **Filter Aksi** — Dropdown Aksi (Buat/Ubah/Hapus/Setuju/Tolak/Kembali dll) ditambahkan di filter bar.
2. **Halaman Detail Audit Trail** — Halaman baru `/admin/audit-trail/{id}` (route `admin.audit-trail.show`):
   - Layout 2 kolom: kiri "Ringkasan Info", kanan "Komparasi Data"
   - **Ringkasan Info**: Nama Pengguna, Peran/Role (badge), Modul Sistem (chip), Aksi Dilakukan (badge), Alamat IP, Waktu Kejadian, tombol Kembali (panah kiri)
   - **Komparasi Data**: icon panah kanan/kiri, badge "Perubahan Terdeteksi" (merah) / "Tidak Ada Perubahan" (hijau), sub-kolom "Data Sebelum" & "Data Sesudah" dengan field dinamis dari JSON (nama_alat, status_alat, lokasi_rak, stok_akhir, id_kategori dll) + empty state jika tidak ada data
3. **403 Protection** — `Route::any('/audit-trail/{any}', ...)` catch-all return 403 untuk semua path/HTTP method selain index & export.
4. **Icon Mata** — Di index sekarang link ke halaman detail (bukan modal).
5. **Empty State** — "Tidak ada log ditemukan."

### File yang Diubah

| File | Perubahan |
|------|-----------|
| `resources/views/admin/audit-trail/index.blade.php` | +Dropdown filter Aksi; icon mata link ke detail page (bukan modal); hapus semua modal HTML + JS; header tabel background #1E3A5F teks putih |
| `resources/views/admin/audit-trail/detail.blade.php` | **Baru** — Halaman detail audit trail: layout 2 kolom (Ringkasan Info + Komparasi Data), field dinamis dari JSON, label map, badge perubahan terdeteksi |
| `app/Http/Controllers/Admin/AuditController.php` | +`show($id_log)` method (findOrFail + parse JSON + deteksi perubahan); +filter `aksi` di index & export; +`$aksis` distinct |
| `routes/web.php` | +Route `/audit-trail/{id_log}` → `AuditController@show`; +catch-all 403 fallback |

---

## 🆕 30 Juni 2026 — Redesign Tab Rekapitulasi Per Mahasiswa di Laporan (Stat Cards + Baris Per Halaman + Pagination)

### Perubahan
1. **4 Stat Cards** — Total Mahasiswa (icon body+head, biru), Status Aktif (icon person+check, hijau), Peminjam Aktif (icon refresh, biru), Terlambat Kembali (icon triangle/warning, merah) — real-time count
2. **Filter Boxes Lebar** — PILIH JENIS LAPORAN min-width 260px, START DATE & END DATE min-width 160px
3. **Table Baru** — Kolom: NIM, NAMA MAHASISWA, PROGRAM STUDI (chip), FREKUENSI PINJAM (bold navy), STATUS AKTIF (badge pill Aktif hijau / Nonaktif merah dengan dot), AKSI (icon mata link ke users index + search NIM)
4. **Baris Perhalaman** — Dropdown kiri bawah dengan opsi 10/25/50/100, auto-submit via form
5. **Page Numbering** — « 1 2 3 ... » di kanan bawah, dynamic sesuai total halaman, fallback jika data kosong
6. **Search & Pagination** — Controller: paginate dengan per_page dinamis; search by nama_lengkap/nim tetap dipertahankan
7. **Stat Query** — totalMahasiswa, statusAktif (is_active), peminjamAktif (has borrowings active), terlambatKembali (is_overdue)

### File yang Diubah

| File | Perubahan |
|------|-----------|
| `resources/views/admin/laporan/index.blade.php` | +Stat cards rekap-mahasiswa (4); +table redesign (NIM/Nama/Prodi/Frekuensi/Status/Aksi); +filter boxes wider (PILIH JENIS LAPORAN 260px, date 160px); +Baris Perhalaman dropdown; +pagination dynamic |
| `app/Http/Controllers/Admin/ReportController.php` | +$perPage dinamis; paginate() instead of get(); +stat query (totalMahasiswa, statusAktif, peminjamAktif, terlambatKembali); +withCount total_dipinjam; init vars; compact update |

---

## 🆕 30 Juni 2026 — Redesign Tab Inventaris Barang di Laporan (Stat Cards + Filter + Pagination)

### Perubahan
1. **4 Stat Cards** — Total Barang (biru), Total Stok (ungu), Kondisi Baik (hijau), Rusak (merah) — masing-masing dengan icon SVG, background gradient, real-time count
2. **Search Bar** — Cari berdasarkan `nama_barang` atau `kode_barang` via input search di header; hidden input `tab` agar search tidak reset tab
3. **Filter Bar** — Dropdown KONDISI (Semua/Baik/Rusak Ringan/Rusak Berat) + KATEGORI (dinamis from DB) auto-submit on change
4. **Pagination** — 15 item per halaman, inline pagination « 1 2 3 » + "Menampilkan X-Y dari Z hasil", fallback jika data kosong
5. **Table Styling** — Kolom: KODE, NAMA BARANG, KATEGORI (chip), KONDISI (badge warna: hijau/baik, kuning/rusak ringan, merah/rusak berat), LOKASI, STOK (bold navy besar), AKSI (icon mata → detail barang)
6. **Export & Cetak** — Tombol Export Excel + Cetak Laporan tetap di header-actions, data export sesuai filter aktif

### File yang Diubah

| File | Perubahan |
|------|-----------|
| `resources/views/admin/laporan/index.blade.php` | +Stat cards (4), +filter kondisi & kategori di filter bar, +table styling baru (chip kategori, badge kondisi, aksi mata), +pagination inline, +hidden input tab di header-search; inventaris-barang pindah ke `@elseif` sendiri |
| `app/Http/Controllers/Admin/ReportController.php` | +Search filter by nama_barang/kode_barang; +pagination (paginate 15); +statistik ($totalBarang, $totalStok, $kondisiBaik, $kondisiRusak); init di atas; compact() update |

---

## 🆕 29 Juni 2026 — Redesign Tab Alat Sedang Dipinjam (Stat Cards + Data Transaksi Table) + Header Actions

### Perubahan
1. **4 Stat Cards** — Total Pinjaman Aktif (biru), Terlambat Kembali (merah), Kembali Hari Ini (kuning/oranye), Peminjam Unik (ungu) — masing-masing dengan icon SVG, background soft, real-time count
2. **Data Transaksi Table** — Kolom: ID PINJAM, PEMINJAM (nama + NIM/role), NAMA ALAT (chip), TGL PINJAM, ESTIMASI KEMBALI (merah jika overdue + badge ● Terlambat), STATUS (badge pill TERLAMBAT/DIPINJAM/DISETUJUI dengan dot), AKSI (three-dots)
3. **Live Update Badge** — Label "Live Update" dengan dot animasi di header tabel
4. **Overdue Detection** — Badge TERLAMBAT (merah) jika `$b->is_overdue` true, tgl_kembali berwarna merah
5. **Export Label** — "Unduh CSV" → "Export Excel"
6. **Controller Logic** — Hitung `$totalPinjamanAktif`, `$totalTerlambat` (via `->filter(fn($b) => $b->is_overdue)`), `$kembaliHariIni` (whereDate tgl_rencana_kembali = now), `$peminjamUnik` (distinct mahasiswa_id); init 0 di atas method untuk safety
7. **Header Actions** — Export Excel + Cetak Laporan dipindah ke `@section('header-actions')` (muncul di page-header, bawah Admin Laboratorium)
8. **Cleanup** — Title "Alat Sedang Dipinjam" + subtitle dihapus dari konten tab; gap 20px → 14px

### File yang Diubah

| File | Perubahan |
|------|-----------|
| `resources/views/admin/laporan/index.blade.php` | Redesign total tab alat-dinjam: +4 stat cards, +live update badge, +ID pinjam + nama+NIM/role, +estimasi merah untuk overdue, +status badge TERLAMBAT/DIPINJAM/DISETUJUI, +aksi three-dots, +pagination; +header-actions section (Export Excel + Cetak Laporan di page-header); hapus title/subtitle/buttons dari konten tab |
| `app/Http/Controllers/Admin/ReportController.php` | +$totalPinjamanAktif, $totalTerlambat, $kembaliHariIni, $peminjamUnik; init di atas; compact() update |

### Perubahan
1. **Detail Barang Page** — Halaman baru `/admin/inventaris/{id}/detaildatabarang` (route `admin.inventaris.detail`) dengan layout: title "Detail Data barang", subtitle "Informasi lengkap mengenai aset laboratorium", left card (Kode Barang | Tanggal Pendataan, Nama Barang, Kategori | Kondisi Barang, DESKRIPSI PRODUK), right card (Stok Tersedia besar, Lokasi Penyimpanan, tombol Edit Data Barang biru gelap, Kembali)
2. **Edit Form** — Form edit barang dipisah ke `edit-form.blade.php` diakses via `/admin/inventaris/{id}/edit`
3. **Search di Topbar** — Input "Cari kode atau nama barang..." muncul di topbar halaman utama inventaris (non-mutasi) via `@section('header-search')`
4. **Aksi Table** — "Lihat Log" diganti icon mata (eye) link ke detail page; urutan aksi: Edit → Catat Mutasi → Hapus → Detail
5. **Icon Mutasi Chips** — Masuk icon panah bawah (hijau), Keluar icon panah atas (merah), Penyesuaian icon refresh (kuning)
6. **Stat Cards Log Mutasi** — Icon Total Pergerakan panah atas & bawah, Total Barang Masuk panah bawah, Total Barang Keluar panah atas
7. **Modal Konfirmasi** — Tombol "Batal" dan "Ya, Lanjutkan" text di-center dengan `justify-content:center`
8. **Title & Subtitle sejajar** — "Log Mutasi Stok" + subtitle sebaris dengan Export Excel + Cetak Laporan

### File yang Diubah

| File | Perubahan |
|------|-----------|
| `resources/views/admin/inventaris/detail.blade.php` | **Baru** — Halaman detail barang: layout 2 kolom, info barang + deskripsi, stok + lokasi, tombol edit & kembali |
| `resources/views/admin/inventaris/edit-form.blade.php` | **Baru** — Form edit barang dipisah dari detail, breadcrumb link ke detail |
| `resources/views/admin/inventaris/edit.blade.php` | **Dihapus** — Diganti `detail.blade.php` + `edit-form.blade.php` |
| `resources/views/admin/inventaris/index.blade.php` | +Search topbar untuk non-mutasi; "Lihat Log" → icon mata link detail; urutan aksi diubah (Detail setelah Hapus) |
| `resources/views/admin/inventaris/mutasi.blade.php` | Chip mutasi icon: Masuk panah bawah, Keluar panah atas, Penyesuaian refresh |
| `resources/views/layouts/app.blade.php` | Modal tombol "Batal" + "Ya, Lanjutkan" + `justify-content:center` |
| `app/Http/Controllers/Admin/ItemController.php` | +Method `detail()`; method `edit()` return `edit-form` langsung |
| `routes/web.php` | +Route `/inventaris/{id}/detaildatabarang` → `admin.inventaris.detail` |

---



### Perubahan
Halaman `/admin/inventaris?tab=mutasi` dirombak total:

1. **Search di Topbar** — Input "Cari alat..." via `@section('header-search')` khusus tab mutasi
2. **Title & Actions** — "Log Mutasi Stok" di page-header + tombol Export Excel (btn-outline) & Cetak Laporan (biru, teks putih) di header-actions
3. **Subtitle** — "Laporan real-time Mutasi Barang Universitas IPWija"
4. **Filter Baris** — Pilih Jenis Laporan, START DATE, END DATE, Jenis Mutasi (Semua/Masuk/Keluar/Penyesuaian) — auto-submit on change + Reset
5. **Stat Cards** — Total Pergerakan (biru), Total Barang Masuk (hijau), Total Barang Keluar (merah) — gradien background + icon
6. **Tabel Log** — Kolom: TANGGAL & WAKTU | NAMA ALAT | PETUGAS | JENIS (badge) | JUMLAH | STOK AWAL | STOK AKHIR | KETERANGAN
7. **Pagination** — "Menampilkan semua X data" (kiri) + page numbers (kanan) dengan border-top

### File yang Diubah

| File | Perubahan |
|------|-----------|
| `resources/views/admin/inventaris/index.blade.php` | +`@section('header-search')` untuk tab mutasi; +`@section('header-actions')` conditional (Export Excel + Cetak Laporan); redesign `@if(tab===mutasi)` block: breadcrumb, filter bar, stat cards, tabel baru, pagination; hide keterangan card saat tab mutasi |
| `app/Http/Controllers/Admin/ItemController.php` | +Search filter by nama/kode barang untuk mutasi; +date range filter (from/to); +tipe_mutasi filter; +statistik (totalPergerakan, totalMasuk, totalKeluar) real-time |

---

## 🆕 29 Juni 2026 — Redesign Halaman Mutasi Stok (Layout Baru)

### Perubahan
Halaman `/admin/inventaris/{id}/mutasi` dirombak total sesuai layout baru:

1. **Search di Topbar** — Input "Cari data mutasi..." via `@section('header-search')`
2. **Breadcrumb** — "Manajemen Alat > Form Mutasi Barang" di atas card
3. **Card Input Mutasi Barang** — Title biru bold "Input Mutasi Barang", subtitle "Catat pergerakan stok barang lab dengan teliti", divider `hr`
4. **Pilih Barang** — Dropdown disabled menampilkan item saat ini + stok
5. **Jenis Mutasi** — 3 chip clickable: Masuk (hijau), Keluar (merah), Penyesuaian (kuning), sebaris dengan Jumlah + Jumlah per unit
6. **Jumlah + Jumlah per unit** — Input number + dropdown satuan di sebelah kanan Jenis Mutasi
7. **Preview Stok** — Box warna sesuai tipe mutasi (hijau masuk, merah keluar, kuning penyesuaian), hitung otomatis real-time
8. **Tanggal** — Date picker (default hari ini)
9. **Keterangan** — Textarea
10. **Tombol** — Batal (kiri) + Simpan Mutasi (biru, kanan), sejajar rata kanan
11. **Riwayat Mutasi** — Tabel log mutasi tetap dipertahankan di card terpisah

### File yang Diubah

| File | Perubahan |
|------|-----------|
| `resources/views/admin/inventaris/mutasi.blade.php` | Redesign total: header-search, breadcrumb, card form baru (chip mutasi, jumlah+satuan sebaris, preview stok, tanggal, textarea), tombol Batal+Simpan, riwayat mutasi |
| `app/Http/Controllers/Admin/ItemController.php` | +Validasi `tgl_mutasi` nullable|date; time_stamp pakai tgl_mutasi dari form (fallback now()) |

---

### Perubahan
1. **Import Tools ke Items** — Route `/_import-tools` trigger `import:tools-to-items`; fix mapping RUSAK → Rusak Berat (sebelumnya ke Baik karena `default`)
2. **Validasi Bahasa Indonesia** — Semua pesan error di ItemController (Tambah/Edit/Mutasi) pakai Bahasa Indonesia: "Kode barang sudah digunakan", "Jumlah harus lebih dari 0", "Stok tidak mencukupi", "Keterangan wajib diisi"
3. **Fitur Hapus Barang** — Tombol trash (ikon sampah merah) di kolom Aksi tabel inventaris; pake modal konfirmasi (`data-confirm`) + soft delete + audit trail
4. **Redesign Form Tambah Barang** — Layout 2 kolom: kiri Informasi Dasar (Nama, Kategori, SKU), kanan Stok & Lokasi (stok −/+, Satuan, Lokasi) + tombol Simpan & Batal; Spesifikasi & Kondisi (chip kondisi, tanggal, deskripsi) di bawah full width
5. **Redesign Form Edit Barang** — Disamakan persis dengan form Tambah (layout, chip kondisi interaktif, stok +/-)
6. **Header Title** — `@yield('header-title')` di topbar layout; halaman bisa naruh judul di topbar sejajar info admin
7. **Peminjaman Title** — "Manajemen Peminjaman" pindah ke topbar; search box dihapus dari topbar biar rapi
8. **Empty State** — Tombol "Tambah Barang Sekarang" di tabel kosong inventaris

### File yang Diubah

| File | Perubahan |
|------|-----------|
| `app/Console/Commands/ImportToolsToItems.php` | Fix mapping RUSAK → Rusak Berat |
| `routes/web.php` | +Route `_import-tools` (trigger command); +Route `inventaris.destroy` |
| `app/Http/Controllers/Admin/ItemController.php` | +`destroy()` method; +custom validation messages Bahasa Indonesia (store, update, mutasiStore) |
| `resources/views/admin/inventaris/index.blade.php` | +Tombol hapus (trash + modal confirm) di kolom Aksi; +empty state "Tambah Barang Sekarang" |
| `resources/views/admin/inventaris/create.blade.php` | Redesign total: layout 2 kolom (Informasi Dasar + Stok & Lokasi), chip kondisi interaktif, stok +/- |
| `resources/views/admin/inventaris/edit.blade.php` | Redesign total: disamakan persis dengan create.blade.php |
| `resources/views/layouts/app.blade.php` | +`@yield('header-title')` di topbar; +CSS `.header-title` |
| `resources/views/admin/peminjaman/index.blade.php` | +header-search (search + filter) di topbar; title "Manajemen Peminjaman" balik ke page-header |
| `resources/views/admin/peminjaman/kembali.blade.php` | Fix tombol Simpan Verifikasi (disabled dihapus, JS validasi on submit); +header-search (search + filter) di topbar; title pindah ke page-header |

## 🆕 27 Juni 2026 — Pagination Dirapihkan (Custom View, Duplikasi Text & Wrapper Dihapus)

### Perubahan
Pagination di seluruh halaman dirapihkan:
1. **Custom Pagination View** — Override default Tailwind pagination dengan view sendiri di `resources/views/vendor/pagination/tailwind.blade.php`
2. **Teks Info Bahasa Indonesia** — Pagination sekarang nampilin "Menampilkan 1-10 dari 11 hasil" (bukan "Showing 1 to 10 of 11 results")
3. **Layout Flex** — Info teks di kiri, nomor halaman di kanan dalam satu baris (`justify-content: space-between`)
4. **Styling Konsisten** — Nomor halaman pakai border, rounded, warna navy untuk active, sesuai tema
5. **Hapus Duplikasi** — Custom "Menampilkan..." text di blade dihapus (karena pagination view sudah handle)
6. **Hapus Wrapper Redundan** — Semua `<div class="pagination">` wrapper di 14 file blade dihapus

### File yang Diubah

| File | Perubahan |
|------|-----------|
| `resources/views/vendor/pagination/tailwind.blade.php` | **Baru** — Custom pagination view: teks Indo, layout flex info kiri + nomor kanan, styling tema |
| `resources/views/admin/peminjaman/index.blade.php` | Hapus custom "Menampilkan semua data peminjaman" text & wrapper div |
| `resources/views/admin/inventaris/index.blade.php` | Hapus custom "Menampilkan X-Y dari Z barang" text & wrapper div (2 tempat) |
| `resources/views/admin/peminjaman/aktif.blade.php` | Hapus custom "Menampilkan X dari Y entri" text & wrapper div |
| `resources/views/admin/borrowings/index.blade.php` | Hapus wrapper `div.pagination` |
| `resources/views/admin/users/index.blade.php` | Hapus wrapper `div.pagination` |
| `resources/views/admin/alat/index.blade.php` | Hapus wrapper `div.pagination` |
| `resources/views/admin/alat/show.blade.php` | Hapus wrapper `div.pagination` |
| `resources/views/admin/audit-trail/index.blade.php` | Hapus wrapper `div.pagination` |
| `resources/views/dosen/katalog/index.blade.php` | Hapus wrapper `div.pagination` |
| `resources/views/dosen/katalog/show.blade.php` | Hapus wrapper `div.pagination` |
| `resources/views/dosen/peminjaman/index.blade.php` | Hapus wrapper `div.pagination` |
| `resources/views/dosen/peminjaman/riwayat.blade.php` | Hapus wrapper `div.pagination` |
| `resources/views/mahasiswa/katalog/index.blade.php` | Hapus wrapper `div.pagination` |
| `resources/views/mahasiswa/katalog/show.blade.php` | Hapus wrapper `div.pagination` |
| `resources/views/mahasiswa/peminjaman/index.blade.php` | Hapus wrapper `div.pagination` |
| `resources/views/mahasiswa/peminjaman/riwayat.blade.php` | Hapus wrapper `div.pagination` |

## 🆕 25 Juni 2026 — Aktivasi Admin, Filter Prodi, Validasi Digit, Badge Role, Redesign Dashboard & Bulk Add Katalog

### Perubahan
1. **Register** — Placeholder NIM/NUPTK diganti "Masukkan NIM atau NUPTK".
2. **Register** — Opsi Program Studi difilter hanya: Informatika, Rekayasa Perangkat Lunak, Sistem Informasi, Kebidanan.
3. **Aktivasi Admin** — User baru daftar dengan `is_active = false`, tidak bisa login sampai diaktifkan admin di `/admin/users`.
4. **Validasi Digit** — NIM 14 digit → mahasiswa, NUPTK 16 digit → dosen. Jika tidak sesuai, tampilkan error.
5. **Badge Role** — Dashboard mahasiswa tampilkan badge "Mahasiswa", dashboard dosen tampilkan badge "Dosen".
6. **Dashboard Title** — Title "Dashboard" diganti "Selamat datang, {nama_lengkap}"; subtitle "Berikut adalah ringkasan inventaris dan status peminjaman anda hari ini".
7. **Cart AJAX** — Klik icon keranjang di katalog langsung tambah via AJAX, stay di halaman, toast notifikasi muncul, badge cart di navbar otomatis update.
8. **Redesign Keranjang** — Layout 2 kolom: Daftar Alat (No, Foto, Nama, Jumlah, Hapus) + Detail Peminjaman (tanggal, catatan) di kiri; Ringkasan Pesanan (total alat, total kuantitas, estimasi durasi, tombol ajukan) di kanan.
9. **Cleanup Keranjang** — Hapus duplicate header "Keranjang Peminjaman" & hapus info user (nama + email) di halaman keranjang.
10. **Layout Keranjang** — Daftar Alat full width (2 kolom grid), Ringkasan Pesanan disejajarkan tepat di sebelah kanan Detail Peminjaman.
11. **Fix Cart Badge** — Key `'jumlah'` diganti `'jumlah_unit'` di navbar (badge selalu 0 sebelumnya).
12. **Fix Hapus + Modal** — Route `hapus` kembali ke GET; native `confirm()` diganti modal popup `showConfirmModal` + `window.location.href`.
13. **Redesign Katalog Detail** — Halaman detail alat mahasiswa/dosen disamakan dengan admin: layout 2 kolom (foto + info di kiri, riwayat peminjaman di kanan), search + pagination, form tambah ke keranjang di bawah info.

### File yang Diubah

| File | Perubahan |
|------|-----------|
| `resources/views/auth/register.blade.php` | Placeholder NIM; Prodi: hanya 4 opsi |
| `app/Http/Controllers/Web/AuthController.php` | `is_active` → `false`; validasi digit 14/16; role otomatis |
| `resources/views/mahasiswa/dashboard/index.blade.php` | +badge "Mahasiswa"; title "Selamat datang, {nama}"; subtitle ringkasan |
| `resources/views/dosen/dashboard/index.blade.php` | +badge "Dosen"; title "Selamat datang, {nama}"; subtitle ringkasan |
| `resources/views/layouts/app.blade.php` | +`@yield('subtitle_badge')` di samping subtitle; fix `sum('jumlah')` → `sum('jumlah_unit')` |
| `resources/views/mahasiswa/katalog/index.blade.php` | Tombol AJAX add-to-cart, toast notif, update badge |
| `resources/views/dosen/katalog/index.blade.php` | Tombol AJAX add-to-cart, toast notif, update badge |
| `routes/web.php` | Route `hapus`: DELETE → GET (revert, karena nested form & dynamic submit bermasalah) |
| `resources/views/mahasiswa/katalog/show.blade.php` | Redesign total: layout 2 kolom ala admin, foto + detail di kiri, riwayat + search di kanan, form tambah ke keranjang |
| `resources/views/dosen/katalog/show.blade.php` | Redesign total: layout 2 kolom ala admin, foto + detail di kiri, riwayat + search di kanan, form tambah ke keranjang |
| `app/Http/Controllers/Mahasiswa/CatalogController.php` | `show()` tambah parameter `Request` + search filter untuk riwayat |
| `app/Http/Controllers/Dosen/CatalogController.php` | `show()` tambah query `$borrowings` + search filter untuk riwayat |
| `resources/views/mahasiswa/keranjang/index.blade.php` | Redesign total: layout 2 kolom, foto, ringkasan biru, estimasi durasi; hapus duplicate header & user info; Daftar Alat full width, Ringkasan sejajar Detail Peminjaman; Hapus link → modal + `window.location.href`, native `confirm()` dihapus |
| `resources/views/dosen/keranjang/index.blade.php` | Redesign total: layout 2 kolom, foto, ringkasan biru, estimasi durasi; hapus duplicate header & user info; Daftar Alat full width, Ringkasan sejajar Detail Peminjaman; Hapus link → modal + `window.location.href`, native `confirm()` dihapus |
| `app/Http/Controllers/Mahasiswa/CartController.php` | `tambah()` return JSON untuk AJAX; +`foto_alat` di session cart |
| `app/Http/Controllers/Dosen/CartController.php` | `tambah()` return JSON untuk AJAX; +`foto_alat` di session cart |

---

## 🆕 24 Juni 2026 — Modal Popup untuk Semua Notifikasi & Konfirmasi

### Perubahan
Semua `confirm()` dan `confirmAction()` native JS dialogs diganti dengan **modal popup** yang reusable. Semua flash message (`session('success')`, `session('error')`) juga ditampilkan dalam modal, bukan inline alert.

> **Aturan:** Setiap notifikasi (success/error/info) dan konfirmasi WAJIB menggunakan modal popup — dilarang pakai native `confirm()`, `alert()`, atau inline alert manual.

### 1. Modal Konfirmasi (Reusable) — layouts/app.blade.php
- **Trigger:** Tombol dengan atribut `data-confirm="Pesan konfirmasi"`
- **JS delegation:** Click handler otomatis intercept `[data-confirm]`, tampilkan modal
- **Tampilan:** Icon warning kuning + pesan + tombol Batal / Ya Lanjutkan
- **Fungsi:** `showConfirmModal(message, callback)`, `closeConfirmModal()`, `executeConfirm()`

### 2. Modal Notifikasi (Reusable) — layouts/app.blade.php
- **Trigger:** Flash message session (success/error) atau panggilan `showNotifModal(type, message)`
- **Tampilan:** Icon sukses (hijau) atau error (merah) + judul + pesan + tombol Tutup
- **Auto-show:** `DOMContentLoaded` — cek `@if(session('success'))` / `@if(session('error'))`
- **Fungsi:** `showNotifModal(type, message)`, `closeNotifModal()`

### 3. File yang Diubah (Layout)

| File | Perubahan |
|------|-----------|
| `resources/views/layouts/app.blade.php` | +modal HTML (confirm + notif), +JS functions, +data-confirm delegation, ganti inline alert → modal trigger |

### 4. File yang Diupdate (Confirm Actions → data-confirm)

| File | Tombol | Pesan |
|------|--------|-------|
| `admin/peminjaman/index.blade.php` | Setujui | "Setujui peminjaman ini?" |
| `admin/peminjaman/index.blade.php` | Proses | "Proses peminjaman ini? Alat akan dicatat sebagai sedang dipinjam." |
| `admin/peminjaman/show.blade.php` | Approve | "Setujui peminjaman ini?" |
| `admin/peminjaman/show.blade.php` | Proses Peminjaman | "Proses peminjaman ini? Status akan berubah menjadi Dipinjam." |
| `admin/borrowings/index.blade.php` | Setuju | "Setujui peminjaman ini?" |
| `admin/borrowings/index.blade.php` | Proses | "Proses peminjaman ini?" |
| `admin/borrowings/show.blade.php` | Setujui | "Setujui peminjaman ini?" |
| `admin/alat/index.blade.php` | Hapus | "Hapus alat {nama}?" |
| `admin/users/index.blade.php` | Aktifkan/Nonaktifkan | "{Aktifkan/Nonaktifkan} user ini?" |

### 5. File yang Diupdate (Flash Messages → Modal)

| File | Perubahan |
|------|-----------|
| `admin/peminjaman/aktif.blade.php` | Hapus `@if(session('success'))` inline alert (layout handle via modal) |
| `auth/login.blade.php` | +modal HTML + JS functions, ganti session success + error → `showNotifModal()` |
| `auth/register.blade.php` | +modal HTML + JS functions, ganti session success + error → `showNotifModal()` |

### 6. Bug Fix (25 Juni 2026)

| File | Issue | Fix |
|------|-------|-----|
| `auth/login.blade.php` | ParseError: missing `@endif` untuk `@if ($errors->any())` → Internal Server Error 500 | Tambah `@endif` setelah script `showNotifModal()` |

## 🆕 26 Juni 2026 — Fitur Deteksi Terlambat (Overdue)

### Perubahan
Setiap peminjaman dengan status **DIPINJAM** yang sudah melewati **estimasi kembali** (`tgl_rencana_kembali`) otomatis ditandai sebagai **Terlambat** berdasarkan real-time.

### Logika
- Jika `status === 'DIPINJAM'` dan `tgl_rencana_kembali < now()` → dianggap **Terlambat**
- Badge berubah jadi merah (badge-red) dengan label "Terlambat"
- Tidak mengubah status di database — murni komputasi real-time via accessor model

### File yang Diubah

| File | Perubahan |
|------|-----------|
| `app/Models/Borowing.php` | + `getIsOverdueAttribute()` — true jika DIPINJAM & lewat estimasi |
| `admin/peminjaman/index.blade.php` | Badge status: cek `$b->is_overdue` → tampilkan "Terlambat" |
| `admin/peminjaman/show.blade.php` | Badge status: cek `$borowing->is_overdue` → tampilkan "Terlambat" |
| `admin/peminjaman/aktif.blade.php` | Badge status: ganti match TERLAMBAT statis → cek `$b->is_overdue` |
| `admin/borrowings/index.blade.php` | Badge status: cek `$b->is_overdue` → tampilkan "Terlambat" |
| `admin/borrowings/show.blade.php` | Badge status: cek `$borowing->is_overdue` → tampilkan "Terlambat" |

### Acceptance Criteria
| Kriteria | Status |
|----------|--------|
| Peminjaman DIPINJAM lewat estimasi muncul badge "Terlambat" | ✅ |
| Perhitungan berdasarkan real-time (bukan cron/database) | ✅ |
| Tidak mengganggu status lain (Menunggu, Disetujui, etc) | ✅ |
| Konsisten di semua halaman (peminjaman & borrowings) | ✅ |

## 🆕 26 Juni 2026 — Rapihin Tombol Tutup & CSS Modal Hilang di Login/Register

### Perubahan
1. Styling tombol **"Tutup"** pada modal notifikasi dirapihin — teks di-center, padding lebih lega, font lebih tebal.
2. **Bug:** Modal notifikasi di halaman `login` dan `register` keliatan terus karena tidak punya CSS `.modal-overlay` (CSS cuma ada di `layouts/app.blade.php`). Akibatnya tombol "Tutup" dan konten modal muncul di pojok kanan halaman.

### File yang Diubah

| File | Perubahan |
|------|-----------|
| `layouts/app.blade.php` | Tombol Tutup: +`justify-content:center`, `padding:10px 24px`, `font-size:14px`, `font-weight:600` |
| `auth/login.blade.php` | Tombol Tutup: +styling rapi; +CSS `.modal-overlay`, `.modal-overlay.show`, `.modal` |
| `auth/register.blade.php` | Tombol Tutup: +styling rapi; +CSS `.modal-overlay`, `.modal-overlay.show`, `.modal` |

## 🆕 26 Juni 2026 — Redesign Halaman Inventaris (Manajemen Barang + Mutasi Stok)

### Perubahan
Halaman `/admin/inventaris` dirombak total — flow CRUD barang, mutasi stok (Masuk/Keluar/Penyesuaian), log mutasi, dan audit trail.

### Fitur
1. **Daftar Barang** — Tabel dengan statistik (total barang, kondisi baik, rusak, total stok), filter kategori & kondisi, aksi Edit / Catat Mutasi / Lihat Log
2. **Log Mutasi** — Tab riwayat semua mutasi stok dengan stok sebelum/sesudah
3. **Tambah / Edit Barang** — Form dengan validasi kode barang unik
4. **Mutasi Stok** — Pilih tipe (Masuk/Keluar/Penyesuaian), preview stok real-time, validasi stok keluar berlebih, simpan snapshot stok sebelum/sesudah + audit trail

### Validasi (Error Handling)
| Skenario | Pesan |
|----------|-------|
| Kode barang sudah dipakai | "Kode barang sudah digunakan" (dari Laravel unique) |
| Jumlah mutasi kosong/nol | "Jumlah harus lebih dari 0" |
| Stok keluar melebihi stok | "Stok tidak mencukupi" |
| Keterangan mutasi kosong | "Keterangan wajib diisi" (required) |
| Mutasi berhasil | Log tersimpan, stok terupdate, audit trail tercatat |

### File yang Diubah

| File | Perubahan |
|------|-----------|
| `app/Http/Controllers/Admin/ItemController.php` | +statistik index (totalBarang, baikCount, rusakCount, totalStok, kategoris); validasi Penyesuaian (min:0 bukan min:1); filter kategori |
| `resources/views/admin/inventaris/index.blade.php` | Redesign total — title "Inventaris Barang", subtitle real-time, Log Mutasi pindah ke header-action (samping Tambah Barang), tabs dihapus, stats cards responsive (auto-fit), link kembali |
| `resources/views/admin/inventaris/create.blade.php` | Redesign form — layout lebih bersih, placeholder, required marker |
| `resources/views/admin/inventaris/edit.blade.php` | Redesign form — stok read-only dengan info "Gunakan Mutasi", layout lebih bersih |
| `resources/views/admin/inventaris/mutasi.blade.php` | Redesign total — header barang dark, form 3 kolom, preview stok real-time (JS), validasi JS client-side, riwayat mutasi |

### Acceptance Criteria
| Kriteria | Status |
|----------|--------|
| CRUD barang berfungsi dengan benar | ✅ |
| Setiap mutasi stok tersimpan di log dengan stok sebelum & sesudah | ✅ |
| Stok barang terupdate setelah mutasi | ✅ |
| Tipe mutasi Masuk (+), Keluar (-), Penyesuaian (=) | ✅ |
| Aksi tercatat di audit trail | ✅ |
| Validasi kode unik, jumlah >0, stok cukup, keterangan wajib | ✅ |
| Preview stok real-time pada form mutasi | ✅ |

### Acceptance Criteria
| Kriteria | Status |
|----------|--------|
| Semua native `confirm()` dialog → modal popup | ✅ |
| Semua inline success/error alert → modal notifikasi | ✅ |
| Modal notifikasi bisa dismiss (tombol Tutup) | ✅ |
| Modal konfirmasi punya Batal + Ya Lanjutkan | ✅ |
| Batal menutup modal tanpa aksi | ✅ |
| Ya/Lanjutkan mengeksekusi form action | ✅ |

## 🆕 25 Juni 2026 — Search Bar & Keterangan di Inventaris

### Perubahan
1. **Search bar** — Satu input di atas title "Inventaris Barang" dengan placeholder "Cari kode atau nama barang...". Mencari di kolom `kode_barang` dan `nama_barang` via parameter `?search=`.
2. **Toolbar dirapihin** — Input "Cari Kode" dan "Cari" digabung jadi satu, toolbar tinggal filter Kategori & Kondisi.
3. **Keterangan** — Card di bagian bawah halaman yang menjelaskan arti badge kondisi (Baik, Rusak Ringan, Rusak Berat).

### File yang Diubah

| File | Perubahan |
|------|-----------|
| `app/Http/Controllers/Admin/ItemController.php` | Hapus filter `kode` terpisah; `search` sekarang cari `kode_barang` + `nama_barang` |
| `resources/views/admin/inventaris/index.blade.php` | +`@section('top-bar')` search form di atas title; toolbar hanya kategori & kondisi; +card Keterangan di bawah |
| `resources/views/layouts/app.blade.php` | +`@yield('top-bar')` sebelum page-header |

---

## 🆕 30 Juni 2026 — Finalisasi Halaman Audit Trail (Penyempurnaan Filter & Tampilan)

### Perubahan
1. **Hapus Filter AKSI** — Dropdown Aksi dihapus dari filter bar; semua logic filter `$request->aksi` dan variabel `$aksis` dihapus dari `AuditController@index` & `export`.
2. **Pencarian Pindah ke Topbar** — Input search dipindah dari filter bar ke `@section('header-search')` dengan icon kaca pembesar, auto-submit via `oninput`, dan hidden input untuk preserve filter params lain.
3. **Tombol Cari Jadi Icon Filter** — Tombol "Cari" (biru, teks putih) diganti icon funnel/filter biru tanpa teks.
4. **MODUL Chip Seragam** — Warna chip modul diseragamkan jadi background biru (`#DBEAFE`) teks hitam (`#000000`), tidak lagi beda warna per modul.
5. **ROLE Hanya Admin/Mahasiswa/Dosen** — `in_array()` check: selain ketiga role tersebut (misal "System") ditampilkan sebagai `-`.
6. **AKSI Badge Inline Style** — Mapping aksi diganti dari class badge ke inline style:
   - CREATE/Buat/Tambah → background hijau `#22C55E`, teks putih
   - UPDATE/Ubah/Edit → background kuning `#FACC15`, teks hitam
   - DELETE/Hapus → background merah `#EF4444`, teks putih
   - APPROVE/Setuju → background biru `#3B82F6`, teks putih
   - REJECT/Tolak → background merah `#EF4444`, teks putih
   - RETURN/Kembali → background biru `#3B82F6`, teks putih
   - Default → background abu `#6B7280`, teks putih

### File yang Diubah

| File | Perubahan |
|------|-----------|
| `resources/views/admin/audit-trail/index.blade.php` | Hapus dropdown AKSI; pindah search ke header-search + icon + auto-submit; tombol Cari → icon filter; MODUL chip warna seragam biru; ROLE filter hanya Admin/Mahasiswa/Dosen; AKSI badge jadi inline style warna solid |
| `app/Http/Controllers/Admin/AuditController.php` | Hapus filter `$request->aksi` di index & export; hapus variabel `$aksis`; hapus `'aksis'` dari compact |

---

## 🆕 30 Juni 2026 — Redesign Halaman Manajemen User (Stat Cards + Filter Prodi + Status Badge)

### Perubahan
1. **4 Stat Cards** — Total User (navy), User Aktif (hijau), Nonaktif (merah), Mahasiswa Baru (ungu) — real-time count, icon SVG
2. **Filter Bar** — Dropdown "Semua Program Studi" (dinamis dari DB) + "Semua Status" (Aktif/Nonaktif), auto-submit on change, icon filter biru
3. **Tabel Baru** — Kolom: NAMA, ROLE (badge biru/ungu), NIM/NIDN, PROGRAM STUDI, EMAIL, STATUS (badge pill dengan dot: hijau Aktif / merah Nonaktif), AKSI (tombol Aktifkan hijau / Nonaktifkan merah)
4. **Status Badge** — Inline style dengan dot indikator: background hijau `#DCFCE7` teks `#16A34A` untuk Aktif, background merah `#FEE2E2` teks `#DC2626` untuk Nonaktif
5. **Aksi Button** — Tombol Aktifkan background `#16A34A` (hijau), Nonaktifkan background `#DC2626` (merah), teks putih
6. **Search Topbar** — Input search "Search for names or NIM." di topbar dengan icon, auto-submit, preserve filter params

### File yang Diubah

| File | Perubahan |
|------|-----------|
| `resources/views/admin/users/index.blade.php` | Redesign total: +4 stat cards, +filter prodi & status, +table baru dengan status badge dot + aksi button warna, +search topbar |
| `app/Http/Controllers/Admin/UserController.php` | +$totalUser, $userAktif, $nonaktif, $mahasiswaBaru (30 hari); +$programStudis distinct; compact() update |

### Rules of Flow
- Admin membuka halaman Manajemen User
- Admin melihat daftar semua akun mahasiswa
- Admin bisa filter berdasarkan nama, NIM, prodi, status
- Admin memilih akun yang ingin diubah statusnya
- Admin klik Aktifkan atau Nonaktifkan
- Sistem menampilkan konfirmasi dialog
- Sistem mengubah nilai is_active di database dan mencatat ke audit trail

### Acceptance Criteria
- Daftar akun mahasiswa tampil lengkap
- Admin bisa mengaktifkan akun yang nonaktif
- Admin bisa menonaktifkan akun yang aktif
- Mahasiswa yang dinonaktifkan tidak bisa login
- Data akun tidak dihapus, hanya status yang berubah
- Setiap perubahan status akun tercatat di audit trail

### Scenario & Expected Handling

| Skenario | Expected Handling |
|----------|-------------------|
| Admin nonaktifkan akun | Konfirmasi dialog muncul, jika Ya maka is_active = false |
| Mahasiswa yang nonaktif coba login | Sistem menampilkan 'Akun Anda telah dinonaktifkan' |
| Admin aktifkan kembali akun | is_active = true, mahasiswa bisa login kembali |
| Admin klik tanpa konfirmasi | Aksi dibatalkan, tidak ada perubahan |

---

## 🆕 30 Juni 2026 — Redesign Dashboard Mahasiswa & Dosen (Seragam + Aktivitas + Peminjaman Aktif)

### Perubahan
1. **Dashboard Seragam** — Mahasiswa dan dosen kini dashboardnya identik (beda badge role dan route saja).
2. **Header Search** — Input "Cari alat atau peminjaman" di topbar, mengarah ke halaman katalog.
3. **Title Case Nama** — Nama pengguna diubah jadi Title Case via `ucwords(mb_strtolower())`.
4. **Stat Cards** — 4 kartu: Menunggu (oranye), Berjalan (biru), Keranjang (ungu), Selesai (hijau).
5. **Peminjaman Aktif** — Section heading + link "Lihat semua" biru. Cards menampilkan:
   - Gambar alat (72x72, thumbnail dari `foto_alat`)
   - Kode peminjaman (#ID)
   - Nama alat + count "lainnya" jika lebih dari 1 item
   - Tanggal Kembali (merah jika overdue) & Jumlah Item
   - Divider + link "Detail Peminjaman"
6. **Aktivitas Terakhir** — Timeline activity feed: pin merah (disetujui), pin biru (terlambat), status perubahan. Menampilkan nama alat, kode peminjaman, hari/tanggal/jam real-time.
7. **Notifikasi Keranjang** — Alert kuning jika ada item di keranjang dengan link ke katalog.
8. **CTA Card** — Card gradien navy "Butuh alat tambahan?" dengan teks deskripsi dan tombol "Jelajahi Katalog" biru.

### File yang Diubah

| File | Perubahan |
|------|-----------|
| `resources/views/mahasiswa/dashboard/index.blade.php` | Redesign total: +header-search, +title case nama, +peminjaman aktif cards dgn gambar, +aktivitas terakhir timeline, +cart notif, +CTA card |
| `resources/views/dosen/dashboard/index.blade.php` | Redesign total: sama dengan mahasiswa, beda badge purple & route dosen |
| `app/Http/Controllers/Mahasiswa/DashboardController.php` | +$activeBorrowings (collection), +$recentActivity, +$userName title case, +compact update |
| `app/Http/Controllers/Dosen/DashboardController.php` | +$activeBorrowings (collection), +$recentActivity, +$userName title case, +compact update |

---

## 🆕 30 Juni 2026 — Perbaikan Halaman Login (Icon Mata & Layout)

### Perubahan
1. **Icon Mata** — Icon gembok kiri di field password dihapus, hanya menyisakan icon mata (kanan) saja. Ukuran SVG 16x16, `stroke-width="1.5"`, tombol 32x32 dengan `border-radius: 8px` dan efek hover background abu.
2. **Padding Input** — Password input diubah paddingnya jadi `padding:10px 48px 10px 12px` (tanpa left padding untuk icon).
3. **Fix Label** — Nested `<label>` pada "Ingat saya" diganti jadi `<span>`.
4. **Toggle Function** — SVG eye-open/eye-off diperbarui ukuran dan stroke konsisten.

### File yang Diubah

| File | Perubahan |
|------|-----------|
| `resources/views/auth/login.blade.php` | Hapus icon gembok di password field; update CSS `.toggle-pw` (ukuran 32x32, border-radius, hover); perbaiki padding input password; fix nested label; update SVG di JS toggle |

---

## 🆕 30 Juni 2026 — Dashboard 2 Kolom (Peminjaman Aktif & Aktivitas Terakhir Berdampingan)

### Perubahan
1. **Layout 2 Kolom** — "Peminjaman Aktif" (kiri) dan "Aktivitas Terakhir" (kanan) kini berdampingan dalam grid `1fr 1fr`, bukan vertikal.
2. **CTA & Cart Notif** — Tetap di bawah kedua kolom.

### File yang Diubah

| File | Perubahan |
|------|-----------|
| `resources/views/mahasiswa/dashboard/index.blade.php` | Bungkus Peminjaman Aktif & Aktivitas Terakhir dalam grid 2 kolom |
| `resources/views/dosen/dashboard/index.blade.php` | Sama dengan mahasiswa |
