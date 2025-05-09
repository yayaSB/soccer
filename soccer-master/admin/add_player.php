<?php
session_start();


include '../inc/db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $age = $_POST['age'];
    $stats = $_POST['stats'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageName = basename($_FILES['image']['name']);
        $targetFilePath = "../images/" . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath);
    } else {
        $imageName = null;
    }

    $stmt = $conn->prepare("INSERT INTO players (name, position, age, stats, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $name, $position, $age, $stats, $imageName);

    if ($stmt->execute()) {
        $success = "Player added successfully!";
    } else {
        $error = "Error adding player: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Player</title>
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
                            <li><a href="add_player.php" class="nav-link active">Add Player</a></li>
                            <li><a href="delete_player.php" class="nav-link">Delete Player</a></li>
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
                    <h1 class="text-white">Add Player</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php elseif (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Player Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="position">Position</label>
                        <input type="text" class="form-control" id="position" name="position" required>
                    </div>
                    <div class="form-group">
                        <label for="age">Age</label>
                        <input type="number" class="form-control" id="age" name="age" required>
                    </div>
                    <div class="form-group">
                        <label for="stats">Player Stats</label>
                        <textarea class="form-control" id="stats" name="stats" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">Player Image</label>
                        <input type="file" class="form-control-file" id="image" name="image">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Player</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="../js/jquery-3.3.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>
