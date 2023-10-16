<?php
// Database connection
$servername = "mariadb";
$username = "root";
$password = "";
$dbname = "woord";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve Dutch and German words from the form
$dutch_word = $_POST["dutch_word"];
$german_word = $_POST["german_word"];

// Insert into the database
$sql = "INSERT INTO word_translations (dutch_word, german_word) VALUES ('$dutch_word', '$german_word')";

if ($conn->query($sql) === TRUE) {
    echo "Words submitted successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
