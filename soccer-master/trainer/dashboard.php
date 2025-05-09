<?php
session_start();
include '../inc/db.php';  // Include the database connection file
include '../inc/header.php';  // Include the header file

// Fetch all training programs for the dropdown
$queryTrainings = "SELECT * FROM trainings";
$trainingsResult = $conn->query($queryTrainings);

// Get the selected training ID from the form
$training_id = isset($_GET['training_id']) ? $_GET['training_id'] : ''; // Empty if not set

// Build the query with the training filter
$queryPlayers = "SELECT p.*, t.title AS training_name
                 FROM players p
                 LEFT JOIN trainings t ON p.assigned_training = t.id
                 WHERE 1";  // Always true condition to start the query

// Add the training filter if a training program is selected
if (!empty($training_id)) {
    $queryPlayers .= " AND p.assigned_training = '$training_id'";
}

// Fetch players based on the selected filters
$playersResult = $conn->query($queryPlayers);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Trainer Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Add custom styles for the modal -->
    <style>
        /* Custom styling for the dropdown inside the modal */
        .modal-content {
            background-color: #343a40; /* Dark background for the modal */
            color: #ffffff; /* White text color for better visibility */
        }

        .modal-header {
            background-color: #007bff; /* Blue header for the modal */
            color: #ffffff;
        }

        .modal-body {
            background-color: #f8f9fa; /* Light background for the modal body */
            color: #000000; /* Black text for content */
        }

        .form-control {
            background-color: #f8f9fa; /* Light background for the form inputs */
            color: #000000; /* Black text */
            border-color: #ccc; /* Border color */
        }

        /* Styling for the dropdown options */
        #training_id_modal, #training_id {
            background-color: #ffffff; /* White background */
            color: #000000; /* Black text */
            border: 1px solid #ccc; /* Border for the dropdown */
        }

        #training_id_modal option, #training_id option {
            background-color: #ffffff; /* White background for options */
            color: #000000; /* Black text for options */
        }

        /* Hover effect to improve visibility */
        #training_id_modal option:hover, #training_id option:hover {
            background-color: #007bff; /* Blue background on hover */
            color: #ffffff; /* White text on hover */
        }

        /* Custom button styling */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="site-wrap">
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
                            <li><a href="../logout.php" class="nav-link">Logout</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <div class="hero overlay" style="background-image: url('../images/bg_3.jpg'); height: 100vh; background-size: cover; background-position: center;">
        <div class="container" style="height: 100%; display: flex; justify-content: center; align-items: center;">
            <div class="row align-items-center text-center text-white">
                <div class="col-lg-6 ml-auto">
                    <h1>Manage Players and Trainings</h1>
                    <p>Select a training program to filter players</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> (Trainer)</h1>
        <p>You have trainer privileges.</p>

        <h2>Select Training Program</h2>
        <form method="GET" action="dashboard.php">
            <div class="form-group">
                <label for="training_id">Choose Training Program:</label>
                <select name="training_id" id="training_id" class="form-control">
                    <option value="">Select Training Program</option>
                    <?php while ($training = $trainingsResult->fetch_assoc()): ?>
                        <option value="<?php echo $training['id']; ?>" <?php echo $training['id'] == $training_id ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($training['title']); ?> <!-- Changed here from 'training_name' to 'title' -->
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">View Players</button>
        </form>

        <h2>Players in Selected Training Program</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Player Name</th>
                    <th>Position</th>
                    <th>Age</th>
                    <th>Assigned Training</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($playersResult->num_rows > 0) {
                    while ($player = $playersResult->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($player['name']); ?></td>
                            <td><?php echo htmlspecialchars($player['position']); ?></td>
                            <td><?php echo htmlspecialchars($player['age']); ?></td>
                            
                            <td><?php echo htmlspecialchars($player['training_name']); ?></td> <!-- Changed here from 'training_name' to 'title' -->
                            <td><button type="button" class="btn btn-info" onclick="assignTraining(<?php echo $player['id']; ?>)">Assign Training</button></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='6'>No players found based on the selected training program.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for Assigning Training -->
    <div class="modal fade" id="assignTrainingModal" tabindex="-1" role="dialog" aria-labelledby="assignTrainingModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignTrainingModalLabel">Assign Training</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="assign_training.php">

                        <input type="hidden" id="player_id" name="player_id" value="">

                        <!-- Training Program Dropdown -->
                        <div class="form-group">
                            <label for="training_id_modal">Choose Training:</label>
                            <select name="training_id" id="training_id_modal" class="form-control">
                                <?php
                                // Loop through all trainings again for the modal dropdown
                                $trainingsResult->data_seek(0);  // Reset the pointer to the beginning
                                while ($training = $trainingsResult->fetch_assoc()) {
                                    echo '<option value="' . $training['id'] . '">' . htmlspecialchars($training['title']) . '</option>';  // Changed here from 'training_name' to 'title'
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Training Date and Time -->
                        <div class="form-group">
                            <label for="training_datetime">Choose Date and Time:</label>
                            <input type="datetime-local" name="training_datetime" id="training_datetime" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Assign Training</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <!-- Add footer content here -->
    </footer>
</div>

<script>
    // Trigger the modal with player ID
    function assignTraining(playerId) {
        // Set the player ID in the form
        document.getElementById('player_id').value = playerId;
        // Show the modal
        $('#assignTrainingModal').modal('show');
    }
</script>

</body>
</html>
