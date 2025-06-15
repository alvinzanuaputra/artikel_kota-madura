<?php
require_once 'koneksi.php';

// Ambil ID artikel dari URL
$article_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($article_id <= 0) {
    header('Location: index.php');
    exit;
}

// Query untuk mengambil detail artikel
$sql = "SELECT 
            a.id,
            a.tanggal,
            a.judul,
            a.isi,
            a.gambar,
            p.nama as penulis_nama,
            GROUP_CONCAT(DISTINCT k.nama SEPARATOR ', ') as kategori_nama,
            GROUP_CONCAT(DISTINCT k.id SEPARATOR ',') as kategori_ids
        FROM artikel a
        LEFT JOIN artikel_penulis ap ON a.id = ap.id_artikel
        LEFT JOIN penulis p ON ap.id_penulis = p.id
        LEFT JOIN artikel_kategori ak ON a.id = ak.id_artikel
        LEFT JOIN kategori k ON ak.id_kategori = k.id
        WHERE a.id = :article_id
        GROUP BY a.id, a.tanggal, a.judul, a.isi, a.gambar, p.nama";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
$stmt->execute();
$article = $stmt->fetch();

if (!$article) {
    header('Location: index.php');
    exit;
}

// Query untuk artikel terkait (berdasarkan kategori yang sama)
$related_articles = [];
if (!empty($article['kategori_ids'])) {
    $kategori_ids = explode(',', $article['kategori_ids']);
    $placeholders = str_repeat('?,', count($kategori_ids) - 1) . '?';

    $sql_related = "SELECT DISTINCT
                        a.id,
                        a.tanggal,
                        a.judul,
                        a.gambar,
                        p.nama as penulis_nama,
                        GROUP_CONCAT(k.nama SEPARATOR ', ') as kategori_nama
                    FROM artikel a
                    LEFT JOIN artikel_penulis ap ON a.id = ap.id_artikel
                    LEFT JOIN penulis p ON ap.id_penulis = p.id
                    LEFT JOIN artikel_kategori ak ON a.id = ak.id_artikel
                    LEFT JOIN kategori k ON ak.id_kategori = k.id
                    WHERE a.id != ? 
                    AND a.id IN (
                        SELECT id_artikel 
                        FROM artikel_kategori 
                        WHERE id_kategori IN ($placeholders)
                    )
                    GROUP BY a.id, a.tanggal, a.judul, a.gambar, p.nama
                    ORDER BY a.tanggal DESC
                    LIMIT 3";

    $stmt_related = $pdo->prepare($sql_related);
    $params = array_merge([$article_id], $kategori_ids);
    $stmt_related->execute($params);
    $related_articles = $stmt_related->fetchAll();
}

