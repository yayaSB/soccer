<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Include database connection
include '../inc/db.php';

// Handle form submission for adding a player
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $assigned_training = $_POST['assigned_training'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Start transaction to ensure both inserts succeed
    $conn->begin_transaction();

    try {
        // Insert into `players` table
        $query = "INSERT INTO players (name, email, assigned_training) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $name, $email, $assigned_training);
        $stmt->execute();

        // Get the last inserted player ID
        $player_id = $conn->insert_id;

        // Insert into `users` table with role 'player'
        $queryUser = "INSERT INTO users (email, password, role, player_id) VALUES (?, ?, 'player', ?)";
        $stmtUser = $conn->prepare($queryUser);
        $stmtUser->bind_param("ssi", $email, $hashed_password, $player_id);
        $stmtUser->execute();

        // Commit the transaction
        $conn->commit();

        // Success message
        $message = "Player added successfully!";
        $message_type = "success";
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        $message = "Error adding player: " . $e->getMessage();
        $message_type = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Add Player - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
    <script>
        // Function to hide alert after 3 seconds
        function hideAlert() {
            setTimeout(function() {
                document.getElementById('alert').style.display = 'none';
            }, 3000);
        }
    </script>
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
                            <li><a href="admin_add_player.php" class="nav-link">Add Player</a></li>
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

    <!-- Add Player Form -->
    <div class="container my-5">
        <?php if (isset($message)): ?>
            <div id="alert" class="alert alert-<?php echo $message_type; ?>" role="alert">
                <?php echo $message; ?>
            </div>
            <script>hideAlert();</script>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Add New Player</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="admin_add_player.php">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="assigned_training">Assigned Training:</label>
                                <select id="assigned_training" name="assigned_training" class="form-control" required>
                                    <option value="">Select a training</option>
                                    <?php
                                    // Fetch all available trainings
                                    $queryTrainings = "SELECT * FROM trainings";
                                    $resultTrainings = $conn->query($queryTrainings);

                                    if ($resultTrainings->num_rows > 0) {
                                        while ($training = $resultTrainings->fetch_assoc()) {
                                            echo '<option value="' . $training['id'] . '">' . htmlspecialchars($training['training_name']) . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">No trainings available</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Add Player</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="../js/jquery-3.3.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/main.js"></script>

</body>
</html>
