<?php
$servername = "mariadb";
$username = "root";
$password = "a3b6c9";
$dbname = "Hangman";

try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the database exists
    $dbname = "your_database_name";
    $checkDbStmt = $conn->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :dbname");
    $checkDbStmt->bindParam(':dbname', $dbname);
    $checkDbStmt->execute();

    if ($checkDbStmt->rowCount() == 0) {
        // Create the database if it doesn't exist
        $createDbStmt = $conn->prepare("CREATE DATABASE $dbname");
        $createDbStmt->execute();
        echo "Database created successfully.";
    } else {
        echo "Database already exists. Nothing to do.";

        // Connect to the existing database
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
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

// Close the connection
$conn = null;
?>
