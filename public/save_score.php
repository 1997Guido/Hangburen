<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hangman";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$score = $_POST['score'];

$sql = "INSERT INTO high_scores (username, score) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $username, $score);
$stmt->execute();

echo json_encode(["status" => "success"]);
?>