<?php
session_start();

// Check if the user is a trainer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'trainer') {
    header("Location: ../login.php");
    exit();
}

include '../inc/db.php';

// Handle selected player
$selected_player_id = $_GET['player_id'] ?? null;
$player_list = $conn->query("SELECT id, name FROM players");

// Get selected player's data
$player_data = null;
if ($selected_player_id) {
    $stmt = $conn->prepare("
        SELECT name, position, goals, assists, matches, yellow_cards, red_cards,
        ROUND(goals / NULLIF(matches, 0), 2) as goals_per_match
        FROM players WHERE id = ?
    ");
    $stmt->bind_param("i", $selected_player_id);
    $stmt->execute();
    $player_data = $stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trainer Follow</title>
    <link rel="stylesheet" href="../css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container my-5">
    <h2 class="text-center mb-4">Player Data</h2>

    <!-- Player Selection -->
    <form method="get" class="mb-4 text-center">
        <label for="playerSelect" class="form-label">Select a Player:</label>
        <select name="player_id" id="playerSelect" class="form-select w-50 mx-auto" onchange="this.form.submit()">
            <option value="">-- Choose Player --</option>
            <?php while ($player = $player_list->fetch_assoc()) { ?>
                <option value="<?= $player['id']; ?>" <?= ($selected_player_id == $player['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($player['name']); ?>
                </option>
            <?php } ?>
        </select>
    </form>

    <?php if ($player_data): ?>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card p-3">
                    <h4><?= htmlspecialchars($player_data['name']); ?> - <?= htmlspecialchars($player_data['position']); ?></h4>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Goals: <?= $player_data['goals']; ?></li>
                        <li class="list-group-item">Assists: <?= $player_data['assists']; ?></li>
                        <li class="list-group-item">Matches: <?= $player_data['matches']; ?></li>
                        <li class="list-group-item">Yellow Cards: <?= $player_data['yellow_cards']; ?></li>
                        <li class="list-group-item">Red Cards: <?= $player_data['red_cards']; ?></li>
                        <li class="list-group-item">Goals per Match: <?= $player_data['goals_per_match']; ?></li>
                    </ul>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <canvas id="playerChart"></canvas>
            </div>
        </div>

        <script>
            const ctx = document.getElementById('playerChart').getContext('2d');
            const playerChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Goals', 'Assists', 'Matches', 'Yellow Cards', 'Red Cards'],
                    datasets: [{
                        label: 'Player Stats',
                        data: [
                            <?= $player_data['goals']; ?>,
                            <?= $player_data['assists']; ?>,
                            <?= $player_data['matches']; ?>,
                            <?= $player_data['yellow_cards']; ?>,
                            <?= $player_data['red_cards']; ?>
                        ],
                        backgroundColor: ['#28a745', '#007bff', '#6c757d', '#ffc107', '#dc3545']
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    <?php elseif ($selected_player_id): ?>
        <div class="alert alert-warning text-center">Player not found.</div>
    <?php endif; ?>
</div>
</body>
</html>
