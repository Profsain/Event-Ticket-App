<div class="sidebar">
    <div class="logo">
        <h2>EventDash</h2>
    </div>
    <nav>
    <ul>
                <li><a href="admin_dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php' ? 'active' : ''; ?>">Dashboard</a></li>
               <li><a href="manage_events.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_events.php' ? 'active' : ''; ?>">Manage Events</a></li>
                <li><a href="manage_customer.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_customer.php' ? 'active' : ''; ?>">Manage Users</a></li>
                <li><a href="manage_ticket.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'create_ticket.php' ? 'active' : ''; ?>">Manage Tickets</a></li>
                <!-- <li><a href="settings.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">Settings</a></li> -->
                <li><a href="logout.php">Logout</a></li>
                
                
            </ul>
        
    </nav>
</div>
