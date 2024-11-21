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
            <h2>Reset Password</h2>
            <form method="POST" action="">
                <input type="email" name="email" placeholder="Enter your email" required>
                <input type="submit" value="Send Reset Link">
            </form>
        </div>
    </main>
</body>
</html>


<?php
include '../db.php';
// session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT * FROM Register WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email exists
        $token = bin2hex(random_bytes(32)); // Generate a unique token
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token expiry time

        // Save the token in the database
        $stmt = $conn->prepare("INSERT INTO password_resets (Email, Token, Expiry) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $token, $expiry);
        if ($stmt->execute()) {
            // Send the reset link via email
            $reset_link = "http://localhost/public/reset-password.php?token=$token";
            $subject = "Password Reset Request";
            $message = "Click the link below to reset your password:\n\n$reset_link";
            $headers = "From: no-reply@yourwebsite.com";

            if (mail($email, $subject, $message, $headers)) {
                echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Email Sent',
                            text: 'A password reset link has been sent to your email address.',
                            confirmButtonText: 'OK',
                            customClass: {
                            confirmButton: 'alert-button'  
                            }
                        });
                      </script>";
            } else {
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Sending Email',
                            text: 'Please try again later.',
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
                    title: 'Email Not Found',
                    text: 'No account associated with this email.',
                    confirmButtonText: 'OK',
                    customClass: {
                    confirmButton: 'alert-button'  
                     }
                });
              </script>";
    }
}
?>
