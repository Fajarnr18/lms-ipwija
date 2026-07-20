# LMS Universitas IPWIJA 🎓

Aplikasi web Sistem Manajemen Pembelajaran (LMS) dan Peminjaman Alat untuk Universitas IPWIJA. Aplikasi ini sudah dikonfigurasi penuh agar dapat berjalan secara instan menggunakan Docker.

## Persyaratan Sistem
Sebelum menjalankan aplikasi, pastikan komputer/laptop Anda sudah ter-install:
- **Docker Desktop** (Pastikan sudah dalam keadaan berjalan / *running* dengan indikator hijau).
- **Git Bash / Terminal / PowerShell**.
- **Aplikasi DBeaver** (Opsional, untuk melihat dan mengelola *database*).

---

## Cara Menjalankan Aplikasi (Localhost)

1. **Buka Terminal di Folder Proyek**
   Buka folder proyek ini (`www`) di File Explorer, lalu klik kanan dan pilih **"Open in Terminal"** atau buka melalui VS Code Terminal.

2. **Jalankan Docker Compose**
   Ketikkan perintah berikut di terminal untuk menghidupkan seluruh sistem (Server Web Laravel & Database MySQL) secara otomatis di latar belakang:
   ```bash
   docker-compose up -d
   ```
   *(Tunggu beberapa saat hingga muncul status `Started` untuk semua kontainer).*

3. **Buka Aplikasi di Browser**
   Setelah sistem menyala, buka browser (Google Chrome / Edge) lalu kunjungi alamat berikut:
   👉 **[http://localhost:8000](http://localhost:8000)**

---

## Informasi Database (MySQL)

Seluruh *database* berjalan secara otomatis bersama aplikasi dan datanya disimpan dengan aman di dalam folder `mysql_data` yang ada di proyek ini.

Untuk melihat isi tabel, riwayat peminjaman, dan mengelola *database*, Anda bisa menggunakan aplikasi **DBeaver** dengan detail koneksi berikut:
- **Host:** `localhost`
- **Port:** `3306`
- **Database:** `db_UAS`
- **Username:** `lms_user`
- **Password:** `lms_password`

*(Catatan: Jangan mengubah isi file di dalam folder `mysql_data` secara manual melalui File Explorer, cukup kelola melalui DBeaver).*

---

## Cara Mematikan Aplikasi

Jika Anda sudah selesai menggunakan aplikasi dan ingin mematikan sistem agar tidak membebani RAM komputer, jalankan perintah ini di terminal:
```bash
docker-compose down
```
Data Anda akan tetap aman dan akan dimuat kembali secara otomatis saat Anda menjalankan `docker-compose up -d` di lain waktu.

---

## Troubleshooting (Jika Terjadi Masalah)
- **Halaman Web Terus Loading / Stuck:** Biasanya terjadi karena *port* bentrok atau sistem *file sharing* (VirtioFS) Docker sedang macet. Cukup *Restart* Docker Desktop Anda, lalu muat ulang halaman.
- **Port 3306 atau 8000 Bentrok:** Pastikan Anda mematikan aplikasi XAMPP/Laragon/MySQL lokal Anda terlebih dahulu, karena aplikasi ini membutuhkan *port* asli tersebut agar berjalan mulus.
