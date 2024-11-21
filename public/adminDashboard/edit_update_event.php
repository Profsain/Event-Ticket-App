<?php
session_start();
include '../../db.php';

// Initialize variables
$event = null;

// Check if an ID is provided
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Ensure ID is an integer

    // Fetch event details
    $sql = "SELECT * FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();

    if (!$event) {
        echo "<script>
                alert('Event not found!');
                window.location.href = 'manage_events.php';
              </script>";
        exit();
    }
} else {
    echo "<script>
            alert('No event ID provided!');
            window.location.href = 'manage_events.php';
          </script>";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventName = trim($_POST['event_name']);
    $eventDate = $_POST['event_date'];
    $eventTime = $_POST['event_time'];
    $seatWithTable = intval($_POST['seat_with_table']);
    $seatWithoutTable = intval($_POST['seat_without_table']);
    $description = trim($_POST['description']);

    // Update event information
    $sql = "UPDATE events SET 
                eventName = ?, 
                description = ?, 
                seatWithTable = ?, 
                seatWithoutTable = ?, 
                eventDate = ?, 
                eventTime = ? 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'sssissi', 
        $eventName, 
        $description, 
        $seatWithTable, 
        $seatWithoutTable, 
        $eventDate, 
        $eventTime, 
        $id
    );

    if ($stmt->execute()) {
        // Simpler alert for success
        echo "<script>
                alert('Event updated successfully!');
                window.location.href = 'manage_events.php';
              </script>";
    } else {
        error_log('Failed to update event: ' . $stmt->error); // Log error for debugging
        echo "<script>
                alert('Failed to update event. Please try again.');
              </script>";
    }
    
    $stmt->close();
    $conn->close(); // Close the connection
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="../../styles/dashboardstyle.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="dashboard-container">
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <header>
            <h1>Edit Event</h1>
        </header>
        <section>
            <?php if ($event): ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="event_name">Event Name</label>
                    <input type="text" id="event_name" name="event_name" value="<?php echo htmlspecialchars($event['eventName']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="event_date">Event Date</label>
                    <input type="date" id="event_date" name="event_date" value="<?php echo htmlspecialchars($event['eventDate']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="event_time">Event Time</label>
                    <input type="time" id="event_time" name="event_time" value="<?php echo htmlspecialchars($event['eventTime']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="seat_with_table">Seat with Table</label>
                    <input type="number" id="seat_with_table" name="seat_with_table" value="<?php echo htmlspecialchars($event['seatWithTable']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="seat_without_table">Seat without Table</label>
                    <input type="number" id="seat_without_table" name="seat_without_table" value="<?php echo htmlspecialchars($event['seatWithoutTable']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($event['description']); ?></textarea>
                </div>
                <button type="submit" class="btn">Update Event</button>
            </form>
            <?php else: ?>
                <p>No event found to edit.</p>
            <?php endif; ?>
        </section>
    </main>
</div>
</body>
</html>
