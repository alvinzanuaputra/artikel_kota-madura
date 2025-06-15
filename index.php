<?php
require_once 'koneksi.php';

// Ambil kategori untuk filter
$categoryList = [];
$sql_categories = "SELECT id, nama FROM kategori ORDER BY nama";
$stmt_categories = $pdo->prepare($sql_categories);
$stmt_categories->execute();
$categoryList = $stmt_categories->fetchAll();

// Untuk kompatibilitas dengan kode lama
$categories = $categoryList;

// Ambil data dari GET
$selectedCategory = isset($_GET['category']) ? intval($_GET['category']) : 0;
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

// Query ke tabel artikel dengan filter kategori dan pencarian
if ($selectedCategory > 0) {
    // Filter dengan kategori dan pencarian
    $sql = "SELECT 
                a.id,
                a.tanggal,
                a.judul,
                a.isi,
                a.gambar,
                p.nama as penulis_nama,
                GROUP_CONCAT(k.nama SEPARATOR ', ') as kategori_nama
            FROM artikel a
            LEFT JOIN artikel_penulis ap ON a.id = ap.id_artikel
            LEFT JOIN penulis p ON ap.id_penulis = p.id
            LEFT JOIN artikel_kategori ak ON a.id = ak.id_artikel
            LEFT JOIN kategori k ON ak.id_kategori = k.id
            WHERE (a.judul LIKE :keyword1 OR a.isi LIKE :keyword2) 
            AND a.id IN (
                SELECT id_artikel 
                FROM artikel_kategori 
                WHERE id_kategori = :category_id
            )
            GROUP BY a.id, a.tanggal, a.judul, a.isi, a.gambar, p.nama
            ORDER BY a.tanggal DESC, a.id DESC";

    $stmt = $pdo->prepare($sql);
    $search_param = "%" . $keyword . "%";
    $stmt->bindParam(':keyword1', $search_param, PDO::PARAM_STR);
    $stmt->bindParam(':keyword2', $search_param, PDO::PARAM_STR);
    $stmt->bindParam(':category_id', $selectedCategory, PDO::PARAM_INT);
} else {
    // Filter hanya dengan pencarian
    $sql = "SELECT 
                a.id,
                a.tanggal,
                a.judul,
                a.isi,
                a.gambar,
                p.nama as penulis_nama,
                GROUP_CONCAT(k.nama SEPARATOR ', ') as kategori_nama
            FROM artikel a
            LEFT JOIN artikel_penulis ap ON a.id = ap.id_artikel
            LEFT JOIN penulis p ON ap.id_penulis = p.id
            LEFT JOIN artikel_kategori ak ON a.id = ak.id_artikel
            LEFT JOIN kategori k ON ak.id_kategori = k.id
            WHERE a.judul LIKE :keyword1 OR a.isi LIKE :keyword2
            GROUP BY a.id, a.tanggal, a.judul, a.isi, a.gambar, p.nama
            ORDER BY a.tanggal DESC, a.id DESC";

    $stmt = $pdo->prepare($sql);
    $search_param = "%" . $keyword . "%";
    $stmt->bindParam(':keyword1', $search_param, PDO::PARAM_STR);
    $stmt->bindParam(':keyword2', $search_param, PDO::PARAM_STR);
}

$stmt->execute();
$articles = $stmt->fetchAll();

