<?php
session_start();
include '../../db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New User</title>
    <link rel="stylesheet" href="../../styles/dashboardstyle.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
        <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>

    <main class="main-content">
            <header>
                <h1>Create New User</h1>
            </header>
    <section>
    <form method="POST" class="user-form">
        <input type="text" name="first_name" placeholder="First Name" required><br><br>
        <input type="text" name="last_name" placeholder="Last Name" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="email" name="email" placeholder="Password" required><br><br>
        <input type="text" name="phone_number" placeholder="Phone Number" required><br><br>
        <input type="text" name="address" placeholder="Address" required><br><br>
        <input type="email" name="email" placeholder="Adult Photo URL" required><br><br>
        <button type="submit">Create User</button>
    </form>
    </section>
    </main>
    </div>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];
    $address = $_POST['address'];

    // Insert new user
    $sql = "INSERT INTO register (FirstName, LastName, Email, PhoneNumber, Address) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssss', $firstName, $lastName, $email, $phoneNumber, $address);

    if ($stmt->execute()) {
        echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'User created successfully!',
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
                        title: 'Oops!',
                        text: 'Error creating user: " . $conn->error;"',
                        confirmButtonText: 'OK',
                        customClass: {
                        confirmButton: 'alert-button'  
                        }
                    });
                  </script>";
    }
}
?>

