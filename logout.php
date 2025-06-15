<?php
// Mulai sesi
session_start();

// Hapus semua variabel sesi
$_SESSION = array();

// Hapus cookie sesi jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    // Set cookie sesi dengan waktu kadaluarsa di masa lalu 
    // cookies disimpan selama 42000 detik (sekitar 11 jam 40 menit)
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hancurkan sesi
session_destroy();

// Redirect ke halaman login
header("Location: index.php");
exit();
?>