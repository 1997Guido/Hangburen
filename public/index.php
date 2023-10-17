<!DOCTYPE html>
<html lang="en">

<head>
    <title>Hangman Game</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="icon" href="./images/logo-trans.png">
</head>

<body>
    <div class="screen-overlay">
        <a href="./login.php" class="return-btn"><img class="icon" src="https://img.icons8.com/material-outlined/24/000000/return.png" /></a>
        <div id="game-box" class="game-box">
            <div class="topcontainer">
                <h1>Hangman</h1>
                <div id="whoseTurn"></div>
                <div id="untranslatedWord"></div>
            </div>
            <div class="middelconatiner">
                <div class="dutchcontainer">
                    <h2>Dutch stats</h2>
                    <div id="dutchPlayerTurns"></div>
                </div>
                <div id="hangman">
                    <div id="head" class="hide"></div>
                    <div id="body" class="hide"></div>
                    <div id="leftArm" class="hide"></div>
                    <div id="rightArm" class="hide"></div>
                    <div id="leftLeg" class="hide"></div>
                    <div id="rightLeg" class="hide"></div>
                </div>
                <div class="germancontainer">
                    <h2>Dutch stats</h2>
                    <div id="germanPlayerTurns"></div>
                </div>

            </div>
            <div id="wordToGuess"></div>
            <div id="currentAttempts"></div>
            <div id="keyboard"></div>
            <button id="newGame" onclick="newGame()">!New Game!</button>
        </div>
        <div id="end-box" class="hide">Javascript needs to insert the winner and scores here after calculating</div>

    </div>


</body>
<script src="js/hangman/game.js"></script>

</html>