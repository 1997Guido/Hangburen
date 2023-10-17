<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/input.css">

    <title>Word submission</title>
</head>

<body>
    <section id="randomBgSection">
        <div class="login-box">
            <form action="process.php" method="post">
                <label for="dutch-word">Enter a Dutch Word:</label>
                <input type="text" class="word" name="dutch_word" id="dutch-word" required>
                <br>
                <label for="german-word">Enter a German Word:</label>
                <input type="text" class="word" name="german_word" id="german-word" required>
                <br>
                <label for="difficulty">Select Difficulty:</label>
                <select name="difficulty" id="difficulty">
                    <option value="easy">Easy</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="expert">Expert</option>
                </select>
                <br>
                <button type="submit" class="ibutton">Submit</button>
                <a href="./login.php" class="return-btn">Retrun home</a>
            </form>
        </div>
    </section>
</body>

</html>