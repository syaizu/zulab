<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Konfigurasi Database Utama Lokal (SIM LAB)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_lab_modular');

// Konfigurasi Database Eksternal (SIK SIMRS Khanza Server)
define('DB_SIK_HOST', 'XXXX');
define('DB_SIK_PORT', 'XXXX');
define('DB_SIK_USER', 'XXXX');
define('DB_SIK_PASS', 'XXX');
define('DB_SIK_NAME', 'XXX');

$db_driver = 'mysql';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ));
} catch (PDOException $e) {
    // Fallback ke SQLite jika MySQL lokal belum dibuat
    $db_driver = 'sqlite';
    $sqliteFile = __DIR__ . '/lab_database.sqlite';
    $pdo = new PDO("sqlite:" . $sqliteFile, null, null, array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ));
}

$pdo_sik = null;
try {
    $dsn_sik = "mysql:host=" . DB_SIK_HOST . ";port=" . DB_SIK_PORT . ";dbname=" . DB_SIK_NAME . ";charset=utf8mb4";
    $pdo_sik = new PDO($dsn_sik, DB_SIK_USER, DB_SIK_PASS, array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ));
} catch (PDOException $e) {
    // Set null jika koneksi ke server Khanza gagal / offline
    $pdo_sik = null;
}

if ($db_driver === 'sqlite') {
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        nama_lengkap TEXT NOT NULL,
        role TEXT NOT NULL,
        kd_dokter TEXT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
} else {
    // Memastikan kolom kd_dokter ada pada tabel users MySQL lokal
    try {
        $checkCol = $pdo->query("SHOW COLUMNS FROM users LIKE 'kd_dokter'")->fetch();
        if (!$checkCol) {
            $pdo->exec("ALTER TABLE users ADD COLUMN kd_dokter VARCHAR(20) NULL DEFAULT NULL AFTER role");
        }
    } catch (PDOException $e) {
        // Abaikan jika tabel belum ada saat pertama instalasi
    }
}

try {
    $checkUser = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($checkUser == 0) {
        $passAdmin = password_hash('admin123', PASSWORD_BCRYPT);
        $passPetugas = password_hash('petugas123', PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("INSERT INTO users (username, password, nama_lengkap, role, kd_dokter) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(array('admin', $passAdmin, 'Administrator Lab', 'admin', null));
        $stmt->execute(array('petugas', $passPetugas, 'Petugas Laboratorium', 'petugas', null));
    }
} catch (PDOException $e) {
    // Abaikan error seed awal
}
