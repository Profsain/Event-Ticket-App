<?php
session_start();
include '../../db.php';

// Fetch data for statistics

// Total Events
$sql_events = "SELECT COUNT(*) AS total_events FROM events";
$result_events = $conn->query($sql_events);

$total_events = ($result_events && $result_events->num_rows > 0) 
    ? $result_events->fetch_assoc()['total_events'] 
    : 0;

// Registered Users
$sql_users = "SELECT COUNT(*) AS total_users FROM customers";
$result_users = $conn->query($sql_users);

$total_users = ($result_users && $result_users->num_rows > 0) 
    ? $result_users->fetch_assoc()['total_users'] 
    : 0;

// Number of Tickets Sold
$sql_tickets = "SELECT SUM(numberOfTicketSold) AS total_tickets FROM sales";
$result_tickets = $conn->query($sql_tickets);

$total_tickets = ($result_tickets && $result_tickets->num_rows > 0) 
    ? $result_tickets->fetch_assoc()['total_tickets'] 
    : 0;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../styles/dashboardstyle.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <header>
                <h1>Dashboard</h1>
            </header>
            <section class="welcome-banner">
                <h2>Welcome, Admin!</h2>
                <p>Manage events, users, and view reports all in one place.</p>
            </section>
            <section class="stats">
                <div class="stat-card">
                    <h3>Total Number of Events</h3>
                    <p><?php echo $total_events; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Number of Registered Users</h3>
                    <p><?php echo $total_users; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Number of Tickets Sold</h3>
                     <p><?php echo $total_tickets; ?></p> 
                </div>
            </section>

            <section>
            <?php include 'overview.php'; ?>
            </section>
        </main>
    </div>
</body>
</html>


