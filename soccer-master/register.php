<?php
session_start();
include 'inc/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = 'user'; // Default role for registration is "user"

    // Validation
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if the username already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username already taken.";
        } else {
            // Insert new user into the database
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashed_password, $role);

            if ($stmt->execute()) {
                $success = "Account created successfully! You can now log in.";
            } else {
                $error = "Error: " . $stmt->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register - Soccer</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="site-wrap">

    <!-- Header -->
    <header class="site-navbar py-4" role="banner">
        <div class="container">
            <div class="d-flex align-items-center">
                <div class="site-logo">
                    <a href="index.php">
                        <img src="images/logo.png" alt="Logo">
                    </a>
                </div>
                <div class="ml-auto">
                    <nav class="site-navigation position-relative text-right" role="navigation">
                        <ul class="site-menu main-menu js-clone-nav mr-auto d-none d-lg-block">
                            <li><a href="index.php" class="nav-link">Home</a></li>
                            <li><a href="login.php" class="nav-link">Login</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="hero overlay" style="background-image: url('images/anfield.jpg');">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 ml-auto text-center">
                    <h1 class="text-white">Register</h1>
                    <p class="text-white">Create your account to join the platform.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Form -->
    <div class="container site-section mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="bg-light p-4 rounded shadow">
                    <h2 class="text-center mb-4">Register</h2>
                    <?php
                    if (isset($error)) {
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                    if (isset($success)) {
                        echo "<div class='alert alert-success'>$success</div>";
                    }
                    ?>
                    <form method="POST">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm your password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                        <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div> <!-- End of Site Wrap -->

<!-- Scripts -->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>

</body>
</html>
