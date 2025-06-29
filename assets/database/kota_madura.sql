/*
SQLyog Community v13.3.0 (64 bit)
MySQL - 8.0.30 : Database - kota_madura
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`kota_madura` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `kota_madura`;

/*Table structure for table `artikel` */

DROP TABLE IF EXISTS `artikel`;

CREATE TABLE `artikel` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gambar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_tanggal` (`tanggal`),
  FULLTEXT KEY `idx_judul_isi` (`judul`,`isi`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `artikel` */

insert  into `artikel`(`id`,`tanggal`,`judul`,`isi`,`gambar`) values 
(1,'2025-06-10','Keindahan Alam di Bangkalan','Bangkalan tidak hanya gerbang utama Madura, tetapi juga rumah bagi pesona alam yang memukau. Dari tebing kapur eksotis di Bukit Jaddih hingga pemandangan matahari terbenam di Mercusuar Sembilangan, Bangkalan menawarkan pengalaman wisata alam yang tak terlupakan.','gambar_artikel_1.jpg'),
(2,'2025-06-09','Kuliner Khas Sampang: Cita Rasa yang Tak Terlupakan','Sampang terkenal dengan kulinernya yang otentik. Cicipi Bebek Songkem yang kaya rempah dan dimasak dengan cara unik, atau nikmati semangkuk Soto Sampang yang gurih. Setiap suapan adalah perayaan cita rasa asli Madura.','gambar_artikel_2.jpg'),
(3,'2025-06-08','Mengenal Tradisi Unik di Pamekasan','Pamekasan adalah jantung dari banyak tradisi Madura yang ikonik. Di sinilah semangat Karapan Sapi membara, diiringi oleh alunan musik Daul yang khas. Tradisi ini bukan sekadar hiburan, melainkan cerminan dari martabat dan budaya masyarakat Pamekasan.','gambar_artikel_3.jpg'),
(4,'2025-06-07','Potensi Wisata Bahari Sumenep','Sebagai kabupaten di ujung timur, Sumenep adalah surga bagi para penyelam dan pencinta pantai. Jelajahi keindahan bawah laut Gili Labak atau kunjungi Gili Iyang yang terkenal dengan kadar oksigen terbaik kedua di dunia. Potensi bahari Sumenep adalah permata yang bersinar.','gambar_artikel_4.jpg'),
(5,'2025-06-10','Perkembangan Infrastruktur di Kalianget','Kalianget, sebuah kota pelabuhan bersejarah, kini terus berbenah. Peningkatan fasilitas pelabuhan, perbaikan jalan, dan revitalisasi bangunan-bangunan tua peninggalan Belanda menunjukkan komitmen untuk menjadikan Kalianget sebagai pusat ekonomi dan konektivitas yang modern.','gambar_artikel_5.jpg'),
(6,'2025-06-05','Sosok Inspiratif dari Arosbaya','Di tengah sentra kerajinan besi dan batik, Arosbaya melahirkan banyak sosok inspiratif. Salah satunya adalah pengrajin batik muda yang berhasil memadukan motif klasik dengan desain kontemporer, membawa batik Arosbaya ke panggung nasional dan memberdayakan ekonomi lokal.','gambar_artikel_6.jpg'),
(7,'2025-06-04','Festival Budaya Tahunan Tanjung Bumi','Setiap tahun, pesisir Tanjung Bumi menjadi hidup dengan warna-warni festival budaya. Acara ini menampilkan parade perahu hias, tarian tradisional, dan pameran batik Gentongan yang legendaris, menarik wisatawan dan melestarikan warisan nenek moyang.','gambar_artikel_7.jpg'),
(8,'2025-06-03','Eksplorasi Sejarah Kota Larangan','Kecamatan Larangan di Pamekasan menyimpan banyak cerita sejarah. Situs-situs kuno dan makam-makam tua menjadi saksi bisu dari perjalanan peradaban di kawasan ini. Menggali sejarah Larangan adalah seperti membuka lembaran lama yang penuh makna.','gambar_artikel_8.jpg'),
(9,'2025-06-02','Kesenian Tradisional yang Masih Hidup di Prenduan','Prenduan, yang dikenal sebagai kota santri, juga merupakan pusat kesenian bernuansa religius. Seni Hadrah dan Saman berkembang pesat di sini, menjadi bagian tak terpisahkan dari upacara keagamaan dan perayaan masyarakat, menjaga syiar dan budaya tetap hidup.','gambar_artikel_9.jpg'),
(10,'2025-06-01','Jejak Arsitektur Kolonial di Gapura','Kecamatan Gapura di Sumenep memperlihatkan perpaduan unik antara arsitektur Madura dan Eropa. Banyak bangunan tua dan rumah bangsawan yang masih mempertahankan pilar-pilar kokoh dan jendela besar gaya kolonial, menciptakan lanskap sejarah yang menawan.','gambar_artikel_10.jpg'),
(11,'2025-05-31','Pesona Pantai Tersembunyi di Kamal','Jauh dari keramaian pelabuhan, Kamal menyimpan pantai-pantai kecil yang tenang seperti Pantai Rongkang. Dengan tebing-tebing kapur yang curam dan pemandangan langsung ke Selat Madura, tempat ini adalah surga tersembunyi bagi mereka yang mencari ketenangan.','gambar_artikel_11.jpg'),
(12,'2025-05-30','Peran Perempuan dalam Budaya Socah','Di Kecamatan Socah, perempuan adalah tulang punggung industri batik. Dari proses menggambar pola hingga pewarnaan, tangan-tangan terampil mereka menciptakan karya seni yang bernilai tinggi. Mereka adalah penjaga tradisi sekaligus motor penggerak ekonomi keluarga.','gambar_artikel_12.jpg'),
(13,'2025-05-29','Pendidikan di Pedalaman Burneh','Upaya peningkatan mutu pendidikan di Kecamatan Burneh terus berjalan. Inisiatif seperti rumah baca komunitas dan program beasiswa bagi siswa berprestasi menunjukkan semangat untuk membangun generasi masa depan Madura yang cerdas dan berkarakter.','gambar_artikel_13.jpg'),
(14,'2025-05-28','Inovasi Anak Muda di Galis','Sekelompok anak muda di Galis menciptakan platform digital untuk memasarkan hasil pertanian lokal secara langsung ke konsumen. Inovasi ini tidak hanya memotong rantai pasok yang panjang tetapi juga meningkatkan pendapatan para petani di wilayah tersebut.','gambar_artikel_14.jpg'),
(15,'2025-05-27','Kisah Mistis dari Saronggi','Saronggi, wilayah yang dekat dengan pemakaman raja-raja Sumenep, kaya akan cerita rakyat dan legenda. Kisah-kisah tentang penampakan dan tempat-tempat keramat diwariskan dari generasi ke generasi, menambah aura magis di kawasan ini.','gambar_artikel_15.jpg'),
(16,'2025-05-26','Daya Tarik Religius di Rubaru','Rubaru menjadi tujuan wisata religi bagi banyak peziarah. Keberadaan makam-makam aulia (buju\') yang dihormati menjadi pusat kegiatan spiritual masyarakat, memberikan kedamaian dan menjadi pengingat akan sejarah penyebaran Islam di Madura.','gambar_artikel_16.jpg'),
(17,'2025-05-25','Kerajinan Tangan Unik dari Torjun','Selain dikenal sebagai daerah pertanian, Torjun di Sampang memiliki potensi kerajinan anyaman pandan yang unik. Tas, tikar, dan hiasan dinding buatan tangan para pengrajin lokal memiliki motif khas yang tidak ditemukan di tempat lain.','gambar_artikel_17.jpg'),
(18,'2025-05-24','Cerita Rakyat Legendaris Ketapang','Kecamatan Ketapang di Sampang memiliki cerita rakyat yang melegenda, yaitu kisah Raden Segoro. Legenda ini menceritakan asal-usul nama daerah dan sering dipentaskan dalam bentuk ludruk atau seni pertunjukan rakyat lainnya.','gambar_artikel_18.jpg'),
(19,'2025-05-23','Transportasi Modern Masuk Pakong','Kehadiran layanan ojek online dan angkutan umum yang lebih teratur telah mengubah wajah transportasi di Pakong, Pamekasan. Kini, mobilitas warga menjadi lebih mudah, menghubungkan desa-desa terpencil dengan pusat kota secara lebih efisien.','gambar_artikel_19.jpg'),
(20,'2025-05-22','Ragam Bahasa dan Logat Daerah Palengaan','Bahasa Madura di Palengaan memiliki dialek dan intonasi yang khas. Penggunaan kosakata kuno yang masih bertahan dan gaya bicara yang unik menjadi identitas linguistik yang membedakan mereka dari wilayah Pamekasan lainnya.','gambar_artikel_20.jpg'),
(21,'2025-05-21','Warisan Sejarah Kecamatan Waru','Kecamatan Waru di Pamekasan merupakan salah satu area dengan peninggalan sejarah penting, termasuk beberapa situs megalitikum dan jejak pemukiman kuno. Ini menunjukkan bahwa wilayah ini telah menjadi pusat peradaban sejak zaman dahulu.','gambar_artikel_21.jpg'),
(22,'2025-05-20','Potensi Ekonomi Desa Tamberu','Desa Tamberu di Pamekasan menunjukkan potensi ekonomi yang besar di sektor perikanan darat. Banyak warga sukses mengembangkan budidaya ikan lele dan gurami, menjadikannya salah satu pemasok utama untuk pasar lokal.','gambar_artikel_22.jpg'),
(23,'2025-05-19','Inovasi Teknologi di Desa Madura','Semangat inovasi teknologi kini menyebar ke desa-desa di Madura. Mulai dari penggunaan drone untuk pertanian hingga sistem irigasi pintar, masyarakat lokal secara aktif mengadopsi teknologi untuk meningkatkan produktivitas dan kualitas hidup.','gambar_artikel_23.jpg'),
(24,'2025-05-18','Pelestarian Budaya Maritim Madura','Sebagai masyarakat bahari, budaya maritim Madura sangat kaya. Artikel ini mengeksplorasi tradisi pembuatan perahu, pengetahuan navigasi tradisional, dan upacara Petik Laut yang masih dilestarikan sebagai wujud syukur kepada laut.','gambar_artikel_24.jpg'),
(25,'2025-05-17','Pengembangan Ekonomi Kreatif di Madura','Ekonomi kreatif di Madura sedang menggeliat. Selain batik, sektor lain seperti kriya, kuliner, dan musik tradisional mulai dikemas secara modern untuk menjangkau pasar yang lebih luas, didukung oleh semangat wirausaha generasi muda.','gambar_artikel_25.jpg'),
(26,'2025-05-16','Pendidikan Berbasis Kearifan Lokal','Beberapa lembaga pendidikan di Madura mulai mengintegrasikan kearifan lokal ke dalam kurikulum. Pelajaran tentang etika, bahasa Madura halus (Enje’Iye), dan sejarah lokal diajarkan untuk membentuk siswa yang tidak hanya cerdas secara akademis tetapi juga kuat secara budaya.','gambar_artikel_26.jpg');

/*Table structure for table `artikel_kategori` */

DROP TABLE IF EXISTS `artikel_kategori`;

CREATE TABLE `artikel_kategori` (
  `id_artikel` int unsigned NOT NULL,
  `id_kategori` int unsigned NOT NULL,
  PRIMARY KEY (`id_artikel`,`id_kategori`),
  KEY `idx_artikel` (`id_artikel`),
  KEY `idx_kategori` (`id_kategori`),
  CONSTRAINT `artikel_kategori_ibfk_1` FOREIGN KEY (`id_artikel`) REFERENCES `artikel` (`id`) ON DELETE CASCADE,
  CONSTRAINT `artikel_kategori_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `artikel_kategori` */

