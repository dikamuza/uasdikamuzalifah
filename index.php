<!--
Author: W3layouts
Author URL: http://w3layouts.com
-->

<?php
include('koneksi.php');

// Tentukan berapa banyak artikel yang ditampilkan per halaman
$limit = 5;

// Ambil halaman saat ini dari URL, jika tidak ada, set ke 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Ambil total artikel untuk menghitung jumlah halaman
$total_sql = "SELECT COUNT(*) as total FROM artikel";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_articles = $total_row['total'];
$total_pages = ceil($total_articles / $limit);

// Ambil data artikel dengan limit dan offset
$sql = "SELECT id, kategori, judul, isi, author, tanggal_publikasi, images, view FROM artikel LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Periksa apakah ada hasil
if ($result->num_rows > 0) {
    // Menyimpan hasil ke dalam array
    $artikels = [];
    while ($row = $result->fetch_assoc()) {
        $artikels[] = $row;
    }
} else {
    $artikels = [];
}

// Query untuk artikel trending
$sql_trending = "SELECT id, kategori, judul, isi, author, tanggal_publikasi, images, view FROM artikel ORDER BY view DESC LIMIT 3";
$result_trending = $conn->query($sql_trending);

if ($result_trending->num_rows > 0) {
    $trendings = [];
    while ($row = $result_trending->fetch_assoc()) {
        $trendings[] = $row;
    }
} else {
    $trendings = [];
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Web Programming - Final Semester Exam</title>

    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet">

    <!-- Template CSS -->
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

                <button class="navbar-toggler collapsed" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="fa icon-expand fa-bars"></span>
                    <span class="fa icon-close fa-times"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item dropdown @@category__active">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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

                    <!-- search-right -->
                    <div class="search-right mt-lg-0 mt-2">
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
                    <!-- //search-right -->

                    <!-- toggle switch for light and dark theme -->
                    <div class="mobile-position">
                        <nav class="navigation">
                            <div class="theme-switch-wrapper">
                                <label class="theme-switch" for="checkbox">
                                    <input type="checkbox" id="checkbox">
                                    <div class="mode-container">
                                        <i class="gg-sun"></i>
                                        <i class="gg-moon"></i>
                                    </div>
                                </label>
                            </div>
                        </nav>
                    </div>
                    <!-- //toggle switch for light and dark theme -->


                    <!-- Dashboard icon -->
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
                <div class="col-lg-9 most-recent">
                    <h3 class="section-title-left">Most Recent posts </h3>
                    <div class="list-view">
                        <?php foreach ($artikels as $artikel): ?>
                            <div class="grids5-info img-block-mobile mt-5">
                                <div class="blog-info align-self">
                                    <span class="category"><?php echo htmlspecialchars($artikel['kategori']); ?></span>
                                    <a href="detail.php?id=<?php echo $artikel['id']; ?>" class="blog-desc mt-0"><?php echo htmlspecialchars($artikel['judul']); ?></a>
                                    <p><?php echo htmlspecialchars($artikel['isi']); ?></p>
                                    <div class="author align-items-center mt-3 mb-1">
                                        <a href="#author"><?php echo htmlspecialchars($artikel['author']); ?></a> in Design
                                    </div>
                                    <ul class="blog-meta">
                                        <li class="meta-item blog-lesson">
                                            <span class="meta-value"><?php echo htmlspecialchars($artikel['tanggal_publikasi']); ?></span>
                                        </li>
                                        <li class="meta-item blog-students">
                                            <span class="meta-value"><?php echo htmlspecialchars($artikel['view']); ?> reads</span>
                                        </li>
                                    </ul>
                                </div>
                                <a href="#blog-single" class="d-block zoom mt-md-0 mt-3"><img src="assets/images/<?php echo htmlspecialchars($artikel['images']); ?>"
                                        alt="" class="img-fluid radius-image news-image"></a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- Pagination code goes here -->
                    <div class="pagination-wrapper mt-5">
                        <ul class="page-pagination">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo $i; ?>" class="<?php echo ($page == $i) ? 'active' : ''; ?>"><?php echo $i; ?></a></li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                    <!-- //pagination -->
                </div>
                <div class="col-lg-3 trending mt-lg-0 mt-5 mb-lg-5">
                    <div class="pos-sticky">
                        <h3 class="section-title-left">Trending </h3>
                        <?php foreach ($trendings as $index => $trending): ?>
                            <div class="grids5-info">
                                <h4><?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?>.</h4>
                                <div class="blog-info">
                                    <a href="detail.php?id=<?php echo $trending['id']; ?>" class="blog-desc1"><?php echo htmlspecialchars($trending['judul']); ?></a>
                                    <div class="author align-items-center mt-2 mb-1">
                                        <a href="#author"><?php echo htmlspecialchars($trending['author']); ?></a> in <a href="#url"><?php echo htmlspecialchars($trending['kategori']); ?></a>
                                    </div>
                                    <ul class="blog-meta">
                                        <li class="meta-item blog-lesson">
                                            <span class="meta-value"><?php echo htmlspecialchars($trending['tanggal_publikasi']); ?></span>
                                        </li>
                                        <li class="meta-item blog-students">
                                            <span class="meta-value"><?php echo htmlspecialchars($trending['view']); ?> reads</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        <?php endforeach; ?>
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
                <ul class="author-icons mt-4">
                    <li><a class="facebook" href="#url"><span class="fa fa-facebook" aria-hidden="true"></span></a>
                    </li>
                    <li><a class="twitter" href="#url"><span class="fa fa-twitter" aria-hidden="true"></span></a></li>
                    <li><a class="google" href="#url"><span class="fa fa-google-plus" aria-hidden="true"></span></a>
                    </li>
                    <li><a class="linkedin" href="#url"><span class="fa fa-linkedin" aria-hidden="true"></span></a></li>
                    <li><a class="github" href="#url"><span class="fa fa-github" aria-hidden="true"></span></a></li>
                    <li><a class="dribbble" href="#url"><span class="fa fa-dribbble" aria-hidden="true"></span></a></li>
                </ul>
                <button onclick="topFunction()" id="movetop" title="Go to top">
                    <span class="fa fa-angle-up"></span>
                </button>
            </div>
        </div>

        <!-- move top -->
        <script>
            // When the user scrolls down 20px from the top of the document, show the button
            window.onscroll = function() {
                scrollFunction()
            };

            function scrollFunction() {
                if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                    document.getElementById("movetop").style.display = "block";
                } else {
                    document.getElementById("movetop").style.display = "none";
                }
            }

            // When the user clicks on the button, scroll to the top of the document
            function topFunction() {
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            }
        </script>
        <!-- //move top -->
    </footer>
    <!-- //footer -->

    <!-- Template JavaScript -->
    <script src="assets/js/theme-change.js"></script>

    <script src="assets/js/jquery-3.3.1.min.js"></script>

    <!-- disable body scroll which navbar is in active -->
    <script>
        $(function() {
            $('.navbar-toggler').click(function() {
                $('body').toggleClass('noscroll');
            })
        });
    </script>
    <!-- disable body scroll which navbar is in active -->

    <script src="assets/js/bootstrap.min.js"></script>

</body>

</html>