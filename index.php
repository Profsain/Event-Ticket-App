<?php
include './db.php';
session_start();

$sql = "SELECT id, eventName, eventDate, eventTime, description, seatWithTable, seatWithoutTable FROM events";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Christmas Events Ticketing</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap" rel="stylesheet">
    <script>
        function handleBuyTickets(eventId) {
            <?php if (isset($_SESSION['user_id'])): ?>
                window.location.href = './public/purchase.php?event_id=' + eventId;
            <?php else: ?>
                alert('Please log in to purchase tickets.');
            <?php endif; ?>
        }
    </script>
</head>
<body>
    
    <?php include './includes/header.php'; ?>

    <main>
        <section id="hero" class="hero-sec">
            <h2>Celebrate the Joy of Christmas</h2>
            <p>Join us for a magical experience with events for everyone!</p>
            <a href="#events" class="cta-button">Explore Events</a>
        </section>
        
        <section id="events">
            <h2>Upcoming Events</h2>
            <p>Here are the events with an option to purchase tickets.</p>
            <p>Please log in to purchase tickets or view event details.</p>
            <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="event-card">
                <p style="color: #ff5722;"><?php echo htmlspecialchars($row['eventDate']); ?></p>
                    <h3 style="font-size: 25px; margin-top: -12px;"><?php echo htmlspecialchars($row['eventName']); ?></h3>
                    <p style="margin-top: -12px;"><?php echo strlen($row['description']) > 200 ? substr(htmlspecialchars($row['description']), 0, 200) . '...' : htmlspecialchars($row['description']); ?></p>
                   <div style="display: grid; margin-top: -12px;">
                   <p>Seat with Table: <?php echo htmlspecialchars($row['seatWithTable']); ?> </p> 
                    <p style="margin-top: -8px;">Seat without Table: <?php echo htmlspecialchars($row['seatWithoutTable']); ?></p>
                   </div>
                    
                    <button class="ticket-button" onclick="handleBuyTickets(<?php echo $row['id']; ?>)">Buy Tickets</button><br> <br>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No events available at the moment.</p>
        <?php endif; ?>

        </section>
    </main>
    <footer>
        <p>&copy; 2024 Christmas Events Ticketing. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
