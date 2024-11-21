<?php
session_start();
include '../../db.php';

// Fetch all events
$sql = "SELECT * FROM customers";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Information</title>
    <link rel="stylesheet" href="../../styles/dashboardstyle.css">
</head>
<body>
    <div class="dashboard-container">
    <?php include 'sidebar.php'; ?>
    
        <main class="main-content">
            <header>
                <h1>Users Information</h1> 
            </header>
            <section class="create-button">
                <a href="create_user.php">
                    <button >Create New User</button></a>
        
            </section>
            <section>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['firstName'] . " " . $row['lastName']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['phoneNumber']; ?></td>
                                    <td><?php echo $row['address']; ?></td>
                                    <td>
                                        <a href="edit_user.php?id=<?php echo $row['id']; ?>">Edit</a> |
                                        <a href="delete_user.php?id=<?php echo $row['id']; ?>">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>
<?php $conn->close(); ?>
