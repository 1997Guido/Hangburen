<?php
$servername = "mariadb";
$username = "root";
$password = "a3b6c9";
$dbname = "Hangman";

$response = []; // Initialize response array

try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the database exists
    $dbname = "Hangman";
    $checkDbStmt = $conn->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :dbname");
    $checkDbStmt->bindParam(':dbname', $dbname);
    $checkDbStmt->execute();

    if ($checkDbStmt->rowCount() == 0) {
        $createDbStmt = $conn->prepare("CREATE DATABASE $dbname");
        $createDbStmt->execute();
        $response['message'] = "Database created successfully.";
    } else {
        $response['message'] = "Database already exists. Nothing to do.";

        // Connect to the existing database
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
} catch (PDOException $e) {
    $response['error'] = "Connection failed: " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents('php://input'), true);
    if ($input && isset($input["username"]) && isset($input["highscore"])) {
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
                    $response['message'] = "Highscore updated successfully!";
                } else {
                    $response['error'] = "Error updating highscore: " . implode(" ", $updateStmt->errorInfo());
                }
            } else {
                $response['message'] = "No update needed. Existing highscore is higher.";
            }
        } else {
            $insertStmt = $conn->prepare("INSERT INTO game_scores (username, highscore) VALUES (:username, :highscore)");
            $insertStmt->bindParam(':username', $username);
            $insertStmt->bindParam(':highscore', $highscore);
            if ($insertStmt->execute()) {
                $response['message'] = "Highscore added successfully!";
            } else {
                $response['error'] = "Error adding highscore: " . implode(" ", $insertStmt->errorInfo());
            }
        }
    } else {
        $response['error'] = "Invalid JSON data received.";
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
    $stmt = $conn->prepare("SELECT username, highscore FROM game_scores ORDER BY highscore DESC");
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $scores = [];
        while ($row = $stmt->fetch()) {
            $scores[] = ['username' => $row['username'], 'highscore' => $row['highscore']];
        }
        $response['highscores'] = $scores;
    } else {
        $response['message'] = "No highscores found in the database.";
    }
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);

$conn = null;
?>
