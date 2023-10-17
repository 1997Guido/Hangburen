<?php
// Database connection parameters
$servername = "your_server_name";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if data is sent as JSON
    $input = json_decode(file_get_contents('php://input'), true);

    if ($input) {
        $username = $input["username"];
        $highscore = $input["highscore"];

        $stmt = $conn->prepare("SELECT highscore FROM game_scores WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $currentHighscore = $row['highscore'];

            if ($highscore > $currentHighscore) {
                $updateStmt = $conn->prepare("UPDATE game_scores SET highscore = :highscore WHERE username = :username");
                $updateStmt->bindParam(':highscore', $highscore);
                $updateStmt->bindParam(':username', $username);
                if ($updateStmt->execute()) {
                    echo "Highscore updated successfully!";
                } else {
                    echo "Error updating highscore: " . implode(" ", $updateStmt->errorInfo());
                }
            } else {
                echo "No update needed. Existing highscore is higher.";
            }
        } else {
            $insertStmt = $conn->prepare("INSERT INTO game_scores (username, highscore) VALUES (:username, :highscore)");
            $insertStmt->bindParam(':username', $username);
            $insertStmt->bindParam(':highscore', $highscore);
            if ($insertStmt->execute()) {
                echo "Highscore added successfully!";
            } else {
                echo "Error adding highscore: " . implode(" ", $insertStmt->errorInfo());
            }
        }
    } else {
        echo "Invalid JSON data received.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $stmt = $conn->prepare("SELECT username, highscore FROM game_scores ORDER BY highscore DESC");
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "Highscores:<br>";
        while ($row = $stmt->fetch()) {
            echo $row['username'] . ": " . $row['highscore'] . "<br>";
        }
    } else {
        echo "No highscores found in the database.";
    }
}

$conn = null;
?>
