<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["movieId"]) && isset($_POST["movieName"]) && isset($_POST["movieLength"]) && isset($_POST["authorName"])) {
    $movieId = $_POST["movieId"];
    $movieName = $_POST["movieName"];
    $movieLength = $_POST["movieLength"];
    $authorName = $_POST["authorName"];

    $stmt = $conn->prepare("UPDATE movies SET movieName = ?, movieLength = ?, authorName = ? WHERE id = ?");
    $stmt->bind_param("sisi", $movieName, $movieLength, $authorName, $movieId);
    $stmt->execute();
    $stmt->close();

    header("Location: todo.php");
    exit();
}

if (isset($_GET["id"])) {
    $movieId = $_GET["id"];
    $stmt = $conn->prepare("SELECT id, movieName, movieLength, authorName FROM movies WHERE id = ?");
    $stmt->bind_param("i", $movieId);
    $stmt->execute();
    $stmt->bind_result($id, $movieName, $movieLength, $authorName);
    $stmt->fetch();
    $stmt->close();
} else {
    header("Location: todo.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Movie</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-4 mb-4">Update Movie</h2>

        <form method="POST" action="update.php">
            <input type="hidden" name="movieId" value="<?php echo $id; ?>">
            <div class="form-group">
                <label for="movieName">Movie Name:</label>
                <input type="text" class="form-control" name="movieName" value="<?php echo $movieName; ?>" required>
            </div>
            <div class="form-group">
                <label for="movieLength">Movie Length:</label>
                <input type="number" class="form-control" name="movieLength" value="<?php echo $movieLength; ?>" required>
            </div>
            <div class="form-group">
                <label for="authorName">Author Name:</label>
                <input type="text" class="form-control" name="authorName" value="<?php echo $authorName; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Movie</button>
        </form>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
