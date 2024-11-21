<?php
include '../../db.php';

// Fetch purchase records
$sql_purchases = "
    SELECT 
        p.id AS purchase_id,
        u.FirstName AS customer_firstname,
        u.LastName AS customer_lastname,
        e.eventName,
        p.quantity,
        p.total_price,
        p.created_at
    FROM purchases p
    JOIN register u ON p.user_id = u.id  -- Updated to match the column name
    JOIN events e ON p.event_id = e.id  -- Updated to match the column name
    ORDER BY p.created_at DESC";
$result_purchases = $conn->query($sql_purchases);

if (!$result_purchases) {
    die("Error fetching purchases: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Ticket Purchases</title>
    <link rel="stylesheet" href="../../styles/dashboardstyle.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <header>
                <h1>Purchase Records</h1>
            </header>
            <section>
                <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th>Purchase ID</th>
                            <th>Customer Name</th>
                            <th>Event Name</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Purchase Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result_purchases->num_rows > 0) {
                            while ($row = $result_purchases->fetch_assoc()) {
                                echo "<tr>
                                    <td>" . htmlspecialchars($row['purchase_id']) . "</td>
                                    <td>" . htmlspecialchars($row['customer_firstname']) . " " . htmlspecialchars($row['customer_lastname']) . "</td>
                                    <td>" . htmlspecialchars($row['eventName']) . "</td>
                                    <td>" . htmlspecialchars($row['quantity']) . "</td>
                                    <td>$" . number_format($row['total_price'], 2) . "</td>
                                    <td>" . htmlspecialchars($row['created_at']) . "</td>
                                    <td>
                                        <button onclick=\"deletePurchase(" . htmlspecialchars($row['purchase_id']) . ")\">Delete</button>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' style='text-align: center;'>No purchases found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <script>
        // Handle delete purchase
        function deletePurchase(purchaseId) {
            if (confirm("Are you sure you want to delete this purchase?")) {
                window.location.href = `delete_purchase.php?id=${purchaseId}`;
            }
        }
    </script>
</body>
</html>
