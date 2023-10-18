<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Game Scores</title>
    <!-- DataTables CSS and JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="css/highscore.css">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
</head>

<body>

    <div class="container">
        <h1>Game Scores</h1>
    <?php
    $servername = "mariadb";
    $username = "root";
    $password = "a3b6c9";
    $dbname = "Hangman";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT username, score FROM game_scores ORDER BY score DESC");
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<div class='table-container'>";
            echo "<table id='gameScores' class='display'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Username</th>";
            echo "<th>Score</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while ($row = $stmt->fetch()) {
                echo "<tr>";
                echo "<td>" . $row['username'] . "</td>";
                echo "<td>" . $row['score'] . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        } else {
            echo "No scores found in the database.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conn = null;
    ?>

        <button id="newGame" onclick="startNewGame()">New Game</button>

    </div>

    <script>
        $(document).ready(function() {
            $('#gameScores').DataTable();
        });

        function startNewGame() {
            // Clear sessionStorage
            sessionStorage.clear();

            // Redirect to login.php
            window.location.href = "./login.php";
        }
    </script>
</body>

</html>