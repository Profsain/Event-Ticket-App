<!-- <?php
include 'config.php';
session_start();

// Check admin login session
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch event and sale data
$sql = "SELECT * FROM Sale JOIN Event ON Sale.EventID = Event.EventID";
$result = $conn->query($sql);
?>

<h2>Dashboard</h2>
<?php while($row = $result->fetch_assoc()) { ?>
    <div>
        <p>Event: <?php echo $row['EventName']; ?></p>
        <p>Tickets Sold: <?php echo $row['NumberOfTicketsSold']; ?></p>
        <p>Total Sales: <?php echo $row['TotalSales']; ?></p>
        <a href="edit_sale.php?id=<?php echo $row['SaleID']; ?>">Edit</a> |
        <a href="delete_sale.php?id=<?php echo $row['SaleID']; ?>">Delete</a>
    </div>
<?php } ?> -->

