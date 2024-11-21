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
        <div class="signup-form">
            <h2>Enter New Password</h2>
            <form method="POST" action="">
                <input type="password" name="password" placeholder="New Password" required>
                <input type="submit" value="Reset Password">
            </form>
        </div>
    </main>
</body>
</html>

<?php

// Check if token is valid
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT * FROM password_resets WHERE Token = ? AND Expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token is valid
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $row = $result->fetch_assoc();
            $email = $row['Email'];

            // Update password in the database
            $stmt = $conn->prepare("UPDATE customers SET Password = ? WHERE Email = ?");
            $stmt->bind_param("ss", $new_password, $email);
            if ($stmt->execute()) {
                // Delete the token after successful reset
                $stmt = $conn->prepare("DELETE FROM password_resets WHERE Email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();

                echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Password Reset Successful',
                            text: 'You can now log in with your new password.',
                            confirmButtonText: 'OK',
                            customClass: {
                            confirmButton: 'alert-button'  
                            }
                        }).then(function() {
                            window.location.href = 'login.php';
                        });
                      </script>";
            } else {
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Unable to reset password. Please try again.',
                            confirmButtonText: 'OK',
                            customClass: {
                            confirmButton: 'alert-button'  
                            }
                        });
                      </script>";
            }
        }
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Token',
                    text: 'The password reset link is invalid or expired.',
                    confirmButtonText: 'OK',
                    customClass: {
                    confirmButton: 'alert-button'  
                     }
                });
              </script>";
        exit();
    }
}
?>
