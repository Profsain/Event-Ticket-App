<?php
include 'config.php';
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$sql = "SELECT * FROM Event";
$result = $conn->query($sql);
?>

<h2>Available Events</h2>
<?php while($row = $result->fetch_assoc()) { ?>
    <div>
        <h3><?php echo $row['EventName']; ?></h3>
        <p><?php echo $row['Description']; ?></p>
        <p>Date: <?php echo $row['EventDate']; ?></p>
        <p>Time: <?php echo $row['EventTime']; ?></p>
        <form action="purchase.php" method="POST">
            <input type="hidden" name="event_id" value="<?php echo $row['EventID']; ?>">
            Number of Tickets: <input type="number" name="ticket_count" min="1" required>
            <input type="submit" value="Purchase">
        </form>
    </div>
<?php } ?>
