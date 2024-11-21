
<?php
session_start();
include '../../db.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the database connection exists
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch events using `eventName`
$sql = "SELECT id, eventName FROM events";
$result = $conn->query($sql);
if (!$result) {
    die("Error fetching events: " . $conn->error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'];
    $seatwithtable_price = $_POST['seatwithtable_price'];
    $seatwithouttable_price = $_POST['seatwithouttable_price'];

    // Validate inputs
    if (!is_numeric($seatwithtable_price) || !is_numeric($seatwithouttable_price)) {
        echo "<script>
                alert('Prices must be numeric values!');
                window.location.href = 'create_ticket.php';
              </script>";
        exit;
    }

    try {
        // Insert tickets into the database
        $stmt = $conn->prepare("INSERT INTO tickets_created (event_id, seat_type, price) VALUES (?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Preparation failed: " . $conn->error);
        }

        $stmt->bind_param("isd", $event_id, $seat_type, $price);

        // Add seat with table ticket
        $seat_type = 'seatwithtable';
        $price = $seatwithtable_price;
        if (!$stmt->execute()) {
            throw new Exception("Execution failed for seat with table: " . $stmt->error);
        }

        // Add seat without table ticket
        $seat_type = 'seatwithouttable';
        $price = $seatwithouttable_price;
        if (!$stmt->execute()) {
            throw new Exception("Execution failed for seat without table: " . $stmt->error);
        }

        // Success message
        echo "<script>
                alert('Tickets created successfully!');
                window.location.href = 'create_ticket.php';
              </script>";
    } catch (Exception $e) {
        // Error message
        echo "<script>
                alert('Error: " . $e->getMessage() . "');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Ticket</title>
    <link rel="stylesheet" href="../../styles/dashboardstyle.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <header>
                <h1>Create Tickets</h1>
            </header>
            <section>
                <form method="POST" action="create_ticket.php">
                    <label for="event">Event Name:</label>
                    <select name="event_id" id="event" required>
                        <option value="">-- Select an Event --</option>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['eventName']) . "</option>";
                            }
                        } else {
                            echo "<option value=''>No events available</option>";
                        }
                        ?>
                    </select><br><br>

                    <label for="seatwithtable_price">Price for Seat with Table:</label>
                    <input type="number" name="seatwithtable_price" id="seatwithtable_price" required><br><br>

                    <label for="seatwithouttable_price">Price for Seat without Table:</label>
                    <input type="number" name="seatwithouttable_price" id="seatwithouttable_price" required><br><br>

                    <button type="submit">Create Tickets</button>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
