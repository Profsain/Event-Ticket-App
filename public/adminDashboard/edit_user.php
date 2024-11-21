<?php
session_start();
include '../../db.php';

// Initialize user variable
$user = null;

// Check if an ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch user details
    $sql = "SELECT * FROM customers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    // Handle case where user is not found
    if (!$user) {
        echo "<script>
                alert('User not found!');
                window.location.href = 'manage_customer.php';
              </script>";
        exit();
    }
} else {
    echo "<script>
            alert('No user ID provided!');
            window.location.href = 'manage_customer.php';
          </script>";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];
    $address = $_POST['address'];

    // Update user information
    $sql = "UPDATE customers SET FirstName = ?, LastName = ?, Email = ?, PhoneNumber = ?, Address = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssi', $firstName, $lastName, $email, $phoneNumber, $address, $id);

    if ($stmt->execute()) {
        echo "<script>
                alert('User updated successfully!');
                window.location.href = 'manage_customer.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Error updating user: " . $conn->error . "');
              </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../../styles/dashboardstyle.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="dashboard-container">
<?php include 'sidebar.php'; ?>

<main class="main-content">
    <header>
        <h1>Edit User</h1>
    </header>
    <section>
        <?php if ($user): ?>
        <form method="POST" class="user-form">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['firstName']); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['lastName']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phoneNumber']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
            </div>
            <button type="submit" class="btn">Update User Information</button>
        </form>
        <?php endif; ?>
    </section>
</main>
</div>
</body>
</html>
