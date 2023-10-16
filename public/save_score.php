<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "a3b6c9";
$dbname = "Hangman";

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=mariadb;dbname=Hangman", "root", "a3b6c9");

    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Collect data from POST request
    $username = $_POST['username'];
    $score = $_POST['score'];

    // SQL query to insert new high score
    $sql = "INSERT INTO high_scores (username, score) VALUES (:username, :score)";

    // Prepare statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':score', $score, PDO::PARAM_INT);

    // Execute the prepared statement
    $stmt->execute();

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'failure', 'error' => $e->getMessage()]);
}

// Close the connection
$conn = null;
?>