// Ambil kategori untuk navigasi
$categoryList = [];
$sql_categories = "SELECT id, nama FROM kategori ORDER BY nama";
$stmt_categories = $pdo->prepare($sql_categories);
$stmt_categories->execute();
$categoryList = $stmt_categories->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['judul']); ?> - Kota Madura</title>
    <link href="./assets/logo/logo.png" type="image/png" rel="shortcut icon">
    <meta name="description" content="<?php echo htmlspecialchars(substr(strip_tags($article['isi']), 0, 160)); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-yellow-50 to-yellow-100 min-h-screen">
    <!-- Header -->
    <header class="bg-gradient-to-r from-yellow-600 to-yellow-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-white">
                        <a href="index.php" class="hover:text-yellow-200 transition duration-300">Kota Madura</a>
                    </h1>
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
                        <?php foreach ($categoryList as $category): ?>
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
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Article Content -->
            <div class="lg:col-span-2">
                <article class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Article Image -->
                    <?php if (!empty($article['gambar'])): ?>
                        <div class="w-full h-full">
                            <img src="./assets/image/<?php echo htmlspecialchars($article['gambar']); ?>"
                                alt="<?php echo htmlspecialchars($article['judul']); ?>"
                                class="w-full h-full object-cover">
                        </div>
                    <?php endif; ?>

                    <div class="p-6 md:p-8">
                        <!-- Article Meta -->
                        <div class="flex flex-wrap items-center gap-4 mb-6 text-sm text-gray-600">
                            <?php if (!empty($article['kategori_nama'])): ?>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                                    </svg>
                                    <span class="text-yellow-700 font-semibold"><?php echo htmlspecialchars($article['kategori_nama']); ?></span>
                                </div>
                            <?php endif; ?>

                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                                <span><?php echo htmlspecialchars($article['penulis_nama']); ?></span>
                            </div>

                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                <span><?php echo date('d F Y', strtotime($article['tanggal'])); ?></span>
                            </div>
                        </div>

                        <!-- Article Title -->
                        <h1 class="text-3xl md:text-4xl font-bold text-yellow-800 mb-6 leading-tight">
                            <?php echo htmlspecialchars($article['judul']); ?>
                        </h1>

                        <!-- Article Content -->
                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                            <?php echo nl2br(htmlspecialchars($article['isi'])); ?>
                        </div>

                        <!-- Share Buttons -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 font-medium">Bagikan artikel ini:</span>
                                <div class="flex space-x-3">
                                    <a href="#" onclick="shareOnFacebook()" class="text-blue-600 hover:text-blue-700 transition duration-300">
                                        <i class="fab fa-facebook-square text-3xl"></i>
                                    </a>
                                    <a href="#" onclick="shareOnTwitter()" class="text-blue-400 hover:text-blue-500 transition duration-300">
                                        <i class="fab fa-twitter-square text-3xl"></i>
                                    </a>
                                    <a href="#" onclick="shareOnWhatsApp()" class="text-green-600 hover:text-green-700 transition duration-300">
                                        <i class="fab fa-whatsapp-square text-3xl"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Author Info (Disembunyikan karena tidak ada kolom bio) -->
                <div class="mt-8 bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-yellow-800 mb-4">Tentang Penulis</h3>
                    <div class="flex items-start space-x-4">
                        <div class="w-16 h-16 bg-yellow-200 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($article['penulis_nama']); ?></h4>
                            <p class="text-gray-600">Penulis artikel di Portal Kota Madura</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Related Articles -->
                <?php if (!empty($related_articles)): ?>
                    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                        <h3 class="text-xl font-bold text-yellow-800 mb-4">Tentang Artikel Terkait</h3>
                        <div class="space-y-4">
                            <?php foreach ($related_articles as $related): ?>
                                <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                                    <a href="artikel.php?id=<?php echo $related['id']; ?>" class="block group">
                                        <div class="flex space-x-3">
                                            <?php if (!empty($related['gambar'])): ?>
                                                <img src="./assets/image/<?php echo htmlspecialchars($related['gambar']); ?>"
                                                    alt="<?php echo htmlspecialchars($related['judul']); ?>"
                                                    class="w-20 h-16 object-cover rounded">
                                            <?php else: ?>
                                                <div class="w-20 h-16 bg-gray-200 rounded flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            <?php endif; ?>
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-800 group-hover:text-yellow-600 transition duration-300 line-clamp-2 text-sm">
                                                    <?php echo htmlspecialchars($related['judul']); ?>
                                                </h4>
                                                <div class="flex items-center mt-2 text-xs text-gray-500">
                                                    <span><?php echo htmlspecialchars($related['penulis_nama']); ?></span>
                                                    <span class="mx-1">•</span>
                                                    <span><?php echo date('d M Y', strtotime($related['tanggal'])); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Back to Articles -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <a href="index.php" class="block w-full bg-yellow-600 hover:bg-yellow-700 text-white text-center py-3 px-4 rounded-md transition duration-300 flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Semua Artikel
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php echo generateFooter($categoryList); ?>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            --webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .prose p {
            margin-bottom: 1rem;
        }

        .prose h1,
        .prose h2,
        .prose h3,
        .prose h4,
        .prose h5,
        .prose h6 {
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }
    </style>

    <script>
        function shareOnFacebook() {
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400');
        }

        function shareOnTwitter() {
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);
            window.open(`https://twitter.com/intent/tweet?url=${url}&text=${title}`, '_blank', 'width=600,height=400');
        }

        function shareOnWhatsApp() {
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);
            window.open(`https://wa.me/?text=${title} ${url}`, '_blank');
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>

</html>