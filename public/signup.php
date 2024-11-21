<?php
include '../db.php';
 session_start();
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../styles/formstyle.css">
    <link rel="stylesheet" href="../styles/styles.css">
    <!-- Ensure SweetAlert2 is loaded before any JavaScript calls it -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <p class="account-link">Already have an account? <a href="../public/login.php">Login Now!</a></p>
        </form>
    </main>

    <footer>
        <p>&copy; 2024 Christmas Events Ticketing. All rights reserved.</p>
    </footer>
</body>
</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phoneNumber = $_POST['phoneNumber'];
    $address = $_POST['address'];
    $adultPhoto = $_POST['adultPhoto'];

    // to check if email already exists
    $sql_check_email = "SELECT * FROM customers WHERE Email = '$email'";
    $result_check = $conn->query($sql_check_email);

    if ($result_check->num_rows > 0) {
        // Email exists, show an error message
        echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Email already registered',
                    text: 'Please proceed to login or try again.',
                    confirmButtonText: 'OK',
                    customClass: {
                    confirmButton: 'alert-button'  
                     }
                });
              </script>";
    } else {
        // Email doesn't exist, proceed with the insert
        $sql = "INSERT INTO customers (FirstName, LastName, Email, Password, PhoneNumber, Address, AdultPhoto)
                VALUES ('$firstName', '$lastName', '$email', '$password', '$phoneNumber', '$address', '$adultPhoto')";

        if ($conn->query($sql) === TRUE) {
            // Registration successful, show SweetAlert and redirect
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Registration Successful!',
                        text: 'You can now log in.',
                        showConfirmButton: false,
                        timer: 3000
                    }).then(function() {
                        window.location.href = '../public/login.php';  // Redirect to login page
                    });
                  </script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>


