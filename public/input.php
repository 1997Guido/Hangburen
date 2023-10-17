<!DOCTYPE html>
<html>
<head>
    <title>Word Submission</title>
</head>
<body>
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
    </form>
</body>
</html>
