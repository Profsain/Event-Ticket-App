<?php
session_start();
include '../../db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <link rel="stylesheet" href="../../styles/dashboardstyle.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <header>
                <h1>Add Event</h1>
            </header>
            <section>
                <form method="POST" action="create_event.php">
                    <div class="form-group">
                        <label for="event_name">Event Name</label>
                        <input type="text" id="eventName" name="eventName" required>
                    </div>
                    <div class="form-group">
                        <label for="event_date">Event Date</label>
                        <input type="date" id="eventDate" name="eventDate" required>
                    </div>
                    <div class="form-group">
                        <label for="event_time">Event Time</label>
                        <input type="time" id="eventTime" name="eventTime" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="seatWithTable">Seat with Table</label>
                        <input type="number" id="seatWithTable" name="seatWithTable" required>
                    </div>
                    <div class="form-group">
                        <label for="seatWithoutTable">Seat without Table</label>
                        <input type="number" id="seatWithoutTable" name="seatWithoutTable" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn">Create Event</button>
                </form>
            </section>
        </main>
    </div>
</body>
</html>

<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $eventName = $_POST['eventName'];
    $eventDate = $_POST['eventDate'];
    $eventTime = $_POST['eventTime'];
    $seatWithTable = $_POST['seatWithTable'];
    $seatWithoutTable = $_POST['seatWithoutTable'];
    $description = $_POST['description'];

    // Insert event into the database
    $sql = "INSERT INTO events (eventName, eventDate, eventTime, seatWithTable, seatWithoutTable, description) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

   // Bind parameters
$stmt->bind_param('ssssss', $eventName, $eventDate, $eventTime, $seatWithTable, $seatWithoutTable, $description);    

    if ($stmt->execute()) {
        echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Event created successfully!.',
                        confirmButtonText: 'OK',
                        customClass: {
                        confirmButton: 'alert-button'  
                        }
                    });
                    window.location.href = 'manage_events.php';
                  </script>";
    } else {
        echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops',
                        text: 'Failed to create event. Error: " . $stmt->error . "',
                        confirmButtonText: 'OK',
                        customClass: {
                        confirmButton: 'alert-button'  
                        }
                    });
                  </script>";
    }
}
?>

