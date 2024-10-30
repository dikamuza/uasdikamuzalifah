<?php
include('koneksi.php');

// Tentukan berapa banyak artikel yang ditampilkan per halaman
$limit = 5;

// Ambil halaman saat ini dari URL, jika tidak ada, set ke 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Ambil total artikel kategori Lifestyle untuk menghitung jumlah halaman
$total_sql = "SELECT COUNT(*) as total FROM artikel WHERE kategori = 'Lifestyle'";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_articles = $total_row['total'];
$total_pages = ceil($total_articles / $limit);

// Ambil data artikel kategori Lifestyle dengan limit dan offset
$sql = "SELECT id, kategori, judul, isi, author, tanggal_publikasi, images, view FROM artikel WHERE kategori = 'Lifestyle' LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Periksa apakah ada hasil
if ($result->num_rows > 0) {
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

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Web Programming - Lifestyle</title>
  <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style-starter.css" />
</head>

<body>
  <header class="w3l-header">
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
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <nav id="breadcrumbs" class="breadcrumbs">
    <div class="container page-wrapper">
      <a href="index.php">Home</a> / Categories /
      <span class="breadcrumb_last" aria-current="page">Lifestyle</span>
    </div>
  </nav>

  <div class="w3l-searchblock w3l-homeblock1 py-5">
    <div class="container py-lg-4 py-md-3">
      <div class="row">
        <div class="col-lg-8 most-recent">
          <h3 class="section-title-left">Lifestyle</h3>

          <div class="row">
            <?php foreach ($artikels as $artikel): ?>
              <div class="col-lg-6 col-md-6 item">
                <div class="card">
                  <div class="card-header p-0 position-relative">
                    <a href="detail.php?id=<?php echo htmlspecialchars($artikel['id']); ?>">
                      <img class="card-img-bottom d-block radius-image" src="assets/images/<?php echo htmlspecialchars($artikel['images']); ?>" alt="Card image cap" />
                    </a>
                  </div>
                  <div class="card-body p-0 blog-details">
                    <a href="detail.php?id=<?php echo htmlspecialchars($artikel['id']); ?>" class="blog-desc"><?php echo htmlspecialchars($artikel['judul']); ?></a>
                    <p><?php echo htmlspecialchars($artikel['isi']); ?></p>
                    <div class="author align-items-center mt-3 mb-1">
                      <a href="#author"><?php echo htmlspecialchars($artikel['author']); ?></a>
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
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="pagination-wrapper mt-5">
            <ul class="page-pagination">
              <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo $i; ?>" class="<?php echo ($page == $i) ? 'active' : ''; ?>"><?php echo $i; ?></a></li>
              <?php endfor; ?>
            </ul>
          </div>
        </div>
        
        <div class="col-lg-4 trending mt-lg-0 mt-5 mb-lg-5">
          <div class="pos-sticky">
            <h3 class="section-title-left">Trending</h3>
            <?php foreach ($trendings as $index => $trending): ?>
              <div class="grids5-info">
                <h4><?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?>.</h4>
                <div class="blog-info">
                  <a href="detail.php?id=<?php echo htmlspecialchars($trending['id']); ?>" class="blog-desc1"><?php echo htmlspecialchars($trending['judul']); ?></a>
                  <div class="author align-items-center mt-2 mb-1">
                    <a href="#author"><?php echo htmlspecialchars($trending['author']); ?></a>
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

  <footer class="w3l-footer-16">
    <div class="footer-content py-lg-5 py-4 text-center">
      <div class="container">
        <div class="copy-right">
          <h6>2024 Web Programming Blog. Made by <i>Dika Muza</i> with <span class="fa fa-heart" aria-hidden="true"></span></h6>
        </div>
      </div>
    </div>
  </footer>

  <script src="assets/js/theme-change.js"></script>
  <script src="assets/js/jquery-3.3.1.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>
