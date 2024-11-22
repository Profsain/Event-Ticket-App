<?php
include '../db.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Christmas Events</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .about-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .about-container h1 {
            color: #d32f2f;
            text-align: center;
            margin-bottom: 20px;
        }
        .about-container p {
            font-size: 16px;
            line-height: 1.6;
            color: #333;
            margin-bottom: 20px;
        }
        .about-container img {
            display: block;
            margin: 0 auto 20px;
            max-width: 100%;
            border-radius: 8px;
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

    <div class="about-container">
        <h1>About Us</h1>
        <img src="https://www.lighttrybe.ng/wp-content/uploads/2023/11/buy-christmas-trees-in-Nigeria.jpg" alt="Christmas Event">
        <p>Welcome to Christmas Events, your ultimate destination for celebrating the joy and magic of the holiday season. Our mission is to bring people together through a series of festive events that capture the spirit of Christmas.</p>
        <p>Founded in 2024, we have been dedicated to creating memorable experiences for families and friends. From enchanting Christmas markets to spectacular light shows, our events are designed to spread cheer and create lasting memories.</p>
        <p>Join us as we celebrate the most wonderful time of the year with events that are sure to delight and inspire. We look forward to welcoming you to our Christmas wonderland!</p>
    </div>

    <footer>
        <p>&copy; 2024 Christmas Events Ticketing. All rights reserved.</p>
    </footer>
</body>
</html>
