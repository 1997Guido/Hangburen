<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <link rel="icon" href="./images/logo-trans.png">
    <title>Hang buren</title>
</head>

<body>
    <section id="randomBgSection">
        <div class="login-box">
            <form method="post" name="loginCard" action="./index.php">
                <h2>lets get started!<h2>
                <div class="input-box">
                    <input type="text" id="username1" required>
                    <label>Player 1 - German words</label>
                </div>
                <div class="input-box">
                    <input type="text" id="username2" required>
                    <label>Player 2 - Dutch words</label>
                </div>

                <button type="button" onclick="storeNamesAndStartGame()">Start Game</button>
                        <div class="register-link">
                            <p>Click <a href="./input.php">here</a> to add words to your game!</a></p>
                        </div>
                        <div class="register-link">
                            <p>Click <a href="./importer.php">here</a> to use a CSV importer to add multiple words to your game!</a></p>
                        </div>
                        <div class="register-link">
                            <p><a href="./about.php">about</a></a></p>
                        </div>
            </form>
        </div>
    </section>
</body>

</html>

<script>
    function storeNamesAndStartGame() {
    const germanPlayer = document.getElementById("username1").value;
    const dutchPlayer = document.getElementById("username2").value;

    // Store names in sessionStorage
    sessionStorage.setItem("germanPlayer", germanPlayer);
    sessionStorage.setItem("dutchPlayer", dutchPlayer);

    // Redirect to game page
    window.location.href = "./index.php";
}
</script>