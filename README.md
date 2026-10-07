🧪 Zulabs - Modul Otorisasi Hasil Laboratorium Sp.PK

Zulabs adalah modul peninjauan dan otorisasi hasil laboratorium terintegrasi dengan SIMRS Khanza (sik), dirancang khusus untuk memenuhi kebutuhan verifikasi klinis Dokter Spesialis Patologi Klinik (Sp.PK).
Sistem ini mempermudah proses evaluasi hasil pemeriksaan laboratorium secara cepat, terstruktur, serta mendukung otorisasi individual per parameter (micro-level), otorisasi paket tes, maupun otorisasi massal per pasien (master level).

🌟 Fitur Utama :
**Auto Dokter PJ Lab Mode**: 
Otorisasi secara otomatis dicatat berdasarkan Dokter PJ Pemeriksaan (periksa_lab.kd_dokter), menjamin keabsahan hukum rekam medis meskipun terjadi pertukaran shift jaga antar Dokter Sp.PK.

**Otorisasi & Un-Otorisasi Fleksibel:**
1. Level Micro: Otorisasi/Batal Otorisasi per parameter dengan mencentang/menghapus centang (un-checked) pada checkbox.
2. Level Paket: Tombol Simpan Otorisasi Paket serta fitur Pilih Semua / Batal Semua.
3. Level Master: Tombol Otorisasi SEMUA Parameter dan Batalkan Otorisasi SEMUA untuk pemrosesan seluruh parameter hasil pasien secara instan.

**Redline Row & Deteksi Nilai Kritis:**
Otomatis menandai baris pemeriksaan dengan latar belakang merah (Redline Row) apabila terdapat frasa "nilai kritis" atau "kritis" pada kolom keterangan.
Banner Alert dan Badge berkedip animasi (pulse) di Dashboard dan Header Kartu Pasien sebagai pengingat penanganan panic values.

**Dashboard Statistik & Audit Trail:**
Ringkasan statistik hari ini: 
1. Total Pasien, Menunggu Review (Pending), Ter-Otorisasi (Valid), dan Nilai Kritis.
2. Progress bar pencapaian otorisasi harian.
3. Widget produktivitas Sp.PK dan Recent Audit Log Feed untuk pemantauan jejak verifikasi.

 **Pemetaan User & Kode Dokter Khanza:** 
 Pemetaan akun login sistem dengan master tabel dokter SIMRS Khanza (kd_dokter).

 **Antarmuka Modern & Responsif:** 
 Dibangun menggunakan Tailwind CSS dengan tampilan berbasis Accordion Card yang nyaman diakses dari PC desktop, tablet, maupun smartphone.

🛠️ Teknologi & Arsitektur
Backend: PHP Native (PDO MySQL / SQLite Fallback)
Frontend: HTML5, Tailwind CSS (CDN), FontAwesome 6 Icons

Database Engine:
1. SIMRS Khanza DB (test-simrs / sik): Mengelola data pasien, pendaftaran, dan otorisasi (rspm_otorisasi, periksa_lab, reg_periksa, pasien, poliklinik, dokter).
2. Database Lokal (db_lab_modular): Mengelola autentikasi & pemetaan pengguna (users).
3. Komunisi Data: Native Vanilla JavaScript Fetch AJAX (Tanpa dependensi jQuery).

📁 Struktur Folder Proyek

zulabs/
│
├── config.php                  # Konfigurasi PDO DB SIM LAB & SIMRS Khanza (Server LAN 192.168.5.100)
├── index.php                   # Controller Utama & Router (Dashboard, Otorisasi AJAX, Authentication)
├── header.php                  # Template Header HTML, CDN Tailwind CSS, & FontAwesome
├── sidebar.php                 # Template Navigasi Sidebar Samping (Responsif Drawer)
├── footer.php                  # Template Footer HTML & Script Modal Global
├── PANDUAN_GITHUB.md           # Panduan alur pengunggahan ke repository GitHub
│
├── views/                      # Subfolder Antarmuka (Views)
│   ├── dashboard.php           # Tampilan Dashboard Statistik & Audit Log
│   ├── pemeriksaan_lab.php     # Tampilan Utama Otorisasi Hasil Lab Sp.PK (Accordion & Checkbox)
│   ├── login.php               # Tampilan Form Login Multiuser
│   └── pengaturan_login.php    # Tampilan Ubah Password & Manajemen User (Admin)
│
├── alter_sik_database.sql      # Script Alter Table pada DB SIK Khanza (rspm_otorisasi)
├── alter_user_table.sql        # Script Alter Table pada DB Lokal (users.kd_dokter)
└── db_lab_modular.sql          # Script Skema Database Lokal User Management


