<?php
header("Content-Type: application/json");

$servername = "mariadb";
$username = "root";
$password = "a3b6c9";
$dbname = "Hangman";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["error" => "Connection failed: " . $e->getMessage()]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents('php://input'), true);

    if ($input) {
        saveScore($conn, $input['germanPlayername'], $input['germanPlayerScore']);
        saveScore($conn, $input['dutchPlayername'], $input['dutchPlayerScore']);
        echo json_encode(["message" => "Scores processed successfully!"]);
    } else {
        echo json_encode(["error" => "Invalid JSON data received."]);
    }
}

function saveScore($conn, $username, $score) {
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
            $updateStmt->execute();
        }
    } else {
        $insertStmt = $conn->prepare("INSERT INTO game_scores (username, score) VALUES (:username, :score)");
        $insertStmt->bindParam(':username', $username);
        $insertStmt->bindParam(':score', $score);
        $insertStmt->execute();
    }
}

$conn = null;
?>