insert  into `artikel_kategori`(`id_artikel`,`id_kategori`) values 
(1,6),
(2,2),
(3,1),
(4,6),
(5,1),
(6,1),
(6,5),
(7,1),
(7,6),
(8,4),
(9,1),
(10,4),
(11,6),
(12,1),
(12,5),
(13,1),
(14,5),
(15,1),
(16,1),
(16,6),
(17,5),
(18,1),
(19,3),
(20,1),
(21,4),
(22,5),
(23,5),
(24,1),
(24,4),
(25,5),
(26,1);

/*Table structure for table `artikel_penulis` */

DROP TABLE IF EXISTS `artikel_penulis`;

CREATE TABLE `artikel_penulis` (
  `id_artikel` int unsigned NOT NULL,
  `id_penulis` int unsigned NOT NULL,
  PRIMARY KEY (`id_artikel`,`id_penulis`),
  KEY `idx_artikel` (`id_artikel`),
  KEY `idx_penulis` (`id_penulis`),
  CONSTRAINT `artikel_penulis_ibfk_1` FOREIGN KEY (`id_artikel`) REFERENCES `artikel` (`id`) ON DELETE CASCADE,
  CONSTRAINT `artikel_penulis_ibfk_2` FOREIGN KEY (`id_penulis`) REFERENCES `penulis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `artikel_penulis` */

insert  into `artikel_penulis`(`id_artikel`,`id_penulis`) values 
(1,1),
(2,2),
(3,3),
(4,4),
(5,1),
(6,2),
(7,3),
(8,4),
(9,1),
(10,2),
(11,3),
(12,4),
(13,1),
(14,2),
(15,3),
(16,4),
(17,1),
(18,2),
(19,3),
(20,4),
(21,1),
(22,2),
(23,3),
(24,4),
(25,1),
(26,2);

/*Table structure for table `kategori` */

DROP TABLE IF EXISTS `kategori`;

CREATE TABLE `kategori` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_nama_kategori` (`nama`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `kategori` */

insert  into `kategori`(`id`,`nama`,`deskripsi`) values 
(1,'Budaya','Artikel mengenai tradisi, adat istiadat, dan kesenian Madura.'),
(2,'Kuliner','Artikel mengenai makanan khas dan minuman tradisional Madura.'),
(3,'Infrastruktur','Artikel tentang kemajuan fasilitas umum dan pembangunan di Madura.'),
(4,'Sejarah','Artikel yang membahas sejarah, warisan, dan peninggalan masa lalu di Madura.'),
(5,'Ekonomi','Artikel tentang perkembangan ekonomi, industri, dan inovasi bisnis di Madura.'),
(6,'Wisata','Artikel mengenai destinasi pariwisata, keindahan alam, dan spot menarik di Madura.');

/*Table structure for table `penulis` */

DROP TABLE IF EXISTS `penulis`;

CREATE TABLE `penulis` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sandi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_email` (`email`),
  KEY `idx_nama` (`nama`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `penulis` */

insert  into `penulis`(`id`,`nama`,`email`,`sandi`) values 
(1,'belaabel289','belaabel289@gmail.com','$2y$10$Wle9n/KAGbiKhTimm5zo5OiVZtayWJcfgzw6GHV6RZrD3H9gY8ose'),
(2,'Siti Aminah','sitiaminah@gmail.com','$2y$10$Wle9n/KAGbiKhTimm5zo5OiVZtayWJcfgzw6GHV6RZrD3H9gY8ose'),
(3,'Budi Santoso','budisantoso@gmail.com','$2y$10$Wle9n/KAGbiKhTimm5zo5OiVZtayWJcfgzw6GHV6RZrD3H9gY8ose'),
(4,'Dewi Rahayu','dewirahayu@gmail.com','$2y$10$Wle9n/KAGbiKhTimm5zo5OiVZtayWJcfgzw6GHV6RZrD3H9gY8ose'),
(5,'Laila Cri Amasiyah','lailaamasiyah@gmail.com','$2y$10$D8bat7D34p1omVq6eezC0e5ASXeRsClBIBxgb2KNpIuxCMeUDBz5O'),
(6,'alvinzanua28','alvinzanua28@gmail.com','$2y$10$AV94EsTvYtqeymVsyoI1SOps96T4epQM/puilFzkqScV9y6Vul5t6');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
