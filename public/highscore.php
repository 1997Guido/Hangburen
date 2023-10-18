<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Game Scores</title>
</head>
<body>

<h1>Game Scores</h1>

<?php
$servername = "mariadb";
$username = "root";
$password = "a3b6c9";
$dbname = "Hangman";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT username, score FROM game_scores ORDER BY score DESC");
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "<ul>";
        while ($row = $stmt->fetch()) {
            echo "<li>" . $row['username'] . ": " . $row['score'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "No scores found in the database.";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>

</body>
</html>
