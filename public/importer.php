<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CSV Importer</title>
</head>
<body>

<h1>Import CSV</h1>

<form action="import_process.php" method="post" enctype="multipart/form-data">
    Upload CSV file:
    <input type="file" name="csvfile" accept=".csv">
    <button type="submit" name="submit">Import</button>
</form>

</body>
</html>
