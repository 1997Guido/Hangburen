<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/input.css">
    <title>Document</title>
</head>

<body>
    <?php
    // Database connection using PDO
    $servername = "mariadb";
    $username = "root";
    $password = "a3b6c9";
    $dbname = "Hangman";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retrieve Dutch and German words, and the selected difficulty from the form
        $dutch_word = $_POST["dutch_word"];
        $german_word = $_POST["german_word"];
        $difficulty = $_POST["difficulty"];

        // Prepare the SQL statement
        $sql = "INSERT INTO word_translations (dutch_word, german_word, difficulty) VALUES (:dutch_word, :german_word, :difficulty)";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':dutch_word', $dutch_word);
        $stmt->bindParam(':german_word', $german_word);
        $stmt->bindParam(':difficulty', $difficulty);

        // Execute the statement
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }


    // Close the database connection
    $conn = null;

    ?>
    <section id="randomBgSection">
        <div class="login-box">
            <form action="process.php" method="post">
                <h2>Word has been stored.</h2>
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