// Support untuk filter kategori lama (kategori_id)
if (isset($_GET['kategori_id']) && !empty($_GET['kategori_id']) && empty($selectedCategory)) {
    $kategori_id = (int)$_GET['kategori_id'];

    $sql_filtered = "SELECT 
                        a.id,
                        a.tanggal,
                        a.judul,
                        a.isi,
                        a.gambar,
                        p.nama as penulis_nama,
                        GROUP_CONCAT(k.nama SEPARATOR ', ') as kategori_nama
                    FROM artikel a
                    LEFT JOIN artikel_penulis ap ON a.id = ap.id_artikel
                    LEFT JOIN penulis p ON ap.id_penulis = p.id
                    LEFT JOIN artikel_kategori ak ON a.id = ak.id_artikel
                    LEFT JOIN kategori k ON ak.id_kategori = k.id
                    WHERE a.id IN (
                        SELECT id_artikel 
                        FROM artikel_kategori 
                        WHERE id_kategori = :kategori_id
                    )
                    GROUP BY a.id, a.tanggal, a.judul, a.isi, a.gambar, p.nama
                    ORDER BY a.tanggal DESC, a.id DESC";

    $stmt_filtered = $pdo->prepare($sql_filtered);
    $stmt_filtered->bindParam(':kategori_id', $kategori_id, PDO::PARAM_INT);
    $stmt_filtered->execute();
    $articles = $stmt_filtered->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kota Madura - Portal Artikel</title>
    <link href="./assets/logo/logo.png" type="image/png" rel="shortcut icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-yellow-50 to-yellow-100 min-h-screen">
    <!-- Header -->
    <header class="bg-gradient-to-r from-yellow-600 to-yellow-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-white">Kota Madura</h1>
                    <p class="text-yellow-100">Portal Artikel & Berita</p>
                </div>
                <div class="flex items-center space-x-4">
                    <?php if (isset($_SESSION['penulis_id'])): ?>
                        <a href="dashboard.php"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md transition duration-300 flex items-center justify-center">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                    <?php else: ?>
                        <a href="login.php"
                            class="bg-yellow-800 hover:bg-yellow-900 text-white px-4 py-2 rounded-md transition duration-300 flex items-center">
                            <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                        </a>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="bg-yellow-500 shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex space-x-8 py-4">
                <a href="index.php" class="text-white font-medium hover:text-yellow-200 transition duration-300"><i class="fas fa-home mr-2"></i>Beranda</a>
                <div class="relative group">
                    <div class="absolute hidden group-hover:block bg-white shadow-lg rounded-md mt-2 py-2 w-48 z-10">
                        <?php foreach ($categories as $category): ?>
                            <a href="index.php?kategori_id=<?php echo $category['id']; ?>"
                                class="block px-4 py-2 text-gray-800 hover:bg-yellow-100">
                                <?php echo htmlspecialchars($category['nama']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-yellow-800 mb-4">Artikel Terbaru</h2>
        </div>

        <!-- Form Pencarian dan Filter -->
        <div class="flex mb-6 text-gray-800 relative rounded-t-lg">
            <!-- Form Pencarian -->
            <form method="GET" action="index.php" class="flex-1 relative">
                <!-- Hidden field untuk mempertahankan filter kategori -->
                <?php if ($selectedCategory > 0): ?>
                    <input type="hidden" name="category" value="<?php echo $selectedCategory; ?>">
                <?php endif; ?>

                <input type="text" name="keyword" id="search-input" placeholder="Cari judul atau isi artikel..."
                    value="<?php echo htmlspecialchars($keyword); ?>"
                    class="w-full bg-white text-gray-800 flex-1 py-2 px-16 rounded-lg border border-yellow-500">
                <button type="submit" class="absolute left-2 p-2 font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="#000000"
                        viewBox="0 0 256 256">
                        <path
                            d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z">
                        </path>
                    </svg>
                </button>
                <button type="button" id="clear-search"
                    class="absolute right-1 top-0.5 py-2.5 mb-3 px-1 text-black hover:text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#000000"
                        viewBox="0 0 256 256">
                        <path
                            d="M205.66,194.34a8,8,0,0,1-11.32,11.32L128,139.31,61.66,205.66a8,8,0,0,1-11.32-11.32L116.69,128,50.34,61.66A8,8,0,0,1,61.66,50.34L128,116.69l66.34-66.35a8,8,0,0,1,11.32,11.32L139.31,128Z">
                        </path>
                    </svg>
                </button>
            </form>

            <!-- Form Filter -->
            <form method="GET" action="index.php" id="filter-form" class="ml-2 flex items-center">
                <!-- Hidden field untuk mempertahankan keyword -->
                <?php if (!empty($keyword)): ?>
                    <input type="hidden" name="keyword" value="<?php echo htmlspecialchars($keyword); ?>">
                <?php endif; ?>

                <select name="category" id="category-select"
                    class="px-2 py-2 rounded-l border border-yellow-500 bg-white text-gray-800"
                    onchange="this.form.submit()">
                    <option value="0">Semua Kategori</option>
                    <?php foreach ($categoryList as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php if ($selectedCategory == $cat['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($cat['nama']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Clear Filter Button inside the form -->
                <?php if (!empty($keyword) || $selectedCategory > 0): ?>
                    <a href="index.php"
                        class="px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-r border border-yellow-500 border-l-0 transition duration-300 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 256 256">
                            <path d="M205.66,194.34a8,8,0,0,1-11.32,11.32L128,139.31,61.66,205.66a8,8,0,0,1-11.32-11.32L116.69,128,50.34,61.66A8,8,0,0,1,61.66,50.34L128,116.69l66.34-66.35a8,8,0,0,1,11.32,11.32L139.31,128Z"></path>
                        </svg>
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tampilkan info hasil pencarian/filter -->
        <?php if (!empty($keyword) || $selectedCategory > 0): ?>
            <div class="mb-4 p-3 bg-yellow-100 border border-yellow-300 rounded-lg">
                <p class="text-yellow-800">
                    <?php
                    $filterInfo = [];
                    if (!empty($keyword)) {
                        $filterInfo[] = "Pencarian: \"" . htmlspecialchars($keyword) . "\"";
                    }
                    if ($selectedCategory > 0) {
                        foreach ($categoryList as $cat) {
                            if ($cat['id'] == $selectedCategory) {
                                $filterInfo[] = "Kategori: " . htmlspecialchars($cat['nama']);
                                break;
                            }
                        }
                    }
                    echo "Menampilkan hasil untuk " . implode(" dan ", $filterInfo) . " (" . count($articles) . " artikel ditemukan)";
                    ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- Articles Grid -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <?php if (empty($articles)): ?>
                <div class="col-span-full text-center py-12">
                    <div class="text-yellow-600 text-lg">
                        <?php if (!empty($keyword) || $selectedCategory > 0): ?>
                            Tidak ada artikel yang sesuai dengan pencarian Anda.
                        <?php else: ?>
                            Belum ada artikel yang tersedia.
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($articles as $article): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                        <?php if (!empty($article['gambar'])): ?>
                            <img src="assets/image/<?php echo htmlspecialchars($article['gambar']); ?>"
                                alt="<?php echo htmlspecialchars($article['judul']); ?>"
                                class="w-full h-48 object-cover">
                        <?php endif; ?>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-yellow-800 mb-2 line-clamp-2">
                                <?php echo htmlspecialchars($article['judul']); ?>
                            </h3>
                            <p class="text-gray-600 mb-4 line-clamp-3">
                                <?php echo htmlspecialchars(substr(strip_tags($article['isi']), 0, 150)); ?>...
                            </p>
                            <div class="flex justify-between items-center text-sm text-gray-500 mb-2">
                                <?php if (!empty($article['kategori_nama'])): ?>
                                    <span class="text-yellow-700 font-semibold">Kategori: <?php echo htmlspecialchars($article['kategori_nama']); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
                                <span>Oleh: <?php echo htmlspecialchars($article['penulis_nama']); ?></span>
                                <span><?php echo date('d M Y', strtotime($article['tanggal'])); ?></span>
                            </div>
                            <a href="artikel.php?id=<?php echo $article['id']; ?>"
                                class="inline-block bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm transition duration-300">
                                Baca Selengkapnya →
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-yellow-800 text-white mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Navigasi Cepat -->
                <div>
                    <h4 class="text-lg font-bold mb-4">Navigasi Cepat</h4>
                    <ul class="space-y-2">
                        <li><a href="index.php" class="hover:text-yellow-200 transition duration-300"><i class="fas fa-home mr-2"></i>Beranda</a></li>
                        <?php
                        // Periksa apakah pengguna sudah login
                        if (isset($_SESSION['user_id'])) {
                            // Jika sudah login, tampilkan tautan tambah artikel
                        ?>
                            <li><a href="tambah_artikel.php" class="hover:text-yellow-200 transition duration-300"><i class="fas fa-plus-circle mr-2"></i>Tambah Artikel</a></li>
                            <li><a href="logout.php" class="hover:text-yellow-200 transition duration-300"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a></li>
                        <?php
                        } else {
                            // Jika belum login, tampilkan tautan login dan register
                        ?>
                            <li><a href="login.php" class="hover:text-yellow-200 transition duration-300"><i class="fas fa-sign-in-alt mr-2"></i>Login</a></li>
                            <li><a href="register.php" class="hover:text-yellow-200 transition duration-300"><i class="fas fa-user-plus mr-2"></i>Daftar</a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>

                <!-- Kategori -->
                <div>
                    <h4 class="text-lg font-bold mb-4">Kategori</h4>
                    <ul class="space-y-2">
                        <?php foreach ($categoryList as $category): ?>
                            <li>
                                <a href="index.php?kategori_id=<?php echo $category['id']; ?>"
                                    class="hover:text-yellow-200 transition duration-300">
                                    <i class="fas fa-tag mr-2"></i><?php echo htmlspecialchars($category['nama']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
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
                <p>&copy; 2025 oleh Nabila Iliatus dari Universitas Islam Negeri Malang. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            --webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            --webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Clear search button functionality
            const clearButton = document.getElementById('clear-search');
            const searchInput = document.getElementById('search-input');

            clearButton.addEventListener('click', function() {
                searchInput.value = '';
                // Submit form to clear search
                searchInput.closest('form').submit();
            });

            // Show/hide clear button based on input
            function toggleClearButton() {
                if (searchInput.value.length > 0) {
                    clearButton.style.display = 'block';
                } else {
                    clearButton.style.display = 'none';
                }
            }

            searchInput.addEventListener('input', toggleClearButton);
            toggleClearButton(); // Initial check
        });

        function shareOnFacebook() {
            const url = encodeURIComponent(window.location.href);
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
        }

        function shareOnTwitter() {
            const url = encodeURIComponent(window.location.href);
            const text = encodeURIComponent(document.title);
            window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
        }

        function shareOnWhatsApp() {
            const url = encodeURIComponent(window.location.href);
            const text = encodeURIComponent(document.title);
            window.open(`https://wa.me/?text=${text} ${url}`, '_blank');
        }
    </script>
</body>

</html>