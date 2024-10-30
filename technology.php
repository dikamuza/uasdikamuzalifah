<?php
include('koneksi.php');

// Tentukan berapa banyak artikel yang ditampilkan per halaman
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Ambil total artikel untuk kategori "Technology"
$total_sql = "SELECT COUNT(*) as total FROM artikel WHERE kategori = 'Technology'";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_articles = $total_row['total'];
$total_pages = ceil($total_articles / $limit);

// Ambil data artikel dengan kategori "Technology"
$sql = "SELECT id, kategori, judul, isi, author, tanggal_publikasi, images, view FROM artikel WHERE kategori = 'Technology' LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
$artikels = ($result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Query untuk artikel trending
$sql_trending = "SELECT id, kategori, judul, isi, author, tanggal_publikasi, images, view FROM artikel ORDER BY view DESC LIMIT 3";
$result_trending = $conn->query($sql_trending);
$trendings = ($result_trending->num_rows > 0) ? $result_trending->fetch_all(MYSQLI_ASSOC) : [];

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Technology Posts</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style-starter.css">
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">Web Programming Blog</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Categories</a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="technology.php">Technology</a>
            <a class="dropdown-item" href="lifestyle.php">Lifestyle</a>
          </div>
        </li>
        <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
      </ul>
    </div>
  </nav>

  <!-- Breadcrumbs -->
  <div class="container mt-4">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Technology</li>
      </ol>
    </nav>

    <!-- Technology Articles Section -->
    <div class="row">
      <!-- Main Articles -->
      <div class="col-lg-8">
        <h3 class="section-title-left">Technology</h3>
        <?php if (!empty($artikels)): ?>
          <?php foreach ($artikels as $artikel): ?>
            <div class="card mb-4">
              <img src="assets/images/<?php echo htmlspecialchars($artikel['images']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($artikel['judul']); ?>">
              <div class="card-body">
                <h5 class="card-title">
                  <a href="detail.php?id=<?php echo htmlspecialchars($artikel['id']); ?>"><?php echo htmlspecialchars($artikel['judul']); ?></a>
                </h5>
                <p class="card-text"><?php echo htmlspecialchars($artikel['isi']); ?></p>
                <p class="card-text"><small class="text-muted">By <?php echo htmlspecialchars($artikel['author']); ?> | <?php echo htmlspecialchars($artikel['tanggal_publikasi']); ?></small></p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>No articles found in the Technology category.</p>
        <?php endif; ?>

        <!-- Pagination -->
        <nav>
          <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
              <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                <a class="page-link" href="technology.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
              </li>
            <?php endfor; ?>
          </ul>
        </nav>
      </div>

      <!-- Trending Articles -->
      <div class="col-lg-4">
        <h3 class="section-title-left">Trending</h3>
        <?php foreach ($trendings as $trending): ?>
          <div class="media mb-4">
            <img src="assets/images/<?php echo htmlspecialchars($trending['images']); ?>" class="mr-3" alt="<?php echo htmlspecialchars($trending['judul']); ?>" width="64">
            <div class="media-body">
              <h6 class="mt-0">
                <a href="detail.php?id=<?php echo htmlspecialchars($trending['id']); ?>"><?php echo htmlspecialchars($trending['judul']); ?></a>
              </h6>
              <small class="text-muted"><?php echo htmlspecialchars($trending['tanggal_publikasi']); ?> | <?php echo htmlspecialchars($trending['view']); ?> reads</small>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="text-center py-4">
    <div>&copy; 2024 Web Programming Blog. Created by <i>(Dika Muza)</i></div>
  </footer>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
