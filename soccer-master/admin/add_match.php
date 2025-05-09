<?php
session_start();
include '../inc/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $team_1 = $_POST['team_1'];
    $team_2 = $_POST['team_2'];
    $match_date = $_POST['match_date'];
    $match_time = $_POST['match_time'];
    $venue = $_POST['venue'];
    $league = $_POST['league'];

    // Handle file uploads
    $team_1_logo = $_FILES['team_1_logo']['name'];
    $team_2_logo = $_FILES['team_2_logo']['name'];

    // File upload directory
    $uploadDir = '../images/';
    $team_1_logo_path = $uploadDir . basename($team_1_logo);
    $team_2_logo_path = $uploadDir . basename($team_2_logo);

    // Move uploaded files to the directory
    if (move_uploaded_file($_FILES['team_1_logo']['tmp_name'], $team_1_logo_path) &&
        move_uploaded_file($_FILES['team_2_logo']['tmp_name'], $team_2_logo_path)) {
        
        // Insert the match data into the database
        $sql = "INSERT INTO matches (team_1, team_2, match_date, match_time, venue, league, team_1_logo, team_2_logo) 
                VALUES ('$team_1', '$team_2', '$match_date', '$match_time', '$venue', '$league', '$team_1_logo', '$team_2_logo')";

        if ($conn->query($sql) === TRUE) {
            echo 'success';
        } else {
            echo 'Error: ' . $conn->error;
        }
    } else {
        echo 'Error uploading files';
    }
} else {
    echo 'Invalid request';
}
?>
