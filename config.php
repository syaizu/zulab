<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Konfigurasi Database Tunggal SIMRS Khanza (Zulabs)
define('DB_HOST', '192.168.5.100');
define('DB_PORT', '3306');
define('DB_USER', 'test-simrs');
define('DB_PASS', 'Rsparu123-test');
define('DB_NAME', 'test-simrs');

$pdo = null;
$pdo_sik = null;

try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ));
    // Alias $pdo_sik tetap disediakan agar kompatibel
    $pdo_sik = $pdo;
} catch (PDOException $e) {
    $pdo = null;
    $pdo_sik = null;
}

// Auto Seed User Default pada tabel rspm_otorisasi_users
if ($pdo) {
    try {
        $checkUser = $pdo->query("SELECT COUNT(*) FROM rspm_otorisasi_users")->fetchColumn();
        if ($checkUser == 0) {
            $passAdmin = password_hash('admin123', PASSWORD_BCRYPT);
            $passPetugas = password_hash('petugas123', PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("INSERT INTO rspm_otorisasi_users (username, password, nama_lengkap, role) VALUES (?, ?, ?, ?)");
            $stmt->execute(array('admin', $passAdmin, 'Administrator Lab', 'admin'));
            $stmt->execute(array('petugas', $passPetugas, 'Petugas Laboratorium', 'petugas'));
        }
    } catch (PDOException $e) {
        // Abaikan jika tabel rspm_otorisasi_users belum dibuat
    }
}
