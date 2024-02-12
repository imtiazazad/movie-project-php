<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $password = $_POST["password"];

    // Check if username, email, and phone number are unique
    $stmt = $conn->prepare("SELECT username, email, phone FROM users WHERE username = ? OR email = ? OR phone = ?");
    $stmt->bind_param("sss", $username, $email, $phone);
    $stmt->execute();
    $stmt->store_result();
    $count = $stmt->num_rows;
    $stmt->close();

    if ($count > 0) {
        echo "<p>Error: Username, email, or phone number is already taken.</p>";
    } else {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user data into the users table
        $stmt = $conn->prepare("INSERT INTO users (username, phone, email, firstName, lastName, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $phone, $email, $firstName, $lastName, $hashedPassword);
        $stmt->execute();
        $stmt->close();

        echo "<p>Registration successful. You can now login.</p>";
    }
}

$conn->close();
?>



<?php include 'header.php'; ?>
        <h2>Registration</h2>
        <form method="POST" action="registration.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="tel" class="form-control" name="phone" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" class="form-control" name="firstName" required>
            </div>
            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" class="form-control" name="lastName" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>

<?php include 'footer.php'; ?>
</body>
</html>

