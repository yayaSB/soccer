<?php
header('Content-Type: application/json');
include '../inc/db.php';

$result = $conn->query("SELECT * FROM players");
$players = [];
while ($row = $result->fetch_assoc()) {
    $players[] = $row;
}
echo json_encode($players);
?>
