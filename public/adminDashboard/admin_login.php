<?php
session_start();
include '../../db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../styles/formstyle.css">
    <link rel="stylesheet" href="../../styles/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="background-color: #ff5722; ">
    <main class="container">
        <div class="signup-form admin-form">
            <h2>Admin Login</h2>
            <form method="POST" action="admin_login.php">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <p class="forgot-password"><a href="../public/forgot-password.php">Forgot Password?</a></p>
                <input type="submit" value="Login">
                <p class="account-link" style="text-align: center;"><a>2024 </a> | Christmas Events Ticketing</p>
            </form>
        </div>
    </main>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: admin_dashboard.php");
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incorrect password',
                        text: 'Sorry, your password is incorrect.',
                        confirmButtonText: 'OK',
                        customClass: {
                        confirmButton: 'alert-button'  
                        }
                    });
                  </script>
            ";
        }
    } else {
        echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Admin not found',
                        text: 'Your account is not recognized. Please register to create account.',
                        confirmButtonText: 'OK',
                        customClass: {
                        confirmButton: 'alert-button'  
                        }
                    });
                  </script>
        ";
    }
}
?>


