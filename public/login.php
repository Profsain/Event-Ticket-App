<?php
include '../db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM Customer WHERE Email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['Password'])) {
            $_SESSION['customer_id'] = $row['CustomerID'];
            header("Location: events.php");
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/formstyle.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <header>
        <h1>Christmas Events</h1>
        <nav>
            <ul>
                <li><a href="../index.php">Events</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="./signup.php">Register</a></li>
                <li><a href="./login.php">Login</a></li>
            </ul>
        </nav>
    </header>
    <main class="container">
        <div class="signup-form">
            <h2>Login</h2>
            <form method="POST" action="">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" value="Login">
            </form>
        </div>
    </main>
</body>
</html>
