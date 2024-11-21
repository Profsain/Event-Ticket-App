<?php
session_start();
include '../../db.php';

// Fetch sales data
$sql_purchases = "SELECT 
                    registerId, 
                    eventId, 
                    SUM(numberOfTicketSold) AS total_tickets, 
                    SUM(totalSales) AS total_sales, 
                    MAX(createdAt) AS date_purchase
                  FROM sales 
                  GROUP BY registerId, eventId 
                  ORDER BY date_purchase DESC";

$result_purchases = $conn->query($sql_purchases);
$purchases_data = [];

// Fetch data and handle potential errors
if ($result_purchases) {
    if ($result_purchases->num_rows > 0) {
        while ($row = $result_purchases->fetch_assoc()) {
            $purchases_data[] = $row;
        }
    }
} else {
    // Log database query errors if any
    error_log("SQL Error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sales Dashboard</title>
    <link rel="stylesheet" href="../../styles/dashboardstyle.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <header>
                <h1>Sales Report</h1>
            </header>
            <section class="sales-data">
                <table>
                    <thead>
                        <tr>
                            <th>Register ID</th>
                            <th>Event ID</th>
                            <th>Number of Tickets Sold</th>
                            <th>Total Sales</th>
                            <th>Date Purchase </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($purchases_data)): ?>
                            <?php foreach ($purchases_data as $purchase): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($purchase['registerId']); ?></td>
                                    <td><?php echo htmlspecialchars($purchase['eventId']); ?></td>
                                    <td><?php echo htmlspecialchars($purchase['total_tickets']); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($purchase['total_sales'], 2)); ?></td>
                                    <td><?php echo htmlspecialchars($purchase['date_purchase']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No sales data available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>
