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
  
  async function newGame() {
    try {
      if (gameCount === 3) {
        alert(`Game Over! Total time: ${totalTime} seconds. Total errors: ${totalAttempts}`);
        gameCount = 0;
        totalAttempts = 0;
        totalTime = 0;
      }
  
      const response = await fetch('get_word.php');
      const data = await response.json();
  
      words = data.words;
      const randomIndex = Math.floor(Math.random() * 2); // 0 or 1
      word = words[randomIndex];
      words.splice(randomIndex, 1);
      untranslatedWord = words[0];
      maskedWord = "_".repeat(word.length);
      
      document.getElementById("wordToGuess").textContent = maskedWord;
      document.getElementById("untranslatedWord").textContent = shownWord;

      generateKeyboard();
      attempts = 0;
      startTime = Date.now();
      gameCount++;
    } catch (error) {
      console.error("An error occurred:", error);
    }
  }  
  

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
  
  