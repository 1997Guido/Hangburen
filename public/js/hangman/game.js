document.addEventListener('DOMContentLoaded', (event) => {
    newGame();
  });
  
  let word = "";
  let maskedWord = "";
  let attempts = 0;
  let totalAttempts = 0;
  let gameCount = 0;
  let startTime;
  let totalTime = 0;
  
  function newGame() {
    if (gameCount === 3) {
      alert(`Game Over! Total time: ${totalTime} seconds. Total errors: ${totalAttempts}`);
      gameCount = 0;
      totalAttempts = 0;
      totalTime = 0;
    }
    fetch('get_word.php')
      .then(response => response.json())
      .then(data => {
        word = data.word.toUpperCase();
        maskedWord = "_".repeat(word.length);
        document.getElementById("wordToGuess").textContent = maskedWord;
        generateKeyboard();
        attempts = 0;
        startTime = Date.now();
        gameCount++;
      });
  }

  function saveHighScore(username, score) {
    fetch('save_score.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `username=${username}&score=${score}`
    })
    .then(response => response.json())
    .then(data => {
      if (data.status === "success") {
        alert("High score saved!");
      }
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
      totalAttempts++;
      showHangman(attempts);
    }
  
    maskedWord = newMasked;
    document.getElementById("wordToGuess").textContent = maskedWord;
  
    if (!maskedWord.includes("_")) {
      const endTime = Date.now();
      totalTime += (endTime - startTime) / 1000; // Convert to seconds
      alert("You won!");
      newGame();
    }
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
        alert("Game Over");
        newGame();
        break;
      default:
        break;
    }
  }
  
  