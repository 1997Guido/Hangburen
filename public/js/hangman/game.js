document.addEventListener('DOMContentLoaded', (event) => {
    newGame();
  });
  let words = [];
  let word = "";
  let maskedWord = "";
  let attempts = 0;
  let totalAttempts = 0;
  let gameCount = 0;
  let startTime;
  let totalTime = 0;
  let dutchPlayer = 0;
  let germanPlayer = 0;
  let whoseTurn = "";
  let playerArray = ["German player", "Dutch player"];
  let turnArray = []
  let firstTurn = turnArray[Math.floor(Math.random() * turnArray.length)];
  let secondTurn = ""
  let thirdTurn = ""
  let currentTurn = ""
  function newGame() {
    fetch('get_word.php')
    .then(response => response.json())
    .then(data => {
      for (let i = 0; i < data.length; i++) {
        words.push(data[i]);
      }
      if (gameCount === 0) {
        currentTurn = firstTurn;
      }else if(lastTurn === "Dutch player"){
        currentTurn = "German player";
      }else if(lastTurn === "German player"){
        currentTurn = "Dutch player";
      }      

  
      console.log(words);
      let randomIndex = Math.floor(Math.random() * 2);
      word = words[randomIndex];
      words.splice(randomIndex, 1);
      untranslatedWord = words[0];
      maskedWord = "_".repeat(word.length);
      shownWord = untranslatedWord;
      
      document.getElementById("wordToGuess").textContent = maskedWord;
      document.getElementById("untranslatedWord").textContent = shownWord;
      document.getElementById("playerTurn").textContent = currentTurn;

      generateKeyboard();
      attempts = 0;
      startTime = Date.now();
      gameCount++;
      if (gameCount !== 3) {
        newGame();
      }else{
        lastTurn = currentTurn;
        if (lastTurn === "Dutch player"){
          dutchPlayer++;
        }
        if (lastTurn === "German player"){
          germanPlayer++;
        }
      }
      if (gameCount === 3) {
        alert("Game over");
        alert("Dutch player: " + dutchPlayer + " German player: " + germanPlayer);
        gameCount = 0;
        dutchPlayer = 0;
        germanPlayer = 0;
      generateKeyboard();
      attempts = 0;
    };
  })};
  

  async function saveHighScore(username, score) {
    try {
      const response = await fetch('save_score.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `username=${username}&score=${score}`
      });
  
      if (!response.ok) {
        throw new Error(`Network response was not ok: ${response.statusText}`);
      }      
      if (response.status === 201) {
        alert("High score saved!");
      } else {
        alert("Failed to save high score.");
      }
    } catch (error) {
      console.error(`There was a problem with the fetch operation: ${error}`);
      alert("Failed to save high score due to a network error.");
    }
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
  
  