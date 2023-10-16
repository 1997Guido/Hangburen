<?php

$all_words = array(
    "dutchword1" => "germanword1",
    "dutchword2" => "germanword2",
);  // Added missing semicolon

$random_dutch_word = array_rand($all_words);  // Changed $words to $all_words
$german_word = $all_words[$random_dutch_word];  // Changed $words to $all_words

$words = [$random_dutch_word, $german_word];  // Added missing semicolon

echo json_encode(array($words));
?>