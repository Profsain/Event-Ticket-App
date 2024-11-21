<?php
include '../../db.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="../../styles/formstyle.css">
    <link rel="stylesheet" href="../../styles/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="background-color: #ff5722;">
    <main class="container">
        <div class="signup-form admin-form">
            <h2>Admin Registration</h2>
            <form method="POST" action="">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" value="Register">
                <p class="account-link" style="text-align: center;">Already have an account? <a href="admin_login.php">Log In</a></p>
                <p class="account-link" style="text-align: center;"><a>2024 </a> | Christmas Events Ticketing</p>
            </form>
        </div>
    </main>
</body>
</html>

<?php

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Securely hash the password

    // Check if admin already exists
    $sql_check = "SELECT * FROM admins WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param('s', $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Admin Already Registered',
                    text: 'This email is already registered. Please log in.',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'alert-button'  
                        }
                });
              </script>";
    } else {
        // Insert the new admin into the database
        $sql = "INSERT INTO admins (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $userName, $email, $hashedPassword);

        if ($stmt->execute()) {
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Registration Successful',
                        text: 'You can now log in as an admin.',
                        confirmButtonText: 'Login',
                        customClass: {
                        confirmButton: 'alert-button'  
                        }
                    }).then(function() {
                        window.location.href = './admin_login.php';
                    });
                  </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Registration Failed',
                        text: 'An error occurred while registering. Please try again.',
                        confirmButtonText: 'OK',
                        customClass: {
                        confirmButton: 'alert-button'  
                        }
                    });
                  </script>";
        }
    }

    $stmt_check->close();
    $stmt->close();
    $conn->close();
}
?>
