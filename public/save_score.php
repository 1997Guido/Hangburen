<?php
$servername = "mariadb";
$username = "root";
$password = "a3b6c9";
$dbname = "Hangman";

try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the database exists
    $checkDbStmt = $conn->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :dbname");
    $checkDbStmt->bindParam(':dbname', $dbname);
    $checkDbStmt->execute();

    if ($checkDbStmt->rowCount() == 0) {
        $createDbStmt = $conn->prepare("CREATE DATABASE $dbname");
        $createDbStmt->execute();
        echo "Database created successfully.";
    } else {
        echo "Database already exists. Nothing to do.";
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    // Create the game_scores table if it doesn't exist
    $createTableStmt = $conn->prepare("
    CREATE TABLE IF NOT EXISTS game_scores (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        score INT NOT NULL
    )
    ");
    $createTableStmt->execute();

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function handleScore($conn, $username, $score) {
    $stmt = $conn->prepare("SELECT score FROM game_scores WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $currentScore = $row['score'];

        if ($score > $currentScore) {
            $updateStmt = $conn->prepare("UPDATE game_scores SET score = :score WHERE username = :username");
            $updateStmt->bindParam(':score', $score);
            $updateStmt->bindParam(':username', $username);
            if ($updateStmt->execute()) {
                echo "Score updated successfully for $username!";
            } else {
                echo "Error updating score for $username: " . implode(" ", $updateStmt->errorInfo());
            }
        } else {
            echo "No update needed for $username. Existing score is higher.";
        }
    } else {
        $insertStmt = $conn->prepare("INSERT INTO game_scores (username, score) VALUES (:username, :score)");
        $insertStmt->bindParam(':username', $username);
        $insertStmt->bindParam(':score', $score);
        if ($insertStmt->execute()) {
            echo "Score added successfully for $username!";
        } else {
            echo "Error adding score for $username: " . implode(" ", $insertStmt->errorInfo());
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents('php://input'), true);

    if ($input) {
        $germanPlayername = $input["germanPlayername"];
        $germanPlayerScore = $input["germanPlayerScore"];
        $dutchPlayername = $input["dutchPlayername"];
        $dutchPlayerScore = $input["dutchPlayerScore"];

        handleScore($conn, $germanPlayername, $germanPlayerScore);
        handleScore($conn, $dutchPlayername, $dutchPlayerScore);
    } else {
        echo "Invalid JSON data received.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $stmt = $conn->prepare("SELECT username, score FROM game_scores ORDER BY score DESC");
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "Scores:<br>";
        while ($row = $stmt->fetch()) {
            echo $row['username'] . ": " . $row['score'] . "<br>";
        }
    } else {
        echo "No scores found in the database.";
    }
}

$conn = null;
?>