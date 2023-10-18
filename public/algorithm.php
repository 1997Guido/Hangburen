<?php

// Calculate the score for a player. If the player failed a turn, the score for that turn is 0.
function calculatePlayerScore($playerData) {
    $totalScore = 0;
    
    $turns = ['One', 'Two', 'Three'];

    foreach ($turns as $turn) {
        if ($playerData["turn{$turn}Fail"] !== true) {
            $totalScore += highScoreAlgorithm(
                $playerData["turn{$turn}Difficulty"],
                $playerData["turn{$turn}Time"],
                $playerData["turn{$turn}Attempts"]
            );
        }
    }

    return $totalScore;
}

/**
 * Calculates a high score based on difficulty, time taken, and errors made.
 *
 * The formula is:
 * H = D * B - (D * T + D * E * P)
 * Where:
 * H = High Score
 * D = Difficulty Factor (easy = 0.5, medium = 1, hard = 2)
 * B = Base Score (1000 points) for completing the word
 * T = Time taken in seconds (Time Penalty)
 * E = Number of Errors made
 * P = Penalty per Error (20 points)
 *
 * @param string $difficulty The difficulty level ('easy', 'medium', 'hard').
 * @param int $timeInSeconds The time taken in seconds.
 * @param int $errors The number of errors made.
 *
 * @return int The high score.
 */
function highScoreAlgorithm($difficulty, $timeInSeconds, $errors) {
    $difficultyFactors = [
        'easy' => 0.5,
        'medium' => 1,
        'hard' => 2
    ];

    $B = 1000;  // Base Score granted for completing the word
    $D = $difficultyFactors[$difficulty];  // Difficulty Factor
    $T = $D * $timeInSeconds;  // Time Penalty adjusted by Difficulty
    $E = $errors;  // Number of Errors
    $P = $D * 20;  // Penalty per Error adjusted by Difficulty

    // Score calculation using the formula
    $H = round($D * $B - ($T + $E * $P));

    return $H;
}

// Get the data from the request
$data = json_decode(file_get_contents("php://input"), true);
$dutchPlayerData = $data['dutchPlayerData'];
$germanPlayerData = $data['germanPlayerData'];

// Calculate the scores
$dutchPlayerScore = calculatePlayerScore($dutchPlayerData);
$germanPlayerScore = calculatePlayerScore($germanPlayerData);

// Create and send the response
$response = [
    'dutchPlayerScore' => $dutchPlayerScore,
    'germanPlayerScore' => $germanPlayerScore
];

echo json_encode($response);

?>
