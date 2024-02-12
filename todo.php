<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Create Movie
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["movieName"]) && isset($_POST["movieLength"]) && isset($_POST["authorName"]) && isset($_FILES["movieImage"])) {
    $movieName = $_POST["movieName"];
    $movieLength = $_POST["movieLength"];
    $authorName = $_POST["authorName"];

    // File Upload
    $targetDir = "uploads/";
    $fileName = basename($_FILES["movieImage"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    move_uploaded_file($_FILES["movieImage"]["tmp_name"], $targetFilePath);

    $stmt = $conn->prepare("INSERT INTO movies (movieName, movieLength, authorName, movieImage) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $movieName, $movieLength, $authorName, $targetFilePath);
    $stmt->execute();
    $stmt->close();

    header("Location: todo.php");
    exit();
}

// Read Movies
$stmt = $conn->prepare("SELECT id, movieName, movieLength, authorName, movieImage FROM movies");
$stmt->execute();
$stmt->bind_result($id, $movieName, $movieLength, $authorName, $movieImage);
$movies = [];
while ($stmt->fetch()) {
    $movies[] = [
        "id" => $id,
        "movieName" => $movieName,
        "movieLength" => $movieLength,
        "authorName" => $authorName,
        "movieImage" => $movieImage
    ];
}
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Movie Library</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('background-image.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
        }
        .container {
            margin-top: 100px;
        }
        .movie-card {
            height: 350px;
        }
        .movie-image {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Movie Library</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h2 class="mt-4 mb-4">Movie Library</h2>

        <!-- Create Movie Form -->
        <form method="POST" action="todo.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="movieName">Movie Name:</label>
                <input type="text" class="form-control" name="movieName" required>
            </div>
            <div class="form-group">
                <label for="movieLength">Movie Length:</label>
                <input type="number" class="form-control" name="movieLength" required>
            </div>
            <div class="form-group">
                <label for="authorName">Author Name:</label>
                <input type="text" class="form-control" name="authorName" required>
            </div>
            <div class="form-group">
                <label for="movieImage">Movie Image:</label>
                <input type="file" class="form-control-file" name="movieImage" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Movie</button>
        </form>

        <!-- Display Movies -->
<!-- Display Movies -->
<div class="row mt-4">
    <?php foreach ($movies as $movie): ?>
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="<?php echo $movie["movieImage"]; ?>" class="card-img-top" alt="Movie Image">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $movie["movieName"]; ?></h5>
                    <p class="card-text">Length: <?php echo $movie["movieLength"]; ?> mins</p>
                    <p class="card-text">Author: <?php echo $movie["authorName"]; ?></p>
                    <a href="update.php?id=<?php echo $movie["id"]; ?>" class="btn btn-primary">Update</a>
                    <a href="delete.php?id=<?php echo $movie["id"]; ?>" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
