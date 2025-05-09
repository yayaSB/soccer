<?php
    session_start();
 
    // Database connection
    require 'db_connect.php';
    
    $player_id = $_SESSION['player_id'];
    
    // Fetch player's training sessions
    $query = "SELECT * FROM training_sessions WHERE player_id = ? ORDER BY date DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $player_id);
    $stmt->execute();
    $result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Training</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header -->
    <header class="py-3 bg-success text-white">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="m-0">SportSync</h1>
            <nav>
                <ul class="nav">
                    <li class="log"><a href="logout.php" class="nav-link text-white">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <!-- Training Schedule -->
    <div class="container mt-4">
        <h2 class="text-center text-white">Your Training Sessions</h2>
        <table class="table table-striped table-dark mt-3">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Coach</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['time']); ?></td>
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                        <td><?php echo htmlspecialchars($row['coach_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['details']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
