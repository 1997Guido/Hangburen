<?php

$servername = "mariadb";
$username = "root";
$password = "a3b6c9";
$dbname = "Hangman";

// Connect to the database
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_FILES['csvfile']) && $_FILES['csvfile']['error'] == 0) {
    $file = $_FILES['csvfile']['tmp_name'];
    $handle = fopen($file, "r");
    
    // Skip the first line if it contains headers
    fgetcsv($handle);

    // Prepare the SQL statement
    $sql = "INSERT INTO word_translations (dutch_word, german_word, difficulty) VALUES (:dutch_word, :german_word, :difficulty)";
    $stmt = $conn->prepare($sql);

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if (count($data) == 3) {
            $dutch_word = $data[0];
            $german_word = $data[1];
            $difficulty = $data[2];

            // Bind parameters and execute the statement
            $stmt->bindParam(':dutch_word', $dutch_word);
            $stmt->bindParam(':german_word', $german_word);
            $stmt->bindParam(':difficulty', $difficulty);
            $stmt->execute();
        }
    }

    fclose($handle);
    echo "Data imported successfully!";
} else {
    echo "Error uploading file.";
}

// Close the database connection
$conn = null;

?>