🗄️ Penyiapan Database & Script SQL

1. Database SIMRS Khanza (test-simrs / sik)
Jalankan perintah SQL berikut pada database SIMRS Khanza Anda untuk menambahkan kolom pendukung otorisasi Sp.PK pada tabel rspm_otorisasi:

USE `test-simrs`;
ALTER TABLE `rspm_otorisasi`
  ADD COLUMN `stts_otorisasi` ENUM('Pending', 'Valid', 'Hold', 'Re-sample') NOT NULL DEFAULT 'Pending' AFTER `keterangan`,
  ADD COLUMN `tgl_otorisasi` DATETIME NULL DEFAULT NULL AFTER `stts_otorisasi`,
  ADD COLUMN `kd_dokter_sppk` VARCHAR(20) NULL DEFAULT NULL AFTER `tgl_otorisasi`,
  ADD COLUMN `catatan_otorisasi` VARCHAR(255) NULL DEFAULT NULL AFTER `kd_dokter_sppk`;

ALTER TABLE `rspm_otorisasi`
  ADD INDEX `idx_stts_otorisasi` (`stts_otorisasi`),
  ADD INDEX `idx_tgl_stts_otorisasi` (`tgl_periksa`, `stts_otorisasi`);

2. Database Lokal SIM LAB (db_lab_modular)
Jalankan script untuk membuat tabel users dan penambahan kolom kd_dokter:

CREATE DATABASE IF NOT EXISTS `db_lab_modular` DEFAULT CHARACTER SET utf8mb4;
USE `db_lab_modular`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `role` ENUM('admin', 'petugas', 'dokter') NOT NULL DEFAULT 'petugas',
  `kd_dokter` VARCHAR(20) NULL DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_kd_dokter` (`kd_dokter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


🚀 Langkah Instalasi & Penggunaan

Clone Repository:
git clone https://github.com/USERNAME/zulabs.git
cd zulabs


Konfigurasi Database (config.php):
Buka file config.php dan sesuaikan kredensial koneksi server SIMRS Khanza serta database lokal Anda:

// Server Eksternal SIMRS Khanza (SIK)
define('DB_SIK_HOST', 'XXX');
define('DB_SIK_PORT', 'XXX');
define('DB_SIK_USER', 'XXXX');
define('DB_SIK_PASS', 'XXX');
define('DB_SIK_NAME', 'XXX');


Login Pertama Kali (Default Seed):
Akses aplikasi via web browser (contoh: http://localhost/zulabs). Sistem secara otomatis akan membuat akun standar jika belum ada data:

Administrator: admin / admin123

Petugas Lab: petugas / petugas123

Pemetaan Akun Sp.PK:

Login menggunakan akun Admin.

Masuk ke menu Pengaturan Login.

Buat/Edit user Dokter Sp.PK lalu hubungkan dengan Kode Dokter Khanza (kd_dokter) dari dropdown yang tersedia.

🔒 Keamanan & Good Practices

File config.php yang memuat password asli server rumah sakit TIDAK BUKAN UNTUK DIPUBLIKASIKAN ke repository publik.

Gunakan berkas .gitignore untuk mencegah ter-upload nya file konfigurasi sensitif atau file SQLite lokal.

Disarankan untuk membuat repository ini dalam mode Private.

📝 Lisensi & Disclaimer
Aplikasi ini dikembangkan untuk penggunaan internal Laboratorium Rumah Sakit / Klinik terintegrasi SIMRS Khanza. Segela bentuk penyesuaian hak cipta dan kepemilikan rekam medis elektronik disesuaikan dengan regulasi instansi masing-masing.
© Zulabs. All rights reserved. Terintegrasi SIMRS Khanza.
