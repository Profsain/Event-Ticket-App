<?php
include '../../db.php';

// Check if an ID is provided
if (isset($_GET['id'])) {
    $purchase_id = $_GET['id'];

    // SQL query to delete the purchase
    $sql_delete = "DELETE FROM purchases WHERE id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $purchase_id);

    // Execute the query and check for success
    if ($stmt->execute()) {
        // Redirect to the purchases page after successful deletion
        header("Location: purchase_records.php");
        exit();
    } else {
        // If the deletion fails, show an error
        die("Error deleting purchase: " . $conn->error);
    }
} else {
    // If no ID is provided, redirect to the purchases page
    header("Location: purchase_records.php");
    exit();
}
?>
