<?php
// Sample array, you can add German and Dutch words here.
$words = array("APFEL", "BANANE", "KEREN", "AUTO");

// Randomly select a word
$random_key = array_rand($words);

echo json_encode(array("word" => $words[$random_key]));
?>