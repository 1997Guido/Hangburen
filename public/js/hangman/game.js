document.addEventListener("DOMContentLoaded", (event) => {
  newGame();
});

let word = "";
let maskedWord = "";
let attempts = 0;

function newGame() {
  fetch("get_word.php")
    .then((response) => response.json())
    .then((data) => {
      word = data.word.toUpperCase();
      maskedWord = "_".repeat(word.length);
      document.getElementById("wordToGuess").textContent = maskedWord;
      generateKeyboard();
      attempts = 0;
    });
}

function saveHighScore(username, score) {
  fetch("save_score.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `username=${username}&score=${score}`,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        alert("High score saved!");
      }
    });
}
if (!maskedWord.includes("_")) {
  alert("You won!");
  let username = prompt("Enter your username to save your high score:");
  let score = calculateScore(); // Implement your own score calculation logic
  saveHighScore(username, score);
  newGame();
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
  for (let i = 0; i < word.length; i++) {
    if (word[i] === letter) {
      newMasked += letter;
    } else {
      newMasked += maskedWord[i];
    }
  }

  if (newMasked === maskedWord) {
    attempts++;
    // Handle failed attempts
  } else {
    maskedWord = newMasked;
    document.getElementById("wordToGuess").textContent = maskedWord;
    if (!maskedWord.includes("_")) {
      alert("You won!");
      newGame();
    }
  }
}
