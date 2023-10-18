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
  turnOneFail: false,
  turnTwoFail: false,
  turnThreeFail: false,
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
  turnOneFail: false,
  turnTwoFail: false,
  turnThreeFail: false,
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
      gameCount++;
    });
}

function nextTurn() {
  lastTurn = currentTurn;

  if (lastTurn === "Dutch player") {
    // Increment Dutch player's turn counter
    dutchPlayerTurn++;

    // Update the total game count

    console.log("gamecount is " + gameCount);
    console.log("dutchPlayerTurn is " + dutchPlayerTurn);
    console.log("germanPlayerTurn is " + germanPlayerTurn);
    document.getElementById("dutchPlayerTurns").innerHTML = dutchPlayerTurn;

    // Check if game should end
    // Existing code to handle player data and other things
    const endTime = Date.now();
    totalTime += (endTime - startTime) / 1000;

    switch (dutchPlayerTurn) {
      case 1:
        dutchPlayerData.turnOneTime = totalTime;
        dutchPlayerData.turnOneDifficulty = "easy";
        dutchPlayerData.turnOneAttempts = attempts;
        if (dutchPlayerData.turnOneAttempts >= 6) {
          dutchPlayerData.turnOneFail = true;
        }
        break;
      case 2:
        dutchPlayerData.turnTwoTime = totalTime;
        dutchPlayerData.turnTwoDifficulty = "easy";
        dutchPlayerData.turnTwoAttempts = attempts;
        if (dutchPlayerData.turnTwoAttempts >= 6) {
          dutchPlayerData.turnTwoFail = true;
        }
        break;
      case 3:
        dutchPlayerData.turnThreeTime = totalTime;
        dutchPlayerData.turnThreeDifficulty = "easy";
        dutchPlayerData.turnThreeAttempts = attempts;
        if (dutchPlayerData.turnThreeAttempts >= 6) {
          dutchPlayerData.turnThreeFail = true;
        }
        break;
      default:
        break;
    }

    // Start a new game and update UI
    newGame();
    hideHangman();
  }

  if (lastTurn === "German player") {
    // Increment German player's turn counter
    germanPlayerTurn++;

    // Update the total game count

    console.log("gamecount is " + gameCount);
    console.log("dutchPlayerTurn is " + dutchPlayerTurn);
    console.log("germanPlayerTurn is " + germanPlayerTurn);
    document.getElementById("germanPlayerTurns").innerHTML = germanPlayerTurn;

    // Existing code to handle player data and other things
    const endTime = Date.now();
    totalTime += (endTime - startTime) / 1000;

    switch (germanPlayerTurn) {
      case 1:
        germanPlayerData.turnOneTime = totalTime;
        germanPlayerData.turnOneDifficulty = "easy";
        germanPlayerData.turnOneAttempts = attempts;
        if (germanPlayerData.turnOneAttempts >= 6) {
          germanPlayerData.turnOneFail = true;
        }
        break;
      case 2:
        germanPlayerData.turnTwoTime = totalTime;
        germanPlayerData.turnTwoDifficulty = "easy";
        germanPlayerData.turnTwoAttempts = attempts;
        if (germanPlayerData.turnTwoAttempts >= 6) {
          germanPlayerData.turnTwoFail = true;
        }
        break;
      case 3:
        germanPlayerData.turnThreeTime = totalTime;
        germanPlayerData.turnThreeDifficulty = "easy";
        germanPlayerData.turnThreeAttempts = attempts;
        if (germanPlayerData.turnThreeAttempts >= 6) {
          germanPlayerData.turnThreeFail = true;
        }
        break;
      default:
        break;
    }

    // Start a new game and update UI
    if (gameCount >= 6) {
      endGame();
    } else {
      newGame();
      hideHangman();
    }
  }
}

function endGame() {
  fetch("algorithm.php", {
    method: "POST",
    body: JSON.stringify({
      dutchPlayerData: dutchPlayerData,
      germanPlayerData: germanPlayerData,
    }),
    headers: {
      "Content-type": "application/json; charset=UTF-8",
    },
  })
  .then(response => response.json())
  .then(data => {
    let germanPlayername = sessionStorage.getItem("germanPlayer");
    let dutchPlayername = sessionStorage.getItem("dutchPlayer");
    let scoreData = {
      germanPlayername: germanPlayername,
      dutchPlayername: dutchPlayername,
      germanPlayerScore: data.germanPlayerScore,
      dutchPlayerScore: data.dutchPlayerScore,
    };

    return fetch("save_score.php", {
      method: "POST",
      body: JSON.stringify(scoreData),
      headers: {
        "Content-type": "application/json; charset=UTF-8",
      },
    });
  })
  .then(response => response.json())
  .then(data => {
    console.log(data);
  })
  .catch(error => {
    console.error('Error:', error);
  })
  .finally(() => {
    // Redirect to highscore.php regardless of success or error
    window.location.href = "highscore.php";
  });
}


function generateKeyboard() {
  const alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  let keyboardHtml = "";

  for (let letter of alphabet) {
    keyboardHtml += `<button onclick="makeGuess('${letter}')">${letter}</button>`;
  }

  document.getElementById("keyboard").innerHTML = keyboardHtml;
}

document.addEventListener("DOMContentLoaded", (event) => {
  newGame();
  listenForKeyboardInput();
});

function listenForKeyboardInput() {
  document.addEventListener("keydown", function (event) {
    const key = event.key.toUpperCase();

    if (key.length === 1 && key >= "A" && key <= "Z") {
      makeGuess(key);

      const button = document.querySelector(`button[data-letter="${key}"]`);
      if (button) {
        button.classList.add("pressed");
        setTimeout(() => button.classList.remove("pressed"), 200);
      }
    }
  });
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
    setTimeout(() => {
      alert("You won! Next Turn!");
      nextTurn();
  }, 100);  // 100 milliseconds delay
  }
}
function hideHangman() {
  const parts = [
    "beam1",
    "beam2",
    "beam3",
    "beam4",
    "head",
    "body",
    "leftArm",
    "rightArm",
    "leftLeg",
    "rightLeg",
  ];
  parts.forEach((part) => document.getElementById(part).classList.add("hide"));
}

function showHangman(attempts) {
  switch (attempts) {
    case 1:
      document.getElementById("beam1").classList.remove("hide");
      break;
    case 2:
      document.getElementById("beam2").classList.remove("hide");
      break;
    case 3:
      document.getElementById("beam3").classList.remove("hide");
      break;
    case 4:
      document.getElementById("beam4").classList.remove("hide");
      break;
    case 5:
      document.getElementById("head").classList.remove("hide");
      break;
    case 6:
      document.getElementById("body").classList.remove("hide");
      break;
    case 7:
      document.getElementById("leftArm").classList.remove("hide");
      break;
    case 8:
      document.getElementById("rightArm").classList.remove("hide");
      break;
    case 9:
      document.getElementById("leftLeg").classList.remove("hide");
      break;
    case 10:
      document.getElementById("rightLeg").classList.remove("hide");
      setTimeout(() => {
        alert("You lost! Next Turn!");
        nextTurn();
    }, 100);  // 100 milliseconds delay    
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
