<!-- header.php -->
<header>
    <h1>Christmas Events</h1>
    <nav>
        <ul>
            <li><a href="#events">Events</a></li>
            <li><a href="./public/about.php">About Us</a></li>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="./public/myevents.php">My Tickets</a></li>
                <li><a href="./public/logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="./public/signup.php">Register</a></li>
                <li><a href="./public/login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
