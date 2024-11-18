<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phoneNumber = $_POST['phoneNumber'];
    $address = $_POST['address'];
    $adultPhoto = $_POST['adultPhoto'];

    $sql = "INSERT INTO Customer (FirstName, LastName, Email, Password, PhoneNumber, Address, AdultPhoto)
            VALUES ('$firstName', '$lastName', '$email', '$password', '$phoneNumber', '$address', '$adultPhoto')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
        <form class="signup-form" action="" method="POST">
            <h2>Sign Up</h2>
            <input type="text" name="firstName" placeholder="First Name" required>
            <input type="text" name="lastName" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="phoneNumber" placeholder="Phone Number" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="text" name="adultPhoto" placeholder="Adult Photo URL" required>
            <input type="submit" value="Register">
        </form>
    </main>
</body>
</html>
