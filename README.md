
# Zulabs - ```Sistem Otorisasi Laboratorium SimRS Khanza```

## A. Membuat/Menambah tabel pada database khanza (sik)
### 1. Tabel ```rspm_otorisasi_user``` 
 
SQL :
```bash
CREATE TABLE IF NOT EXISTS `rspm_otorisasi_users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `role` ENUM('admin', 'petugas', 'dokter') NOT NULL DEFAULT 'petugas',
  `kd_dokter` VARCHAR(20) NULL DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_kd_dokter` (`kd_dokter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 2. Tabel ```rspm_otorisasi```
- Pertama, kita harus melakukan kloning tabel ```detail_periksa_lab``` ke ```rspm_otorisasi``` dengan data 1 minggu untuk keperluan uji coba
- Kemudian tabel ini di setting agar secara otomatis bila ada data baru di tabel ```detail_periksa_lab``` maka ditabel ```rspm_otorisasi``` juga ada data tersebut.
- Isi nya sama dengan tabel ```detail_periksa_lab```  namun ada beberapa tambahan kolom. Untuk bagian ini saya serahkan ke temen2 IT untuk mengaturnya, karena saya tidak bisa mencoba penuh di database yg update.

#### contoh SQL penambahan kolom pada tabel ```rspm_otorisasi``` 
``` 
ALTER TABLE `rspm_otorisasi`
  ADD COLUMN `stts_otorisasi` ENUM('Pending', 'Valid', 'Hold', 'Re-sample') NOT NULL DEFAULT 'Pending' AFTER `keterangan`,
  ADD COLUMN `tgl_otorisasi` DATETIME NULL DEFAULT NULL AFTER `stts_otorisasi`,
  ADD COLUMN `kd_dokter_sppk` VARCHAR(20) NULL DEFAULT NULL AFTER `tgl_otorisasi`,
  ADD COLUMN `catatan_otorisasi` VARCHAR(255) NULL DEFAULT NULL AFTER `kd_dokter_sppk`;

ALTER TABLE `rspm_otorisasi`
  ADD INDEX `idx_stts_otorisasi` (`stts_otorisasi`),
  ADD INDEX `idx_tgl_stts_otorisasi` (`tgl_periksa`, `stts_otorisasi`);

```

## B. Instalasi dan konfigurasi
- Ada file yang harus di copas dan edit untuk konfigurasi sesuai database khanza (sik)
- Setelah itu selesai dan aplikasi dapat dicoba.

## C. Pengaturan di Simrs Khanza
- Penambahan kolom pada menu Periksa Lab (hasil lab yang sudah jadi) kolom waktu otorisasi per-parameter
- Apabila sudah terotorisasi semua, maka tanda tangan dokter spesialis patologi klinik pada hasil lab dapat di tampilkan, bila belum semua maka hasil laboratorium belum ada tanda tangan Dokter Spesialis Patologi Klinik
- Waktu otorisasi tidak perlu ditampilkan di hasil laboratorium
- Waktu selesai pemeriksaan tetap seperti itu, merupakan waktu hasil pemeriksaan di entry di simrs khanza

#### Bila ada yang tidak jelas bisa langsung whatsapp dokter zuhri


