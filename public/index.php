<!DOCTYPE html>
<html>
<head>
  <title>Hangman Game</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
  <h1>Hangman Game</h1>
  <div id="wordToGuess"></div>
  <div id="keyboard"></div>
  <button onclick="newGame()">New Game</button>
  <div id="hangman">
  <div id="head" class="hide"></div>
  <div id="body" class="hide"></div>
  <div id="leftArm" class="hide"></div>
  <div id="rightArm" class="hide"></div>
  <div id="leftLeg" class="hide"></div>
  <div id="rightLeg" class="hide"></div>
</div>
  <script src="js/hangman/game.js"></script>
</body>
</html>