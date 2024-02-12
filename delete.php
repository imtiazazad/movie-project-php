<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["id"])) {
    $movieId = $_GET["id"];
    $stmt = $conn->prepare("DELETE FROM movies WHERE id = ?");
    $stmt->bind_param("i", $movieId);
    $stmt->execute();
    $stmt->close();

    header("Location: todo.php");
    exit();
} else {
    header("Location: todo.php");
    exit();
}

$conn->close();
?>
