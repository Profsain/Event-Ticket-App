<?php
session_start();
include '../db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch events that the user has purchased tickets for
$sql = "
    SELECT e.id, e.eventName, e.eventDate, e.description
    FROM purchases p
    JOIN events e ON p.event_id = e.id
    WHERE p.user_id = ?
    ORDER BY e.eventDate DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Events - Christmas Events</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .events-container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .event-card {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .event-card h3 {
            color: #d32f2f;
            margin-bottom: 10px;
        }
        .event-card p {
            font-size: 16px;
            color: #333;
        }
    </style>
</head>
<body>
      <header>
        <h1>Christmas Events</h1>
        <nav>
            <ul>
                <li><a href="../index.php">Events</a></li>
                <li><a href="./about.php">About Us</a></li>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="./myevents.php">My Tickets</a></li>
                    <li><a href="./logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="./signup.php">Register</a></li>
                    <li><a href="./login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>


    <div class="events-container">
        <h1>My Purchased Events</h1>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($event = $result->fetch_assoc()): ?>
                <div class="event-card">
                    <h3><?php echo htmlspecialchars($event['eventName']); ?></h3>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($event['eventDate']); ?></p>
                    <p><?php echo htmlspecialchars($event['description']); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>You have not purchased tickets for any events yet.</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2024 Christmas Events Ticketing. All rights reserved.</p>
    </footer>
</body>
</html>
