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
let dutchPlayerData = {
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
let germanPlayerData = {
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
let firstTurn = "";
let currentTurn = "";

function newGame() {
  words = [];
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

      dutchWord = words[0].toUpperCase();
      germanWord = words[1].toUpperCase();

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
      document.getElementById("whoseTurn").textContent = currentTurn;

      generateKeyboard();
      attempts = 0;
      startTime = Date.now();
    });
}

function nextTurn() {
    lastTurn = currentTurn;
    if (lastTurn === "Dutch player") {
      gameCount = dutchPlayerTurn + germanPlayerTurn;
      console.log("gamecount is " + gameCount);
      console.log("dutchPlayerTurn is " + dutchPlayerTurn);
      console.log("germanPlayerTurn is " + germanPlayerTurn);
      document.getElementById("dutchPlayerTurns").innerHTML = dutchPlayerTurn;
      if (gameCount === 6) {
        endGame();
        console.log("gamecount is 6 ending game")
        hideGame();
      }else{
      const endTime = Date.now();
      totalTime += (endTime - startTime) / 1000; // Convert to seconds
      switch (dutchPlayerTurn) {
        case 1:
          dutchPlayerData.turnOneTime = totalTime;
          dutchPlayerData.turnOneDifficulty = "easy";
          dutchPlayerData.turnOneAttempts = attempts;
          break;
        case 2:
          dutchPlayerData.turnTwoTime = totalTime;
          dutchPlayerData.turnTwoDifficulty = "easy";
          dutchPlayerData.turnTwoAttempts = attempts;
          break;
        case 3:
          dutchPlayerData.turnThreeTime = totalTime;
          dutchPlayerData.turnThreeDifficulty = "easy";
          dutchPlayerData.turnThreeAttempts = attempts;
          break;
        default:
          break;
      }
      newGame();
      dutchPlayerTurn++;
      hideHangman();
    }}
    if (lastTurn === "German player") {
      gameCount = dutchPlayerTurn + germanPlayerTurn;
      if (gameCount > 6) {
        endGame();
        console.log("gamecount is 6 ending game")
        hideGame();
      }else{
      console.log("gamecount is " + gameCount);
      console.log("dutchPlayerTurn is " + dutchPlayerTurn);
      console.log("germanPlayerTurn is " + germanPlayerTurn);
      document.getElementById("germanPlayerTurns").innerHTML = germanPlayerTurn;
      const endTime = Date.now();
      totalTime += (endTime - startTime) / 1000; // Convert to seconds
      switch (germanPlayerTurn) {
        case 1:
          germanPlayerData.turnOneTime = totalTime;
          germanPlayerData.turnOneDifficulty = "easy";
          germanPlayerData.turnOneAttempts = attempts;
          break;
        case 2:
          germanPlayerData.turnTwoTime = totalTime;
          germanPlayerData.turnTwoDifficulty = "easy";
          germanPlayerData.turnTwoAttempts = attempts;
          break;
        case 3:
          germanPlayerData.turnThreeTime = totalTime;
          germanPlayerData.turnThreeDifficulty = "easy";
          germanPlayerData.turnThreeAttempts = attempts;
          break;
        default:
          break;
      }
      newGame();
      germanPlayerTurn++;
      hideHangman();
    }}
}

function endGame() {
  fetch("algorithm.php", {
    method: "POST",
    body: JSON.stringify({
      dutchPlayerData: dutchPlayerData,
      germanPlayerData: germanPlayerData
    }),
    headers: {
      "Content-type": "application/json; charset=UTF-8"
    }
  }).then(response => response.json().then(data => {
    console.log(data);
    document.getElementById("game-box").classList.add("hide");
    document.getElementById("end-box").classList.remove("hide");
    }));
}

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
    document.getElementById("currentAttempts").textContent = attempts;
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

// async function saveHighData(username, Data) {
//   try {
//     const response = await fetch("save_Data.php", {
//       method: "POST",
//       headers: {
//         "Content-Type": "application/x-www-form-urlencoded",
//       },
//       body: `username=${username}&Data=${Data}`,
//     });

//     if (!response.ok) {
//       throw new Error(`Network response was not ok: ${response.statusText}`);
//     }
//     if (response.status === 201) {
//       alert("High Data saved!");
//     } else {
//       alert("Failed to save high Data.");
//     }
//   } catch (error) {
//     console.error(`There was a problem with the fetch operation: ${error}`);
//     alert("Failed to save high Data due to a network error.");
//   }
// }
