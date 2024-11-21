<?php
include '../db.php';
 session_start();
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            <h2>Login</h2>
            <form method="POST" action="">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <p class="forgot-password"><a href="../public/forgot-password.php">Forgot Password?</a></p>
                <input type="submit" value="Login">
                <p class="account-link">Don't have an account? <a href="../public/signup.php">Register Now!</a></p>
            </form>
        </div>
    </main>
</body>
</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM customers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email exists, now check the password
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Password is correct
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['firstName'] = $user['firstName'];

            // Redirect to ticket purchasing page
            echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Login Successful!',
                text: 'Welcome back, " . addslashes($user['firstName']) . "!',
                showConfirmButton: false,
                timer: 3000
            }).then(function() {
                window.location.href = '../public/purchase.php';
            });
          </script>";
        } else {
            // Incorrect password
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Password',
                        text: 'Please try again.',
                        confirmButtonText: 'OK',
                        customClass: {
                        confirmButton: 'alert-button'  
                        }
                    });
                  </script>";
        }
    } else {
        // Email doesn't exist
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Email Not Found',
                    text: 'Please create an account to get started..',
                    confirmButtonText: 'OK',
                    customClass: {
                    confirmButton: 'alert-button'  
                     }
                });
              </script>";
    }
    $stmt->close();
    $conn->close();
}
?>

