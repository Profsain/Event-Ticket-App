<?php
// Assuming you're using a MySQL database connection already
if (isset($_GET['id'])) {
    $eventID = $_GET['id'];
    // Query to get the full event details
    $sql = "SELECT * FROM events WHERE eventID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $eventID); // Bind the eventID to the query
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();

    if (!$event) {
        echo "Event not found.";
        exit;
    }
}
?>

<section id="event-details">
    <h2><?php echo htmlspecialchars($event['eventName']); ?></h2>
    <p><strong>Date:</strong> <?php echo htmlspecialchars($event['eventDate']); ?></p>
    <p><strong>Time:</strong> <?php echo htmlspecialchars($event['eventTime']); ?></p>
    <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
    <p><strong>Seats Available:</strong> <?php echo htmlspecialchars($event['seatsAvailable']); ?></p>
    <form action="purchase.php" method="POST">
        <button type="submit" class="ticket-button">Buy Tickets</button>
    </form>
</section>
