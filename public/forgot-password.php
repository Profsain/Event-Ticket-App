<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../styles/formstyle.css">
    <link rel="stylesheet" href="../styles/styles.css">
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
// Include the PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer (make sure you've installed it via Composer or manually)
require '../vendor/autoload.php';

// Include database connection
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT * FROM customers WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email exists, fetch the first name
        $row = $result->fetch_assoc();
        $firstName = $row['firstName']; // Assuming column name is 'FirstName'
        
        // Generate a unique token and expiry time
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Save the token in the database
        $stmt = $conn->prepare("INSERT INTO password_resets (Email, Token, Expiry) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $token, $expiry);
        if ($stmt->execute()) {
            try {
                // Send reset link via PHPMailer
                $mail = new PHPMailer(true);

                // SMTP configuration
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; // SMTP server
                $mail->SMTPAuth   = true;
                $mail->Username   = 'omolewabalikis149@gmail.com'; // Your email
                $mail->Password   = 'chctwdrimuepomwq'; // Your email password
                $mail->SMTPSecure = 'tls'; // Encryption type (SSL/TLS)
                $mail->Port       = 587;

                // Email settings
                $mail->setFrom('omolewabalikis149@gmail.com', 'Christmas Events'); // Sender email
                $mail->addAddress($email); // Recipient email

                $reset_link = "http://localhost/Event-Ticket-App/public/reset-password.php?token=$token"; // correct later to the correct urrl link
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Password Reset Request';
                $mail->isHTML(true); // Set email format to HTML
                $mail->Body    = "
                <!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f4f4f4;
                            padding: 20px;
                        }
                        .container {
                            background-color: #f7f7f7;
                            width: 100%;
                            max-width: 600px;
                            margin: 0 auto;
                            padding: 20px;
                            border-radius: 8px;
                            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                        }
                        .header {
                            text-align: center;
                            margin-bottom: 20px;
                        }
                        .header img {
                            width: 60px;
                        }
                            .header h1 {
                            font-size: 24px;
                            color: #ff5722;
                        }
                        .content {
                            margin-bottom: 20px;
                        }
                        .content h2 {
                            color: #333;
                            font-size: 24px;
                        }
                        .content p {
                            font-size: 15px;
                        }
                            .content a {
                            color: #fff;
                        }
                        .reset-button {
                            display: inline-block;
                            padding: 10px 20px;
                            background-color: #ff5722;
                            color: white;
                            font-size: 16px;
                            text-decoration: none;
                            border-radius: 5px;
                            text-align: center;
                        }
                        .footer {
                            text-align: left;
                            font-size: 12px;
                            color: #888;
                            margin-top: 20px;
                        } .footer .socials { ext-align: center; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                             <img src='https://your-logo-url.com/logo.png' alt='Logo'>
                            <h1>Christmas Events</h1>
                        </div>
                        <div class='content'>
                            <h2>Hi $firstName,</h2>
                            <p>You have requested to reset your password. Please click the button below to reset your password.</p>
                            <a href='$reset_link' class='reset-button'>Reset password</a>
                            <p><small>(link will expire after 24 hours)</small></p>
                        </div>
                        <div class='footer'>
                            <p>2024 | Christmas Events</p>
                        </div>
                    </div>
                </body>
                </html>
                ";
                $mail->AltBody = "Hi $firstName, You have requested to reset your password. Please click the link below to reset your password: $reset_link (link will expire after 24 hours)";

                
                $mail->send();

                // Success alert
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
            } catch (Exception $e) {
                // Error alert for PHPMailer exceptions
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Email Not Sent',
                            text: 'Mailer Error: {$mail->ErrorInfo}',
                            confirmButtonText: 'OK'
                        });
                      </script>";
            }
        }
    } else {
        // Email does not exist
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Email Not Found',
                    text: 'No account associated with this email.',
                    confirmButtonText: 'OK'
                });
              </script>";
    }
}
?>
