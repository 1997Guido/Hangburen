<?php

function calculatePlayerScore($playerData) {
    $totalScore = 0;
    
    if ($playerData['turnOneFail'] == true) {
        $totalScore += 0;
    } else {
        $totalScore += highScoreAlgorithm($playerData['turnOneDifficulty'], $playerData['turnOneTime'], $playerData['turnOneAttempts']);
    }

    if ($playerData['turnTwoFail'] == true) {
        $totalScore += 0;
    } else {
        $totalScore += highScoreAlgorithm($playerData['turnTwoDifficulty'], $playerData['turnTwoTime'], $playerData['turnTwoAttempts']);
    }

    if ($playerData['turnThreeFail'] == true) {
        $totalScore += 0;
    } else {
        $totalScore += highScoreAlgorithm($playerData['turnThreeDifficulty'], $playerData['turnThreeTime'], $playerData['turnThreeAttempts']);
    }

    return $totalScore;
}

function highScoreAlgorithm($difficulty, $timeInSeconds, $errors) {
    $difficultyFactors = [
        'easy' => 0.5,
        'medium' => 1,
        'hard' => 2
    ];

    if (!isset($difficultyFactors[$difficulty])) {
        return "Invalid difficulty level!";
    }

    $wordCompletionBonus = 1000;
    $timePenalty = $timeInSeconds;
    $errorPenalty = 20 * $errors;

    $highScore = ($difficultyFactors[$difficulty] * $wordCompletionBonus) - ($timePenalty + $errorPenalty);

    return $highScore;
}

$data = json_decode(file_get_contents("php://input"), true);
$dutchPlayerData = $data['dutchPlayerData'];
$germanPlayerData = $data['germanPlayerData'];

$dutchPlayerScore = calculatePlayerScore($dutchPlayerData);
$germanPlayerScore = calculatePlayerScore($germanPlayerData);

$response = [
    'dutchPlayerScore' => $dutchPlayerScore,
    'germanPlayerScore' => $germanPlayerScore
];

echo json_encode($response);

?>
