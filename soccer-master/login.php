<?php
session_start();
include 'inc/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] == 'admin') {
                header("Location: admin/dashboard.php"); 
                exit();
            } elseif ($user['role'] == 'trainer') {
                $_SESSION['trainer_id'] = $user['id'];
                header("Location: trainer/dashboard.php"); 
                exit();
            } elseif ($user['role'] == 'player') {
                $_SESSION['player_id'] = $user['id']; // Store player ID in session
                header("Location: player/dashboard.php");
                exit();
            } else {
                // If no valid role is found, redirect to index
                header("Location: index.php");
                exit();
            }
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - Soccer</title>
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
               
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="hero overlay" style="background-image: url('images/anfield.jpg');">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 ml-auto text-center">
                    <h1 class="text-white">Login</h1>
                    <p class="text-white">Access your account to explore and manage.</p>
                </div>
            </div>
        </div>
    </div>
   

    <!-- Login Form -->
    <div class="container site-section mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="bg-light p-4 rounded shadow">
                    <h2 class="text-center mb-4">Login</h2>
                    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    <form method="POST">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                        <p class="text-center mt-3">Don't have an account? <a href="register.php">Register</a></p> <!-- Link to Register Page -->
                        </div>

                         
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
