<?php
// Database connection using PDO
$servername = "mariadb";
$username = "root";
$password = "a3b6c9";
$dbname = "Hangman";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve Dutch and German words, and the selected difficulty from the form
    $dutch_word = $_POST["dutch_word"];
    $german_word = $_POST["german_word"];
    $difficulty = $_POST["difficulty"];

    // Prepare the SQL statement
    $sql = "INSERT INTO word_translations (dutch_word, german_word, difficulty) VALUES (:dutch_word, :german_word, :difficulty)";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':dutch_word', $dutch_word);
    $stmt->bindParam(':german_word', $german_word);
    $stmt->bindParam(':difficulty', $difficulty);

    // Execute the statement
    $stmt->execute();

    echo "Words submitted successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$conn = null;
?>


