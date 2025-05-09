<?php
session_start();

// Ensure only admins can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Include database connection
include '../inc/db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $image = $_FILES['image']['name'] ?? '';

    if ($title && $content && $image) {
        $target_dir = "../images/";
        $target_file = $target_dir . basename($image);

        // Move uploaded file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Insert blog post into the database
            $stmt = $conn->prepare("INSERT INTO blogs (title, content, image, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param('sss', $title, $content, $image);
            if ($stmt->execute()) {
                $message = 'Blog added successfully!';
            } else {
                $message = 'Error adding blog.';
            }
        } else {
            $message = 'Error uploading image.';
        }
    } else {
        $message = 'Please fill all fields.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add Blog - Admin Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
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
                            <li><a href="add_match.php" class="nav-link">Add Match</a></li>
                            <li><a href="delete_match.php" class="nav-link">Delete Match</a></li>
                            <li><a href="add_blog.php" class="nav-link active">Add Blog</a></li>
                            <li><a href="../logout.php" class="nav-link">Logout</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="hero overlay" style="background-image: url('../images/bg_3.jpg');">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 ml-auto text-center">
                    <h1 class="text-white">Add Blog</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Blog Form -->
    <div class="container my-5">
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="" method="POST" enctype="multipart/form-data" class="bg-light p-4 rounded">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="Blog Title" required>
                    </div>
                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea id="content" name="content" rows="5" class="form-control" placeholder="Blog Content" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Add Blog</button>
                </form>
            </div>
        </div>
    </div>

</div>
<script src="../js/jquery-3.3.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/main.js"></script>
</body>
</html>
