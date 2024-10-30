<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "berita";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fungsi Create
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['action'] == 'create') {
    $judul = $conn->real_escape_string($_POST['judul']);
    $isi = $conn->real_escape_string($_POST['isi']);
    $kategori = $conn->real_escape_string($_POST['kategori']);
    $author = $conn->real_escape_string($_POST['author']);
    $tanggal_publikasi = $conn->real_escape_string($_POST['tanggal_publikasi']);

    // Insert data tanpa gambar terlebih dahulu
    $sql = "INSERT INTO artikel (judul, isi, kategori, author, tanggal_publikasi) VALUES ('$judul', '$isi', '$kategori', '$author', '$tanggal_publikasi')";
    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;

        // Handle file upload
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
            $target_dir = "assets/images/";
            $imageFileType = strtolower(pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION));
            $new_filename = $last_id . '.' . $imageFileType;
            $target_file = $target_dir . $new_filename;

            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                // Update record dengan nama file gambar
                $update_sql = "UPDATE artikel SET images='$new_filename' WHERE id=$last_id";
                $conn->query($update_sql);
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        header("Location: dashboard.html");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fungsi Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['action'] == 'update') {
    $id = intval($_POST['id']);
    $judul = $conn->real_escape_string($_POST['judul']);
    $isi = $conn->real_escape_string($_POST['isi']);
    $kategori = $conn->real_escape_string($_POST['kategori']);
    $author = $conn->real_escape_string($_POST['author']);
    $tanggal_publikasi = $conn->real_escape_string($_POST['tanggal_publikasi']);

    // Mulai dengan query dasar
    $updateFields = "judul='$judul', isi='$isi', kategori='$kategori', author='$author', tanggal_publikasi='$tanggal_publikasi'";

    // Handle file upload jika ada file baru
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "assets/images/";
        $imageFileType = strtolower(pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION));
        $new_filename = $id . '.' . $imageFileType;
        $target_file = $target_dir . $new_filename;

        // Hapus file lama jika ada
        $old_file_query = "SELECT images FROM artikel WHERE id=$id";
        $result = $conn->query($old_file_query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (!empty($row['images'])) {
                $old_file = $target_dir . $row['images'];
                if (file_exists($old_file)) {
                    unlink($old_file);
                }
            }
        }

        // Upload file baru
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            // Tambahkan update gambar ke query
            $updateFields .= ", images='$new_filename'";
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    }

    // Buat query update lengkap
    $sql = "UPDATE artikel SET $updateFields WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.html");
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Fungsi Read (tampilkan satu artikel berdasarkan ID)
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM artikel WHERE id = $id";
    $result = $conn->query($sql);
    $article = $result->fetch_assoc();
    echo json_encode($article);
    exit;
}

// Fungsi Fetch All untuk menampilkan semua artikel
function fetchAll()
{
    global $conn;
    $sql = "SELECT * FROM artikel";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr data-id='{$row['id']}'>
                    <td class='judul'>{$row['judul']}</td>
                    <td class='isi'>{$row['isi']}</td>
                    <td class='kategori'>{$row['kategori']}</td>
                    <td class='author'>{$row['author']}</td>
                    <td class='tanggal'>{$row['tanggal_publikasi']}</td>
                    <td class='images'>{$row['images']}</td>
                    <td>
                        <button class='btn btn-success edit-btn' onclick='editArticle({$row['id']})'>Edit</button>
                        <button class='btn btn-danger btn-sm' onclick='deleteArticle({$row['id']})'>Delete</button>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No articles available</td></tr>";
    }
}

// Fungsi Delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['action'] == 'delete') {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM artikel WHERE id = $id";
    $conn->query($sql);
    header("Location: dashboard.html");
    exit;
}

// Memanggil fetchAll untuk menampilkan artikel
fetchAll();

// Tutup koneksi
$conn->close();
?>


<!-- Modal for Editing Article -->
<div id="editModal" style="display:none;">
    <form method="POST" action="crud.php">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" id="edit-id">
        <label for="judul">Judul:</label>
        <input type="text" id="edit-judul" name="judul"><br><br>
        <label for="isi">Isi:</label>
        <textarea id="edit-isi" name="isi"></textarea><br><br>
        <label for="kategori">Kategori:</label>
        <input type="text" id="edit-kategori" name="kategori"><br><br>
        <label for="author">Author:</label>
        <input type="text" id="edit-author" name="author"><br><br>
        <label for="tanggal_publikasi">Tanggal Publikasi:</label>
        <input type="date" id="edit-tanggal" name="tanggal_publikasi"><br><br>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<script>
    function openEditModal(id, judul, isi, kategori, author, tanggal_publikasi) {
        document.getElementById("edit-id").value = id;
        document.getElementById("edit-judul").value = judul;
        document.getElementById("edit-isi").value = isi;
        document.getElementById("edit-kategori").value = kategori;
        document.getElementById("edit-author").value = author;
        document.getElementById("edit-tanggal").value = tanggal_publikasi;
        document.getElementById("editModal").style.display = "block";
    }
</script>