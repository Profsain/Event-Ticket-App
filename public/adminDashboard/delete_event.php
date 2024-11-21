<?php
session_start();
include '../../db.php';

// Check if an ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Event</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <script>
        // Show confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this action!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff5722',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to the delete action
                window.location.href = "delete_event.php?confirm=yes&id=<?php echo $id; ?>";
            } else {
                // Redirect back to the admin dashboard
                window.location.href = "manage_events.php";
            }
        });
    </script>
</body>
</html>

<?php
// Perform the deletion only if confirmed
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    $sql = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        // Redirect back to the dashboard after successful deletion
        header("Location: manage_events.php");
        exit();
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}
?>
