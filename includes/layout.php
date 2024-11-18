<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="/christmasTicketCMS/public/styles/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Christmas Events</h1>
        <?php include 'navbar.php'; ?>
    </header>
    <main>
        <?php echo $content; ?>
    </main>
    <footer>
        <p>&copy; 2024 Christmas Events Ticketing. All rights reserved.</p>
    </footer>
</body>
</html> 