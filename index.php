<?php
include './db.php';


// Fetch events from the database
$sql = "SELECT name, date, location FROM events";
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
</head>
<body>
    <!-- <header>
        <h1>Christmas Events</h1>
        <nav>
            <ul>
                <li><a href="#events">Events</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="./public/signup.php">Register</a></li>
                <li><a href="./public/login.php">Login</a></li>
            </ul>
        </nav>
    </header> -->
    <?php include './includes/header.php'; ?>
    <main>
        <section id="hero" class="hero-sec">
            <h2>Celebrate the Joy of Christmas</h2>
            <p>Join us for a magical experience with events for everyone!</p>
            <a href="#events" class="cta-button">Explore Events</a>
        </section>
        <section id="events">
            <h2>Upcoming Events</h2>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="event-card">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p>Date: <?php echo htmlspecialchars($row['date']); ?></p>
                        <p>Location: <?php echo htmlspecialchars($row['location']); ?></p>
                        <a href="#" class="ticket-button">Buy Tickets</a>
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
