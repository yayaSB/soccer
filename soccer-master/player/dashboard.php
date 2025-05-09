<?php
session_start();
include '../inc/db.php'; 

// Ensure player is logged in
if (!isset($_SESSION['player_id'])) {
    header("Location: ../login.php");
    exit();
}

$player_id = $_SESSION['player_id'];

// Fetch assigned training for the player
$queryPlayers = "SELECT p.id, p.name AS player_name, p.position, p.age, t.title AS training_name
                 FROM players p
                 LEFT JOIN trainings t ON p.assigned_training = t.id
                 WHERE p.id = ?";  // Filter by player ID

$stmt = $conn->prepare($queryPlayers);
$stmt->bind_param("i", $player_id);  // Bind player_id to the query
$stmt->execute();
$result = $stmt->get_result();
$player = $result->fetch_assoc();

// Default values if no training is assigned
$player_name = $player['player_name'] ?? 'Player';
$assigned_training = $player['training_name'] ?? 'No training assigned';

// Fetch available training programs (adjusted to reflect correct column name)
$queryAvailable = "SELECT t.title FROM trainings t";  // Assuming the column is 'title'
$availableTrainings = $conn->query($queryAvailable);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Player Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="site-wrap">
    <header class="site-navbar py-4" role="banner">
        <div class="container">
            <div class="d-flex align-items-center">
                <div class="site-logo">
                    <a href="../index.php"><img src="../images/logo.png" alt="Logo"></a>
                </div>
                <div class="ml-auto">
                    <nav class="site-navigation position-relative text-right">
                        <ul class="site-menu main-menu js-clone-nav mr-auto d-none d-lg-block">
                            <li><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                            <li><a href="../logout.php" class="nav-link">Logout</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <div class="hero overlay" style="background-image: url('../images/bg_3.jpg'); height: 100vh;">
        <div class="container text-center text-white d-flex align-items-center justify-content-center" style="height: 100%;">
            <h1>Welcome, <?php echo htmlspecialchars($player_name); ?>!</h1>
        </div>
    </div>

    <div class="container py-5">
        <h2>Assigned Training Program</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Training Name</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($assigned_training); ?></td>
                </tr>
            </tbody>
        </table>

        <h2>Available Training Programs</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Training Name</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($training = $availableTrainings->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($training['title']); ?></td>  <!-- Adjusted to 'title' -->
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
