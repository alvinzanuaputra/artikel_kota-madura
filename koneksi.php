<?php
// Atur zona waktu ke Asia/Jakarta untuk waktu Indonesia
date_default_timezone_set('Asia/Jakarta');

// Konfigurasi database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'kota_madura';
$port = 3306;

// Membuat koneksi
try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;port=$port", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// Memulai session
session_start();

// Fungsi untuk mengecek apakah user sudah login
function isLoggedIn() {
    return isset($_SESSION['penulis_id']) && !empty($_SESSION['penulis_id']);
}

// Fungsi untuk redirect jika belum login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

// Fungsi untuk redirect jika sudah login
function requireLogout() {
    if (isLoggedIn()) {
        header('Location: index.php');
        exit();
    }
}

// Link Font Awesome: https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css

function generateFooter($categoryList = [], $isLoggedIn = false) {
    $shareScript = '
    <script>
        function shareOnFacebook() {
            const url = encodeURIComponent(window.location.href);
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, "_blank");
        }

        function shareOnTwitter() {
            const url = encodeURIComponent(window.location.href);
            const text = encodeURIComponent(document.title);
            window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, "_blank");
        }

        function shareOnWhatsApp() {
            const url = encodeURIComponent(window.location.href);
            const text = encodeURIComponent(document.title);
            window.open(`https://wa.me/?text=${text} ${url}`, "_blank");
        }
    </script>
    ';

    $quickNavLinks = $isLoggedIn ? [
        ['url' => 'index.php', 'icon' => 'home', 'label' => 'Beranda'],
        ['url' => 'tambah_artikel.php', 'icon' => 'plus-circle', 'label' => 'Tambah Artikel'],
        ['url' => 'dashboard.php', 'icon' => 'tachometer-alt', 'label' => 'Dashboard'],
        ['url' => '?logout=1', 'icon' => 'sign-out-alt', 'label' => 'Logout']
    ] : [
        ['url' => 'index.php', 'icon' => 'home', 'label' => 'Beranda'],
        ['url' => 'login.php', 'icon' => 'sign-in-alt', 'label' => 'Login'],
        ['url' => 'register.php', 'icon' => 'user-plus', 'label' => 'Daftar']
    ];

    $footer = '
    <!-- Footer -->
    <footer class="bg-yellow-800 text-white mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Navigasi Cepat -->
                <div>
                    <h4 class="text-lg font-bold mb-4">Navigasi Cepat</h4>
                    <ul class="space-y-2">';
    
    foreach ($quickNavLinks as $link) {
        $footer .= '
                        <li>
                            <a href="' . $link['url'] . '" class="hover:text-yellow-200 transition duration-300">
                                <i class="fas fa-' . $link['icon'] . ' mr-2"></i>' . $link['label'] . '
                            </a>
                        </li>';
    }

    $footer .= '
                    </ul>
                </div>

                <!-- Kategori -->
                <div>
                    <h4 class="text-lg font-bold mb-4">Kategori</h4>
                    <ul class="space-y-2">';
    
    if (!empty($categoryList)) {
        foreach ($categoryList as $category) {
            $footer .= '
                        <li>
                            <a href="index.php?kategori_id=' . $category['id'] . '" 
                               class="hover:text-yellow-200 transition duration-300">
                                <i class="fas fa-tag mr-2"></i>' . htmlspecialchars($category['nama']) . '
                            </a>
                        </li>';
        }
    }

    $footer .= '
                    </ul>
                </div>

                <!-- Kontak & Share -->
                <div>
                    <h4 class="text-lg font-bold mb-4">Hubungi Kami</h4>
                    <div class="space-y-2">
                        <p><i class="fas fa-envelope mr-2"></i>belaabel289@gmail.com</p>
                        <p><i class="fas fa-university mr-2"></i>Universitas Islam Negeri Malang</p>
                        
                        <div class="mt-4">
                            <h5 class="font-semibold mb-2">Bagikan Halaman</h5>
                            <div class="flex space-x-3">
                                <a href="#" onclick="shareOnFacebook()" class="text-white hover:text-yellow-200">
                                    <i class="fab fa-facebook-f text-2xl"></i>
                                </a>
                                <a href="#" onclick="shareOnTwitter()" class="text-white hover:text-yellow-200">
                                    <i class="fab fa-twitter text-2xl"></i>
                                </a>
                                <a href="#" onclick="shareOnWhatsApp()" class="text-white hover:text-yellow-200">
                                    <i class="fab fa-whatsapp text-2xl"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-yellow-700 mt-8 pt-6 text-center">
                <p>&copy; 2025 oleh Nabila Ilmiatus dari Universitas Islam Negeri Malang. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>
    ' . $shareScript . '
    ';

    return $footer;
}

// Fungsi untuk mengambil daftar kategori
function getKategori() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT id, nama FROM kategori ORDER BY nama ASC");
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        // Tangani error jika query gagal
        error_log("Gagal mengambil kategori: " . $e->getMessage());
        return [];
    }
}

?>