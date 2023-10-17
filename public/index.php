<!DOCTYPE html>
<html>

<head>
    <title>Hangman Game</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div class="screen-overlay">
        <div class="returnHome"><a href="./login.php">Home</a>
        </div>
        <div class="game-box">
            <h1>Hangman Game</h1>
            <div id="wordToGuess"></div>
            <div id="hangman">
                <div id="head" class="hide"></div>
                <div id="body" class="hide"></div>
                <div id="leftArm" class="hide"></div>
                <div id="rightArm" class="hide"></div>
                <div id="leftLeg" class="hide"></div>
                <div id="rightLeg" class="hide"></div>
            </div>
            <div id="keyboard"></div>
            <button onclick="newGame()">New Game</button>
        </div>

    </div>


</body>

</html>