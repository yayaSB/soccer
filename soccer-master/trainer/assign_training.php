<?php
session_start();
include '../inc/db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $player_id = $_POST['player_id'];
    $training_id = $_POST['training_id'];
    $training_datetime = $_POST['training_datetime']; // The training date and time

    // Update the player record with the assigned training and datetime
    $query = "UPDATE players 
              SET assigned_training = ?, training_datetime = ? 
              WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isi", $training_id, $training_datetime, $player_id);

    if ($stmt->execute()) {
        // Success, redirect or show message
        header("Location: dashboard.php?success=1");
    } else {
        // Error handling
        echo "Error: " . $stmt->error;
    }
}
?>
