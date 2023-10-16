<?php

/**
 * Calculate the high score for a given difficulty level, time, and average errors.
 *
 * @param string $difficulty
 * @param int $timeInSeconds
 * @param int $averageErrors
 * @return int|string
 */

 /*
    This custom Algorithm is based on the following formula:
    High Score = (Difficulty Factor * Word Completion Bonus) - (Time Penalty + Error Penalty)

    Difficulty Factor:
        Easy: 1
        Medium: 1.5
        Hard: 2

    Word Completion Bonus:
        10 words * 100 points each = 1000 points

    Time Penalty:
        1 second = 1 point
        
    Error Penalty:
        20 points * average errors * 10 words

    Example:
        Difficulty: Medium
        Time: 300 seconds
        Average Errors: 2

        High Score = (1.5 * 1000) - (300 + (20 * 2 * 10))
                   = 1500 - (300 + 400)
                   = 1500 - 700
                   = 800 points
 */

/*
    This function expects the following parameters:
    $difficulty: string
    $timeInSeconds: int
    $averageErrors: int

    $difficulty can be 'easy', 'medium', or 'hard'
    $timeInSeconds is the time it took the user to complete the game
    $averageErrors is the average number of errors the user made per word

    The function returns the high score as an integer
*/

function highScoreAlgorithm($difficulty, $timeInSeconds, $averageErrors) {
    // Define difficulty factors
    $difficultyFactors = [
        'easy' => 1,
        'medium' => 1.5,
        'hard' => 2
    ];

    // Check if difficulty is valid
    if (!isset($difficultyFactors[$difficulty])) {
        return "Invalid difficulty level!";
    }

    $wordCompletionBonus = 10 * 100;  // 10 words * 100 points each
    $timePenalty = $timeInSeconds;
    $errorPenalty = 20 * $averageErrors * 10;  // 20 points * average errors * 10 words
    // Calculate high score
    // High Score = (Difficulty Factor * Word Completion Bonus) - (Time Penalty + Error Penalty)
    // Example: High Score = (1.5 * 1000) - (300 + (20 * 2 * 10))
    $highScore = ($difficultyFactors[$difficulty] * $wordCompletionBonus) - ($timePenalty + $errorPenalty);

    return $highScore;
}

// Example usage:
$difficulty = "medium"; // can be 'easy', 'medium', or 'hard'
$timeInSeconds = 300;   // example value
$averageErrors = 2;     // example value

echo highScoreAlgorithm($difficulty, $timeInSeconds, $averageErrors);

?>