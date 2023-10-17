document.addEventListener("DOMContentLoaded", (event) => {
  newGame();
});
let words = [];
let word = "";
let maskedWord = "";
let attempts = 0;
let gameCount = 0;
let startTime;
let totalTime = 0;

let dutchPlayerTurn = 0;
let germanPlayerTurn = 0;
let dutchPlayerScore = {
  turnOneTime: 0,
  turnTwoTime: 0,
  turnThreeTime: 0,
  turnOneDifficulty: 0,
  turnTwoDifficulty: 0,
  turnThreeDifficulty: 0,
  turnOneAttempts: 0,
  turnTwoAttempts: 0,
  turnThreeAttempts: 0,
};
let germanPlayerScore = {
  turnOneTime: 0,
  turnTwoTime: 0,
  turnThreeTime: 0,
  turnOneDifficulty: 0,
  turnTwoDifficulty: 0,
  turnThreeDifficulty: 0,
  turnOneAttempts: 0,
  turnTwoAttempts: 0,
  turnThreeAttempts: 0,
};

let playerArray = ["German player", "Dutch player"];
let turnArray = [];
let firstTurn = turnArray[Math.floor(Math.random() * turnArray.length)];
let currentTurn = "";

function newGame() {
  fetch("get_word.php")
    .then((response) => response.json())
    .then((data) => {
      for (let i = 0; i < data.length; i++) {
        words.push(data[i]);
      }
      console.log(words);

      firstTurn = playerArray[Math.floor(Math.random() * playerArray.length)];

      if (gameCount === 0) {
        currentTurn = firstTurn;
      } else if (lastTurn === "Dutch player") {
        currentTurn = "German player";
      } else if (lastTurn === "German player") {
        currentTurn = "Dutch player";
      }

      dutchWord = words[0];
      germanWord = words[1];

      if (currentTurn === "Dutch player") {
        shownWord = dutchWord;
        word = germanWord;
        maskedWord = "_".repeat(word.length);
      } else if (currentTurn === "German player") {
        shownWord = germanWord;
        word = dutchWord;
        maskedWord = "_".repeat(word.length);
      }

      document.getElementById("wordToGuess").textContent = maskedWord;
      document.getElementById("untranslatedWord").textContent = shownWord;
      document.getElementById("playerTurn").textContent = currentTurn;

      generateKeyboard();
      attempts = 0;
      startTime = Date.now();
      gameCount++;
      if (currentTurn === "Dutch player") {
        dutchPlayerTurn++;
      }
      if (currentTurn === "German player") {
        germanPlayerTurn++;
      }
    });
}

function nextTurn() {
  if (gameCount !== 6) {
    lastTurn = currentTurn;
    if (lastTurn === "Dutch player") {
      const endTime = Date.now();
      totalTime += (endTime - startTime) / 1000; // Convert to seconds
      switch (dutchPlayerTurn) {
        case 1:
          dutchPlayerScore.turnOneTime = totalTime;
          dutchPlayerScore.turnOneDifficulty = "easy";
          dutchPlayerScore.turnOneAttempts = attempts;
          break;
        case 2:
          dutchPlayerScore.turnTwoTime = totalTime;
          dutchPlayerScore.turnTwoDifficulty = "easy";
          dutchPlayerScore.turnTwoAttempts = attempts;
          break;
        case 3:
          dutchPlayerScore.turnThreeTime = totalTime;
          dutchPlayerScore.turnThreeDifficulty = "easy";
          dutchPlayerScore.turnThreeAttempts = attempts;
          break;
        default:
          break;
      }
      newGame();
      hideHangman();
    }
    if (lastTurn === "German player") {
      const endTime = Date.now();
      totalTime += (endTime - startTime) / 1000; // Convert to seconds
      switch (germanPlayerTurn) {
        case 1:
          germanPlayerScore.turnOneTime = totalTime;
          germanPlayerScore.turnOneDifficulty = "easy";
          germanPlayerScore.turnOneAttempts = attempts;
          break;
        case 2:
          germanPlayerScore.turnTwoTime = totalTime;
          germanPlayerScore.turnTwoDifficulty = "easy";
          germanPlayerScore.turnTwoAttempts = attempts;
          break;
        case 3:
          germanPlayerScore.turnThreeTime = totalTime;
          germanPlayerScore.turnThreeDifficulty = "easy";
          germanPlayerScore.turnThreeAttempts = attempts;
          break;
        default:
          break;
      }
      newGame();
      hideHangman();
    }
  } else if (gameCount === 6) {
    endGame();
  }
}

function endGame() {}

function generateKeyboard() {
  const alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  let keyboardHtml = "";

  for (let letter of alphabet) {
    keyboardHtml += `<button onclick="makeGuess('${letter}')">${letter}</button>`;
  }

  document.getElementById("keyboard").innerHTML = keyboardHtml;
}

function makeGuess(letter) {
  let newMasked = "";
  let correctGuess = false;

  for (let i = 0; i < word.length; i++) {
    if (word[i] === letter) {
      newMasked += letter;
      correctGuess = true;
    } else {
      newMasked += maskedWord[i];
    }
  }

  if (!correctGuess) {
    attempts++;
    showHangman(attempts);
  }

  maskedWord = newMasked;
  document.getElementById("wordToGuess").textContent = maskedWord;

  if (!maskedWord.includes("_")) {
    const endTime = Date.now();
    totalTime += (endTime - startTime) / 1000; // Convert to seconds
    alert("You won! Next Turn!");
    nextTurn();
  }
}
function hideHangman() {
  const parts = ["head", "body", "leftArm", "rightArm", "leftLeg", "rightLeg"];
  parts.forEach((part) => document.getElementById(part).classList.add("hide"));
}

function showHangman(attempts) {
  switch (attempts) {
    case 1:
      document.getElementById("head").classList.remove("hide");
      break;
    case 2:
      document.getElementById("body").classList.remove("hide");
      break;
    case 3:
      document.getElementById("leftArm").classList.remove("hide");
      break;
    case 4:
      document.getElementById("rightArm").classList.remove("hide");
      break;
    case 5:
      document.getElementById("leftLeg").classList.remove("hide");
      break;
    case 6:
      document.getElementById("rightLeg").classList.remove("hide");
      alert("You lost! Next Turn!");
      nextTurn();
      break;
    default:
      break;
  }
}

// async function saveHighScore(username, score) {
//   try {
//     const response = await fetch("save_score.php", {
//       method: "POST",
//       headers: {
//         "Content-Type": "application/x-www-form-urlencoded",
//       },
//       body: `username=${username}&score=${score}`,
//     });

//     if (!response.ok) {
//       throw new Error(`Network response was not ok: ${response.statusText}`);
//     }
//     if (response.status === 201) {
//       alert("High score saved!");
//     } else {
//       alert("Failed to save high score.");
//     }
//   } catch (error) {
//     console.error(`There was a problem with the fetch operation: ${error}`);
//     alert("Failed to save high score due to a network error.");
//   }
// }
