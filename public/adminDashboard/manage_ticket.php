<?php
session_start();
include '../../db.php';
?>
<?php 
// Fetch tickets for display
$sql_tickets = "SELECT tc.id, et.eventName, tc.seat_type, tc.price 
                FROM tickets_created tc 
                JOIN events et ON tc.event_id = et.id 
                ORDER BY et.eventName, tc.seat_type";
$result_tickets = $conn->query($sql_tickets);
if (!$result_tickets) {
    die("Error fetching tickets: " . $conn->error);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tickets</title>
    <link rel="stylesheet" href="../../styles/dashboardstyle.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <header>
                <h1>Manage Tickets</h1>
            </header>
            <section class="stats">
                <div class="stat-card">
                    <a href="create_ticket.php" style="text-decoration: none;"><h3>Create New Tickets</h3></a>
                </div>
                <div class="stat-card">
                    <a href="sales_records.php" style="text-decoration: none;"><h3>View Sales Report</h3></a>
                </div>
            </section>
            <br>
            <section>
                <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Event Name</th>
                            <th>Seat Type</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result_tickets->num_rows > 0) {
                            while ($row = $result_tickets->fetch_assoc()) {
                                echo "<tr id='ticket-row-" . htmlspecialchars($row['id']) . "'>
                                    <td>" . htmlspecialchars($row['id']) . "</td>
                                    <td>" . htmlspecialchars($row['eventName']) . "</td>
                                    <td>" . htmlspecialchars($row['seat_type']) . "</td>
                                    <td>$" . number_format($row['price'], 2) . "</td>
                                    <td><button onclick=\"deleteTicket(" . htmlspecialchars($row['id']) . ")\">Delete</button></td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' style='text-align: center;'>No tickets available</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <!-- JavaScript Code -->
    <script>
        function deleteTicket(ticketId) {
            if (confirm("Are you sure you want to delete this ticket?")) {
                fetch('delete_ticket.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: ticketId }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Ticket deleted successfully!");
                        document.querySelector(`#ticket-row-${ticketId}`).remove();
                    } else {
                        alert("Error deleting ticket: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred while deleting the ticket.");
                });
            }
        }
    </script>
</body>
</html>



