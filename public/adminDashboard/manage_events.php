<?php
session_start();
include '../../db.php';

// Fetch all events
$sql = "SELECT * FROM events";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <link rel="stylesheet" href="../../styles/dashboardstyle.css">
</head>
<body>
    <div class="dashboard-container">

    <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <header>
                <h1>Event Listings</h1>
            </header>
            <section class="create-button">
                <a href="create_event.php">
                    <button >Create New Event</button></a>
        
            </section>
            <section>
                <table>
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Description</th>
                            <th>Seat with Table</th>
                            <th>Seat with out Table</th>
                            <th>Date</th>
                            <th>Time</th>
                            
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['eventName']; ?></td>
                             <td><?php echo $row['description']; ?></td>
                             <td><?php echo $row['seatWithTable']; ?></td>
                             <td><?php echo $row['seatWithoutTable']; ?></td>
                             
                             <td><?php echo $row['eventDate']; ?></td>
                             <td><?php echo $row['eventTime']; ?></td>
                            <td>
                                <a href="edit_update_event.php?id=<?php echo $row['id']; ?>">Edit</a> |
                                <a href="delete_event.php?id=<?php echo $row['id']; ?>">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>
