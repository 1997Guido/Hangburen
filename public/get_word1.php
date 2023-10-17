<?php
// Database connection using PDO
$servername = "mariadb";
$username = "root";
$password = "a3b6c9";
$dbname = "Hangman";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve all IDs from the word_translations table
    $sql = "SELECT id FROM word_translations";
    $stmt = $conn->query($sql);
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN, 0); // Fetch IDs into an array

    if (count($ids) > 0) {
        // Select a random ID from the array
        $randomId = $ids[array_rand($ids)];

        // Query the database for the entry with the selected ID
        $sql = "SELECT * FROM word_translations WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $randomId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Create an array with the selected data
        $words = array($result['dutch_word'], $result['german_word'], $result['difficulty']
        );
    // Encode the selected word data as JSON and echo it
    echo json_encode($words);
        // Now you have the selected word data in the $selectedWordData array
        // You can use this array in a separate file or for your game logic.
    } else {
        // Handle the case where no records are found in the word_translations table.
    }
} catch (PDOException $e) {
    // Handle database connection errors.
}

// Close the database connection
$conn = null;
?>


