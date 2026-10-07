<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'logout') {
    session_destroy();
    header("Location: index.php?page=login");
    exit();
}

if (!isset($_SESSION['user_id']) && $page !== 'login') {
    header("Location: index.php?page=login");
    exit();
}

$alertMessage = '';

if ($page === 'login') {
    if (isset($_POST['login_submit'])) {
        if (!$pdo) {
            $alertMessage = "Koneksi ke Server SIMRS Khanza terputus / offline.";
        } else {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            $stmt = $pdo->prepare("SELECT * FROM rspm_otorisasi_users WHERE username = ?");
            $stmt->execute(array($username));
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['kd_dokter'] = isset($user['kd_dokter']) ? $user['kd_dokter'] : '';

                header("Location: index.php?page=dashboard");
                exit();
            } else {
                $alertMessage = "Username atau password salah!";
            }
        }
    }
    
    require_once __DIR__ . '/views/login.php';
    exit();
}

if (isset($_POST['ajax_update_otorisasi']) && $_POST['ajax_update_otorisasi'] == '1') {
    header('Content-Type: application/json');
    if (!$pdo) {
        echo json_encode(array('status' => 'error', 'message' => 'Database SIMRS Khanza (SIK) tidak terhubung.'));
        exit();
    }

    $mode = isset($_POST['mode']) ? $_POST['mode'] : 'batch_package';
    $no_rawat = isset($_POST['no_rawat']) ? $_POST['no_rawat'] : '';
    $tgl_periksa = isset($_POST['tgl_periksa']) ? $_POST['tgl_periksa'] : '';
    $jam = isset($_POST['jam']) ? $_POST['jam'] : '';

    // Fallback akun aktif jika periksa_lab.kd_dokter kosong
    $fallbackDokter = !empty($_SESSION['kd_dokter']) ? $_SESSION['kd_dokter'] : $_SESSION['username'];

    try {
        if ($mode === 'master') {
            $stts_otorisasi = isset($_POST['stts_otorisasi']) ? $_POST['stts_otorisasi'] : 'Valid';
            $tglOto = ($stts_otorisasi === 'Valid') ? date('Y-m-d H:i:s') : null;

            if ($stts_otorisasi === 'Valid') {
                // Update Otorisasi Master menggunakan Kode Dokter PJ Lab dari periksa_lab
                $stmtUp = $pdo->prepare("UPDATE rspm_otorisasi r 
                    LEFT JOIN periksa_lab pl 
                        ON r.no_rawat = pl.no_rawat 
                       AND r.kd_jenis_prw = pl.kd_jenis_prw 
                       AND r.tgl_periksa = pl.tgl_periksa 
                       AND r.jam = pl.jam 
                    SET r.stts_otorisasi = ?, 
                        r.tgl_otorisasi = ?, 
                        r.kd_dokter_sppk = COALESCE(NULLIF(pl.kd_dokter, ''), ?)
                    WHERE r.no_rawat = ? AND r.tgl_periksa = ? AND r.jam = ?");
                $stmtUp->execute(array($stts_otorisasi, $tglOto, $fallbackDokter, $no_rawat, $tgl_periksa, $jam));
            } else {
                // Batal Otorisasi Master (Reset)
                $stmtUp = $pdo->prepare("UPDATE rspm_otorisasi 
                    SET stts_otorisasi = ?, tgl_otorisasi = NULL, kd_dokter_sppk = NULL
                    WHERE no_rawat = ? AND tgl_periksa = ? AND jam = ?");
                $stmtUp->execute(array($stts_otorisasi, $no_rawat, $tgl_periksa, $jam));
            }

            echo json_encode(array('status' => 'success', 'message' => 'Status otorisasi master pasien berhasil diperbarui.'));
            exit();
        } elseif ($mode === 'batch_package') {
            $kd_jenis_prw = isset($_POST['kd_jenis_prw']) ? $_POST['kd_jenis_prw'] : '';
            $rawItems = isset($_POST['items']) ? $_POST['items'] : '[]';
            $items = json_decode($rawItems, true);

            // Ambil Kode Dokter PJ Pemeriksaan khusus paket ini dari periksa_lab
            $stmtPj = $pdo->prepare("SELECT kd_dokter FROM periksa_lab WHERE no_rawat = ? AND kd_jenis_prw = ? AND tgl_periksa = ? AND jam = ? LIMIT 1");
            $stmtPj->execute(array($no_rawat, $kd_jenis_prw, $tgl_periksa, $jam));
            $pjDokter = $stmtPj->fetchColumn();
            
            // Gunakan Dokter PJ Pemeriksaan jika tersedia, atau fallback ke user login
            $dokterOtoTarget = (!empty($pjDokter)) ? $pjDokter : $fallbackDokter;

            if (is_array($items)) {
                foreach ($items as $item) {
                    $id_template = isset($item['id_template']) ? trim($item['id_template']) : '';
                    $is_checked = isset($item['is_checked']) && $item['is_checked'] == 1;
                    $catatan = isset($item['catatan']) ? trim($item['catatan']) : '';

                    $stts = $is_checked ? 'Valid' : 'Pending';
                    $tglOto = $is_checked ? date('Y-m-d H:i:s') : null;
                    $dokterOto = $is_checked ? $dokterOtoTarget : null;

                    $stmtUp = $pdo->prepare("UPDATE rspm_otorisasi 
                        SET stts_otorisasi = ?, tgl_otorisasi = ?, kd_dokter_sppk = ?, catatan_otorisasi = ?
                        WHERE no_rawat = ? AND tgl_periksa = ? AND jam = ? AND kd_jenis_prw = ? AND id_template = ?");
                    $stmtUp->execute(array($stts, $tglOto, $dokterOto, $catatan, $no_rawat, $tgl_periksa, $jam, $kd_jenis_prw, $id_template));
                }
            }

            echo json_encode(array('status' => 'success', 'message' => 'Otorisasi paket berhasil disimpan.'));
            exit();
        }
    } catch (PDOException $e) {
        echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
        exit();
    }
}

if ($page === 'pengaturan_login') {
    if (isset($_POST['update_own_password'])) {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];

        $stmt = $pdo->prepare("SELECT password FROM rspm_otorisasi_users WHERE id = ?");
        $stmt->execute(array($_SESSION['user_id']));
        $currUser = $stmt->fetch();

        if ($currUser && password_verify($old_password, $currUser['password'])) {
            $new_hash = password_hash($new_password, PASSWORD_BCRYPT);
            $update = $pdo->prepare("UPDATE rspm_otorisasi_users SET password = ? WHERE id = ?");
            $update->execute(array($new_hash, $_SESSION['user_id']));
            $alertMessage = "Password berhasil diperbarui!";
        } else {
            $alertMessage = "Password saat ini tidak cocok!";
        }
    }

    if (isset($_POST['save_user']) && $_SESSION['role'] === 'admin') {
        $user_id = trim($_POST['user_id']);
        $nama_lengkap = trim($_POST['nama_lengkap']);
        $username = trim($_POST['username']);
        $role = trim($_POST['role']);
        $password = trim($_POST['password']);
        $kd_dokter = trim($_POST['kd_dokter']);

        if (!empty($user_id)) {
            if (!empty($password)) {
                $hashPass = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("UPDATE rspm_otorisasi_users SET nama_lengkap = ?, username = ?, role = ?, kd_dokter = ?, password = ? WHERE id = ?");
                $stmt->execute(array($nama_lengkap, $username, $role, $kd_dokter, $hashPass, $user_id));
            } else {
                $stmt = $pdo->prepare("UPDATE rspm_otorisasi_users SET nama_lengkap = ?, username = ?, role = ?, kd_dokter = ? WHERE id = ?");
                $stmt->execute(array($nama_lengkap, $username, $role, $kd_dokter, $user_id));
            }
            $alertMessage = "Data pengguna berhasil diperbarui!";
        } else {
            $checkUser = $pdo->prepare("SELECT COUNT(*) FROM rspm_otorisasi_users WHERE username = ?");
            $checkUser->execute(array($username));
            if ($checkUser->fetchColumn() > 0) {
                $alertMessage = "Username '$username' sudah terdaftar!";
            } else {
                $hashPass = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO rspm_otorisasi_users (nama_lengkap, username, role, kd_dokter, password) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute(array($nama_lengkap, $username, $role, $kd_dokter, $hashPass));
                $alertMessage = "Pengguna baru berhasil ditambahkan!";
            }
        }
    }

    if (isset($_POST['delete_user']) && $_SESSION['role'] === 'admin') {
        $user_id = $_POST['user_id'];
        if ($user_id != $_SESSION['user_id']) {
            $stmt = $pdo->prepare("DELETE FROM rspm_otorisasi_users WHERE id = ?");
            $stmt->execute(array($user_id));
            $alertMessage = "Pengguna berhasil dihapus!";
        } else {
            $alertMessage = "Anda tidak dapat menghapus akun Anda sendiri!";
        }
    }
}

$currentPage = $page;
require_once __DIR__ . '/header.php';
echo '<div class="flex flex-1 min-h-screen relative overflow-x-hidden">';
require_once __DIR__ . '/sidebar.php';

echo '<main class="flex-1 p-4 sm:p-6 lg:p-8 bg-slate-50 min-w-0 overflow-y-auto">';

if ($page === 'dashboard') {
    $totalExamToday = 0;
    $totalPendingToday = 0;
    $totalValidToday = 0;
    $totalCriticalToday = 0;
    $totalHoldToday = 0;
    $totalResampleToday = 0;
    $myAuthCountToday = 0;
    $recentCriticals = array();
    $recentAuths = array();
    $totalUsers = 0;

    if ($pdo) {
        try {
            $today = date('Y-m-d');
            $totalUsers = $pdo->query("SELECT COUNT(*) FROM rspm_otorisasi_users")->fetchColumn();
            
            // Stats pasien lab hari ini
            $totalExamToday = $pdo->query("SELECT COUNT(DISTINCT no_rawat) FROM rspm_otorisasi WHERE tgl_periksa = '$today'")->fetchColumn();
            $totalPendingToday = $pdo->query("SELECT COUNT(DISTINCT no_rawat) FROM rspm_otorisasi WHERE tgl_periksa = '$today' AND stts_otorisasi = 'Pending'")->fetchColumn();
            $totalValidToday = $pdo->query("SELECT COUNT(DISTINCT no_rawat) FROM rspm_otorisasi WHERE tgl_periksa = '$today' AND stts_otorisasi = 'Valid'")->fetchColumn();
            $totalHoldToday = $pdo->query("SELECT COUNT(DISTINCT no_rawat) FROM rspm_otorisasi WHERE tgl_periksa = '$today' AND stts_otorisasi = 'Hold'")->fetchColumn();
            $totalResampleToday = $pdo->query("SELECT COUNT(DISTINCT no_rawat) FROM rspm_otorisasi WHERE tgl_periksa = '$today' AND stts_otorisasi = 'Re-sample'")->fetchColumn();
            
            // Total Pasien bernilai Kritis hari ini
            $totalCriticalToday = $pdo->query("SELECT COUNT(DISTINCT no_rawat) FROM rspm_otorisasi WHERE tgl_periksa = '$today' AND (LOWER(keterangan) LIKE '%kritis%' OR LOWER(keterangan) LIKE '%nilai kritis%')")->fetchColumn();

            // Total otorisasi yang diselesaikan oleh Sp.PK aktif hari ini
            $activeKdDokter = !empty($_SESSION['kd_dokter']) ? $_SESSION['kd_dokter'] : $_SESSION['username'];
            $stmtMy = $pdo->prepare("SELECT COUNT(DISTINCT no_rawat) FROM rspm_otorisasi WHERE DATE(tgl_otorisasi) = ? AND kd_dokter_sppk = ?");
            $stmtMy->execute(array($today, $activeKdDokter));
            $myAuthCountToday = $stmtMy->fetchColumn();

            // List 5 Pasien Kritis Terbaru Hari Ini
            $stmtCrit = $pdo->prepare("SELECT DISTINCT d.no_rawat, p.nm_pasien, r.no_rkm_medis, pol.nm_poli, d.jam, d.stts_otorisasi
                FROM rspm_otorisasi d
                LEFT JOIN reg_periksa r ON d.no_rawat = r.no_rawat
                LEFT JOIN pasien p ON r.no_rkm_medis = p.no_rkm_medis
                LEFT JOIN poliklinik pol ON r.kd_poli = pol.kd_poli
                WHERE d.tgl_periksa = ? AND (LOWER(d.keterangan) LIKE '%kritis%' OR LOWER(d.keterangan) LIKE '%nilai kritis%')
                ORDER BY d.jam DESC
                LIMIT 5");
            $stmtCrit->execute(array($today));
            $recentCriticals = $stmtCrit->fetchAll();

            // List 5 Otorisasi Selesai Terbaru Hari Ini
            $stmtAuth = $pdo->prepare("SELECT DISTINCT d.no_rawat, p.nm_pasien, r.no_rkm_medis, d.tgl_otorisasi, dok.nm_dokter AS nm_dokter_sppk
                FROM rspm_otorisasi d
                LEFT JOIN reg_periksa r ON d.no_rawat = r.no_rawat
                LEFT JOIN pasien p ON r.no_rkm_medis = p.no_rkm_medis
                LEFT JOIN dokter dok ON d.kd_dokter_sppk = dok.kd_dokter
                WHERE d.stts_otorisasi = 'Valid' AND d.tgl_otorisasi IS NOT NULL
                ORDER BY d.tgl_otorisasi DESC
                LIMIT 5");
            $stmtAuth->execute();
            $recentAuths = $stmtAuth->fetchAll();
        } catch (PDOException $e) {
            // Silence query error
        }
    }

    require_once __DIR__ . '/views/dashboard.php';
} elseif ($page === 'pemeriksaan_lab') {
    $tgl_awal = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-d');
    $tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : '';
    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
    $stts_filter = isset($_GET['stts_filter']) ? trim($_GET['stts_filter']) : '';

    $exams_sik = array();
    $sik_error = null;

    if ($pdo) {
        try {
            // Query JOIN utama termasuk periksa_lab & dokter (dok_pj) untuk Dokter PJ Lab
            $sql = "SELECT d.no_rawat, d.kd_jenis_prw, d.tgl_periksa, d.jam, d.id_template, d.nilai, d.nilai_rujukan, d.keterangan,
                           d.stts_otorisasi, d.tgl_otorisasi, d.kd_dokter_sppk, d.catatan_otorisasi,
                           j.nm_perawatan,
                           t.Pemeriksaan AS nama_template,
                           p.nm_pasien, r.no_rkm_medis,
                           pol.nm_poli,
                           dok.nm_dokter AS nm_dokter_pengirim,
                           dok_sppk.nm_dokter AS nm_dokter_sppk,
                           pl.kd_dokter AS kd_dokter_pj_lab,
                           dok_pj.nm_dokter AS nm_dokter_pj_lab
                    FROM rspm_otorisasi d
                    LEFT JOIN jns_perawatan_lab j ON d.kd_jenis_prw = j.kd_jenis_prw
                    LEFT JOIN template_laboratorium t ON d.id_template = t.id_template
                    LEFT JOIN reg_periksa r ON d.no_rawat = r.no_rawat
                    LEFT JOIN pasien p ON r.no_rkm_medis = p.no_rkm_medis
                    LEFT JOIN poliklinik pol ON r.kd_poli = pol.kd_poli
                    LEFT JOIN dokter dok ON r.kd_dokter = dok.kd_dokter
                    LEFT JOIN dokter dok_sppk ON d.kd_dokter_sppk = dok_sppk.kd_dokter
                    LEFT JOIN periksa_lab pl ON d.no_rawat = pl.no_rawat AND d.kd_jenis_prw = pl.kd_jenis_prw AND d.tgl_periksa = pl.tgl_periksa AND d.jam = pl.jam
                    LEFT JOIN dokter dok_pj ON pl.kd_dokter = dok_pj.kd_dokter
                    WHERE 1=1";
            
            $params = array();

            if (!empty($tgl_awal) && !empty($tgl_akhir)) {
                $sql .= " AND d.tgl_periksa BETWEEN ? AND ?";
                $params[] = $tgl_awal;
                $params[] = $tgl_akhir;
            } elseif (!empty($tgl_awal)) {
                $sql .= " AND d.tgl_periksa = ?";
                $params[] = $tgl_awal;
            }

            if (!empty($stts_filter)) {
                $sql .= " AND d.stts_otorisasi = ?";
                $params[] = $stts_filter;
            }

            if (!empty($keyword)) {
                $sql .= " AND (d.no_rawat LIKE ? OR p.nm_pasien LIKE ? OR r.no_rkm_medis LIKE ? OR j.nm_perawatan LIKE ? OR t.Pemeriksaan LIKE ? OR d.keterangan LIKE ?)";
                $keyParam = '%' . $keyword . '%';
                $params[] = $keyParam;
                $params[] = $keyParam;
                $params[] = $keyParam;
                $params[] = $keyParam;
                $params[] = $keyParam;
                $params[] = $keyParam;
            }

            $sql .= " ORDER BY d.tgl_periksa DESC, d.jam DESC, d.no_rawat ASC, d.kd_jenis_prw ASC";

            $stmtSik = $pdo->prepare($sql);
            $stmtSik->execute($params);
            $exams_sik = $stmtSik->fetchAll();
        } catch (PDOException $e) {
            $sik_error = "Error Query SIMRS Khanza: " . $e->getMessage();
        }
    } else {
        $sik_error = "Database SIK SIMRS Khanza belum terhubung atau belum di-konfigurasi di config.php.";
    }

    $grouped_patients = array();
    foreach ($exams_sik as $row) {
        $noRawat = $row['no_rawat'];
        $kdJenis = $row['kd_jenis_prw'];

        if (!isset($grouped_patients[$noRawat])) {
            $grouped_patients[$noRawat] = array(
                'no_rawat' => $noRawat,
                'nm_pasien' => !empty($row['nm_pasien']) ? $row['nm_pasien'] : 'Pasien Tanpa Nama',
                'no_rkm_medis' => !empty($row['no_rkm_medis']) ? $row['no_rkm_medis'] : '-',
                'nm_poli' => !empty($row['nm_poli']) ? $row['nm_poli'] : '-',
                'nm_dokter' => !empty($row['nm_dokter_pengirim']) ? $row['nm_dokter_pengirim'] : '-',
                'kd_dokter_pj_lab' => !empty($row['kd_dokter_pj_lab']) ? $row['kd_dokter_pj_lab'] : '',
                'nm_dokter_pj_lab' => !empty($row['nm_dokter_pj_lab']) ? $row['nm_dokter_pj_lab'] : '-',
                'tgl_periksa' => $row['tgl_periksa'],
                'jam' => $row['jam'],
                'perawatan_list' => array(),
                'total_item' => 0,
                'valid_count' => 0,
                'critical_count' => 0
            );
        }

        if (!isset($grouped_patients[$noRawat]['perawatan_list'][$kdJenis])) {
            $grouped_patients[$noRawat]['perawatan_list'][$kdJenis] = array(
                'kd_jenis_prw' => $kdJenis,
                'nm_perawatan' => !empty($row['nm_perawatan']) ? $row['nm_perawatan'] : $kdJenis,
                'items' => array(),
                'group_total' => 0,
                'group_valid' => 0
            );
        }

        $grouped_patients[$noRawat]['perawatan_list'][$kdJenis]['items'][] = $row;
        $grouped_patients[$noRawat]['perawatan_list'][$kdJenis]['group_total']++;
        $grouped_patients[$noRawat]['total_item']++;

        if ($row['stts_otorisasi'] === 'Valid') {
            $grouped_patients[$noRawat]['perawatan_list'][$kdJenis]['group_valid']++;
            $grouped_patients[$noRawat]['valid_count']++;
        }

        if (!empty($row['keterangan']) && (stripos($row['keterangan'], 'nilai kritis') !== false || stripos($row['keterangan'], 'kritis') !== false)) {
            $grouped_patients[$noRawat]['critical_count']++;
        }
    }

    require_once __DIR__ . '/views/pemeriksaan_lab.php';
} elseif ($page === 'pengaturan_login') {
    $stmtUsers = $pdo->query("SELECT * FROM rspm_otorisasi_users ORDER BY id DESC");
    $users = $stmtUsers->fetchAll();

    $doctors_khanza = array();
    if ($pdo) {
        try {
            $stmtDok = $pdo->query("SELECT kd_dokter, nm_dokter FROM dokter WHERE status = '1' ORDER BY nm_dokter ASC");
            $doctors_khanza = $stmtDok->fetchAll();
        } catch (PDOException $e) {
            // Silence SIK doctor query error
        }
    }

    require_once __DIR__ . '/views/pengaturan_login.php';
} else {
    echo '<div class="p-6 bg-white rounded-2xl border shadow-sm"><h2 class="text-lg font-bold text-slate-800">Halaman tidak ditemukan!</h2></div>';
}

echo '</main></div>';
require_once __DIR__ . '/footer.php';
?>
