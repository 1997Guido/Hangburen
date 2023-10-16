<?php
// Sample array, you can add German and Dutch words here.
$all_words = array(
    "dutchword1" => "germanword1",
    "dutchword2" => "germanword2",
);

$random_dutch_word = array_rand($all_words);
$german_word = $all_words[$random_dutch_word];

$words = [$random_dutch_word, $german_word];

echo json_encode(array("words" => $words));
?>
