<?php
include('koneksi.php');

// Cek apakah ada ID artikel yang diteruskan melalui URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Mengambil ID dari URL

    // Ambil data artikel berdasarkan ID
    $sql = "SELECT kategori, judul, isi, author, tanggal_publikasi, images, view FROM artikel WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Periksa apakah ada hasil
    if ($result->num_rows > 0) {
        $artikel = $result->fetch_assoc();

        // Update kolom view
        $new_view_count = $artikel['view'] + 1; // Menambahkan 1 ke jumlah view saat ini
        $update_sql = "UPDATE artikel SET view = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ii", $new_view_count, $id);
        $update_stmt->execute();
    } else {
        // Jika tidak ada artikel ditemukan, bisa redirect atau tampilkan pesan
        echo "Artikel tidak ditemukan.";
        exit;
    }
} else {
    // Jika tidak ada ID, redirect ke halaman utama
    header("Location: index.php");
    exit;
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo htmlspecialchars($artikel['judul']); ?> - Web Programming Blog</title>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;500;600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style-starter.css">
</head>

<body>
    <!-- header -->
    <header class="w3l-header">
        <!--/nav-->
        <nav class="navbar navbar-expand-lg navbar-light fill px-lg-0 py-0 px-3">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <span class="fa fa-pencil-square-o"></span> Web Programming Blog
                </a>
                <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="fa icon-expand fa-bars"></span>
                    <span class="fa icon-close fa-times"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item dropdown @@category__active">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Categories <span class="fa fa-angle-down"></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item @@cp__active" href="technology.php">Technology posts</a>
                                <a class="dropdown-item @@ls__active" href="lifestyle.php">Lifestyle posts</a>
                            </div>
                        </li>
                        <li class="nav-item @@contact__active">
                            <a class="nav-link" href="contact.html">Contact</a>
                        </li>
                        <li class="nav-item @@about__active">
                            <a class="nav-link" href="about.html">About</a>
                        </li>
                    </ul>
                    <div class="search-right mt-lg-0 mt- 2">
                        <a href="#search" title="search"><span class="fa fa-search" aria-hidden="true"></span></a>
                        <div id="search" class="pop-overlay">
                            <div class="popup">
                                <h3 class="hny-title two">Search here</h3>
                                <form action="#" method="Get" class="search-box">
                                    <input type="search" placeholder="Search for blog posts" name="search" required="required" autofocus="">
                                    <button type="submit" class="btn">Search</button>
                                </form>
                                <a class="close" href="#close">×</a>
                            </div>
                        </div>
                    </div>
                    <a href="dashboard.html" class="ml-3" title="Dashboard">
                        <span class="fa fa-user-circle fa-lg"></span>
                    </a>
                </div>
            </div>
        </nav>
        <!--//nav-->
    </header>
    <!-- //header -->
    <div class="w3l-homeblock1">
        <div class="container pt-lg-5 pt-md-4">
            <div class="row">
                <div class="col-lg-12 most-recent">
                    <h3 class="section-title-left"><?php echo htmlspecialchars($artikel['judul']); ?></h3>
                    <div class="grids5-info img-block-mobile mt-5">
                        <div class="blog-info align-self">
                            <span class="category"><?php echo htmlspecialchars($artikel['kategori']); ?></span>
                            <p><?php echo htmlspecialchars($artikel['isi']); ?></p>
                            <div class="author align-items-center mt-3 mb-1">
                                <a href="#author"><?php echo htmlspecialchars($artikel['author']); ?></a> | <?php echo htmlspecialchars($artikel['tanggal_publikasi']); ?>
                            </div>
                        </div>
                        <a href="#blog-single" class="d-block zoom mt-md-0 mt-3">
                            <img src="assets/images/<?php echo htmlspecialchars($artikel['images']); ?>" alt="<?php echo htmlspecialchars($artikel['judul']); ?>" class="img-fluid radius-image news-image">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- footer -->
    <footer class="w3l-footer-16">
        <div class="footer-content py-lg-5 py-4 text-center">
            <div class="container">
                <div class="copy-right">
                    <h6>© 2024 Web Programming Blog . Made by <i>(Dika Muza)</i> with <span class="fa fa-heart" aria-hidden="true"></span><br>Designed by
                        <a href="https://w3layouts.com">W3layouts</a>
                    </h6>
                </div>
                <button onclick="topFunction()" id="movetop" title="Go to top">
                    <span class="fa fa-angle-up"></span>
                </button>
            </div>
        </div>
    </footer>
    <!-- //footer -->
</body>

</html>