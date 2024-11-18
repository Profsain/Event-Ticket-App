<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_SESSION['customer_id'];
    $event_id = $_POST['event_id'];
    $ticket_count = $_POST['ticket_count'];
    $total_price = 100 * $ticket_count; // Example price calculation

    $sql = "INSERT INTO Sale (CustomerID, EventID, NumberOfTicketsSold, PaymentDate, TotalSales)
            VALUES ('$customer_id', '$event_id', '$ticket_count', NOW(), '$total_price')";

    if ($conn->query($sql) === TRUE) {
        echo "Ticket purchased successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
