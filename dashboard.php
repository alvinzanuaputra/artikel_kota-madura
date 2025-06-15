<?php
require_once 'koneksi.php';

// Cek apakah user sudah login
requireLogin();

// Logout handler
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Ambil ID penulis yang login
$penulis_id = $_SESSION['penulis_id'];

// Ambil ID kategori dari URL jika ada
$kategori_id = isset($_GET['kategori_id']) ? (int)$_GET['kategori_id'] : null;

// Tambahkan variabel untuk pencarian dan filter
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$selectedCategory = isset($_GET['category']) ? intval($_GET['category']) : null;

// Ambil semua kategori untuk filter
$categories = [];
try {
    $stmt_kategori = $pdo->prepare("SELECT id, nama FROM kategori ORDER BY nama ASC");
    $stmt_kategori->execute();
    $categories = $stmt_kategori->fetchAll();
} catch (PDOException $e) {
    // Anda bisa tambahkan logging error di sini
    // error_log("Error fetching categories: " . $e->getMessage());
    $categories = [];
}


// Ambil artikel berdasarkan penulis yang login dan kategori (jika dipilih)
$articles = [];
try {
    $sql = "
        SELECT
            a.*,
            p.nama AS penulis_nama,
            GROUP_CONCAT(k.nama ORDER BY k.nama ASC SEPARATOR ', ') AS kategori_nama
        FROM
            artikel a
        JOIN
            artikel_penulis ap ON a.id = ap.id_artikel
        JOIN
            penulis p ON ap.id_penulis = p.id
        LEFT JOIN
            artikel_kategori ak_concat ON a.id = ak_concat.id_artikel
        LEFT JOIN
            kategori k ON ak_concat.id_kategori = k.id
    ";

    $conditions = ["ap.id_penulis = :penulis_id"]; // Selalu filter berdasarkan penulis yang login
    $params = [':penulis_id' => $penulis_id];

    // Filter kategori
    if ($selectedCategory) {
        $sql .= " JOIN artikel_kategori ak_filter ON a.id = ak_filter.id_artikel";
        $conditions[] = "ak_filter.id_kategori = :kategori_id";
        $params[':kategori_id'] = $selectedCategory;
    }

    // Filter pencarian
    if (!empty($keyword)) {
        $conditions[] = "(a.judul LIKE :keyword1 OR a.isi LIKE :keyword2)";
        $params[':keyword1'] = "%{$keyword}%";
        $params[':keyword2'] = "%{$keyword}%";
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $sql .= " GROUP BY a.id"; // Penting untuk GROUP_CONCAT
    $sql .= " ORDER BY a.tanggal DESC"; // Urutkan berdasarkan tanggal artikel

    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $stmt->execute();
    $articles = $stmt->fetchAll();
} catch (PDOException $e) {
    // Tambahkan logging error untuk debugging lebih lanjut jika diperlukan
    // error_log("Error fetching articles: " . $e->getMessage());
    $articles = [];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Kota Madura</title>
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
                    <span class="text-yellow-100">Selamat datang, <?php echo htmlspecialchars($_SESSION['penulis_nama']); ?></span>
                    <a href="?logout=1" class="bg-yellow-800 hover:bg-yellow-900 text-white px-4 py-2 rounded-md transition duration-300 flex items-center">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="bg-yellow-500 shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex space-x-8 py-4">
                <a href="index.php" class="text-white font-medium hover:text-yellow-200 transition duration-300"><i class="fas fa-home mr-2"></i>Beranda</a>
                <a href="tambah_artikel.php" class="text-white font-medium hover:text-yellow-200 transition duration-300"><i class="fas fa-plus mr-2"></i>Tambah Artikel</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-yellow-800 mb-4">Daftar Artikel</h2>
        </div>

        <!-- Form Pencarian dan Filter -->
        <div class="flex mb-6 text-gray-800 relative rounded-t-lg">
            <!-- Form Pencarian -->
            <form method="GET" action="dashboard.php" class="flex-1 relative">
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
            <form method="GET" action="dashboard.php" id="filter-form" class="ml-2 flex items-center">
                <!-- Hidden field untuk mempertahankan keyword -->
                <?php if (!empty($keyword)): ?>
                    <input type="hidden" name="keyword" value="<?php echo htmlspecialchars($keyword); ?>">
                <?php endif; ?>

                <select name="category" id="category-select"
                    class="px-2 py-2 rounded-l border border-yellow-500 bg-white text-gray-800"
                    onchange="this.form.submit()">
                    <option value="0">Semua Kategori</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php if ($selectedCategory == $cat['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($cat['nama']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Clear Filter Button inside the form -->
                <?php if (!empty($keyword) || $selectedCategory > 0): ?>
                    <a href="dashboard.php"
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
                        foreach ($categories as $cat) {
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
                    <div class="text-yellow-600 text-lg">Belum ada artikel yang dipublikasikan.</div>
                    <a href="tambah_artikel.php" class="text-yellow-800 font-medium hover:underline mt-2 inline-block">
                        Buat artikel pertama Anda
                    </a>
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
                            <div class="flex space-x-2">
                                <a href="edit_artikel.php?id=<?php echo $article['id']; ?>"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition duration-300 flex items-center">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <a href="hapus_artikel.php?id=<?php echo $article['id']; ?>"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition duration-300 flex items-center">
                                    <i class="fas fa-trash-alt mr-1"></i> Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <?php echo generateFooter($categories, true); ?>

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
    </script>
</body>

</html>