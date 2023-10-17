<!DOCTYPE html>
<html lang="en">

<head>
    <title>Hangman Game</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">

</head>

<body>
    <div class="screen-overlay">
        <a href="./login.php" class="return-btn"><img class="icon" src="https://img.icons8.com/material-outlined/24/000000/return.png" /></a>
        <div class="game-box">
            <div class="topcontainer">
                <h1>Hangman</h1>
                <div id="whoseTurn"></div>
                <div id="untranslatedWord"></div>
            </div>
            <div class="middelconatiner">
                <div class="dutchcontainer">
                    <div id="dutchPlayerTurns"></div>
                    dutch
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
                    <div id="germanPlayerTurns"></div>
                    german
                </div>

            </div>
            <div id="wordToGuess"></div>
            <div id="currentAttempts"></div>
            <div id="keyboard"></div>
            <button onclick="newGame()">New Game</button>
        </div>

    </div>


</body>
<script src="js/hangman/game.js"></script>

</html>