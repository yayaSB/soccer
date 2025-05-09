<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Include database connection
include('../inc/db.php');

// Add blog functionality
if (isset($_POST['add_blog'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_POST['author'];
    $image = $_FILES['image']['name'];

    // Move uploaded file to the images directory
    if (move_uploaded_file($_FILES['image']['tmp_name'], "../images/" . $image)) {
        // Insert blog into the database
        $stmt = $conn->prepare("INSERT INTO blogs (title, content, author, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $content, $author, $image);
        $stmt->execute();
        echo "<script>alert('Blog added successfully!');</script>";
    } else {
        echo "<script>alert('Failed to upload image.');</script>";
    }
}

// Delete blog functionality
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Fetch the blog to delete the image file
    $result = $conn->query("SELECT image FROM blogs WHERE id = $id");
    $blog = $result->fetch_assoc();

    if ($blog) {
        $imagePath = "../images/" . $blog['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath); // Delete the image file
        }

        // Delete blog from database
        $conn->query("DELETE FROM blogs WHERE id = $id");
        echo "<script>alert('Blog deleted successfully!');</script>";
    } else {
        echo "<script>alert('Blog not found.');</script>";
    }
}

// Fetch blogs from the database
$blogs = $conn->query("SELECT * FROM blogs");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Blogs</title>
    <link rel="stylesheet" href="../css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/jquery-3.3.1.min.js"></script>
</head>
<body>
<div class="site-wrap">
    <!-- Admin Header -->
    <header class="site-navbar py-4" role="banner">
        <div class="container">
            <div class="d-flex align-items-center">
                <div class="site-logo">
                    <a href="../index.php">
                        <img src="../images/logo.png" alt="Logo">
                    </a>
                </div>
                <div class="ml-auto">
                    <nav class="site-navigation position-relative text-right" role="navigation">
                        <ul class="site-menu main-menu js-clone-nav mr-auto d-none d-lg-block">
                            <li><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                            <li><a href="manage_users.php" class="nav-link">Manage Users</a></li>
                            <li><a href="manage_players.php" class="nav-link">Manage Players</a></li>
                            <li><a href="manage_blogs.php" class="nav-link">Manage Blogs</a></li>
                            <li><a href="manage_matches.php" class="nav-link">Manage Matches</a></li>
                            <li><a href="../logout.php" class="nav-link">Logout</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="hero overlay" style="background-image: url('../images/bg_3.jpg'); height: 100vh; background-size: cover; background-position: center;">
        <div class="container" style="height: 100%; display: flex; justify-content: center; align-items: center;">
            <div class="row align-items-center text-center text-white">
                <div class="col-lg-6 ml-auto">
                    <h1>Manage blogs</h1>
                    <p>Manage all blogs in the system</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <!-- Add Blog Form -->
        <h2 class="mb-4">Add New Blog</h2>
        <form method="POST" enctype="multipart/form-data" class="bg-light p-4 rounded">
            <div class="form-group">
                <input type="text" name="title" class="form-control" placeholder="Title" required>
            </div>
            <div class="form-group">
                <textarea name="content" class="form-control" placeholder="Content" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <input type="text" name="author" class="form-control" placeholder="Author" required>
            </div>
            <div class="form-group">
                <input type="file" name="image" class="form-control" required>
            </div>
            <button type="submit" name="add_blog" class="btn btn-primary">Add Blog</button>
        </form>

        <hr class="my-5">

        <!-- Display Blogs -->
        <h2 class="mb-4">Blogs List</h2>
        <div class="row">
            <?php while ($blog = $blogs->fetch_assoc()) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="../images/<?= $blog['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($blog['title']) ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($blog['title']) ?></h5>
                            <p class="card-text"><strong>Author:</strong> <?= htmlspecialchars($blog['author']) ?></p>
                            <p class="card-text"><?= nl2br(htmlspecialchars($blog['content'])) ?></p>
                            <a href="?delete=<?= $blog['id'] ?>" class="btn btn-danger">Delete</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<script src="../js/bootstrap.min.js"></script>
</body>
</html>
