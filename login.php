<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($userId, $hashedPassword);
    $stmt->fetch();
    $stmt->close();

    if ($hashedPassword && password_verify($password, $hashedPassword)) {
        $_SESSION["user_id"] = $userId;
        $_SESSION["user_name"] = $email;
        header("Location: todo.php");
    } else {
        echo "<p>Invalid email or password.</p>";
    }
}

$conn->close();
?>

<?php include 'header.php'; ?>
        <h2>Login</h2>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

<?php include 'footer.php'; ?>
</body>
</html>


