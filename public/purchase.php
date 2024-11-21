<?php
session_start();
include '../db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if event_id is passed in the URL and is numeric
if (!isset($_GET['event_id']) || !is_numeric($_GET['event_id'])) {
    die("Invalid event ID.");
}

$event_id = (int) $_GET['event_id'];
$user_id = (int) $_SESSION['user_id'];

// Verify if the user exists in the customers table
$sql_user_check = "SELECT id FROM customers WHERE id = ?";
$stmt_user_check = $conn->prepare($sql_user_check);
$stmt_user_check->bind_param("i", $user_id);
$stmt_user_check->execute();
$result_user_check = $stmt_user_check->get_result();

if ($result_user_check->num_rows === 0) {
    die("Invalid user. Please contact support.");
}

// Fetch event details
$sql_event_check = "SELECT id, eventName, eventDate, description FROM events WHERE id = ?";
$stmt_event_check = $conn->prepare($sql_event_check);
$stmt_event_check->bind_param("i", $event_id);
$stmt_event_check->execute();
$result_event_check = $stmt_event_check->get_result();

if ($result_event_check->num_rows === 0) {
    die("Event not found.");
}
$event = $result_event_check->fetch_assoc();

// Fetch available tickets for the event
$sql_tickets = "SELECT seat_type, price FROM tickets_created WHERE event_id = ?";
$stmt_tickets = $conn->prepare($sql_tickets);
$stmt_tickets->bind_param("i", $event_id);
$stmt_tickets->execute();
$result_tickets = $stmt_tickets->get_result();

if ($result_tickets->num_rows === 0) {
    die("No tickets available for this event.");
}

// Handle the purchase form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seat_type = $_POST['seat_type'] ?? '';
    $quantity = (int) ($_POST['quantity'] ?? 1);

    // Validate input
    if ($quantity < 1) {
        die("Invalid quantity selected.");
    }

    // Find the price for the selected ticket type
    $sql_ticket_price = "SELECT price FROM tickets_created WHERE event_id = ? AND seat_type = ?";
    $stmt_price = $conn->prepare($sql_ticket_price);
    $stmt_price->bind_param("is", $event_id, $seat_type);
    $stmt_price->execute();
    $result_price = $stmt_price->get_result();

    if ($result_price->num_rows === 0) {
        die("Invalid ticket type selected.");
    }
    $ticket = $result_price->fetch_assoc();
    $total_price = $ticket['price'] * $quantity;

    // Insert the purchase record into the database
    $sql_purchase = "INSERT INTO purchases (user_id, event_id, seat_type, quantity, total_price) VALUES (?, ?, ?, ?, ?)";
    $stmt_purchase = $conn->prepare($sql_purchase);
    $stmt_purchase->bind_param("iisid", $user_id, $event_id, $seat_type, $quantity, $total_price);

    if (!$stmt_purchase->execute()) {
        die("An error occurred while processing your purchase. Please try again later.");
    }

    // Update or insert sales data
    $sql_sales_check = "SELECT * FROM sales WHERE eventId = ?";
    $stmt_sales_check = $conn->prepare($sql_sales_check);
    $stmt_sales_check->bind_param("i", $event_id);
    $stmt_sales_check->execute();
    $result_sales_check = $stmt_sales_check->get_result();

    if ($result_sales_check->num_rows > 0) {
        // Update existing sales record
        $sql_sales_update = "UPDATE sales SET 
                             numberOfTicketSold = numberOfTicketSold + ?, 
                             totalSales = totalSales + ? 
                             WHERE eventId = ?";
        $stmt_sales_update = $conn->prepare($sql_sales_update);
        $stmt_sales_update->bind_param("idi", $quantity, $total_price, $event_id);
        $stmt_sales_update->execute();
    } else {
        // Insert new sales record
        $sql_sales_insert = "INSERT INTO sales (eventId, registerId, numberOfTicketSold, totalSales) VALUES (?, ?, ?, ?)";
        $stmt_sales_insert = $conn->prepare($sql_sales_insert);
        $stmt_sales_insert->bind_param("iiid", $event_id, $user_id, $quantity, $total_price);
        $stmt_sales_insert->execute();
    }

    // Redirect with success message
    echo "<script>alert('Purchase successful!'); window.location.href = '../index.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Ticket</title>
    <style>
        .purchase-container {
            width: 80%;
            max-width: 1000px;
            margin: 30px auto;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }

        .purchase-container h1 {
            font-size: 28px;
            color: #333;
            text-align: center;
        }

        .purchase-container h2 {
            color: #333;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .purchase-container p {
            font-size: 16px;
            line-height: 1.6;
            color: #666;
            margin-bottom: 20px;
        }

        .ticket-option {
            margin-bottom: 20px;
            padding-left: 20px;
        }

        .ticket-option label {
            font-size: 18px;
            margin-right: 10px;
            color: #333;
        }

        .ticket-option input[type="radio"] {
            margin-right: 10px;
            margin-top: 10px;
        }

        .ticket-option input[type="number"] {
            width: 60px;
            padding: 5px;
            margin-left: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .total-price {
            font-size: 22px;
            font-weight: bold;
            color: #28a745;
            margin-top: 20px;
            text-align: center;
        }

        .btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            margin-top: 30px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            background-color: #218838;
        }

        .form-container {
            margin-top: 30px;
        }

        .ticket-description {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        }

        .ticket-description h2 {
            color: #333;
            font-size: 24px;
        }

        .ticket-description p {
            font-size: 16px;
            color: #666;
        }

        .ticket-description div {
            margin-bottom: 10px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 90%;
            }

            h1 {
                font-size: 24px;
            }

            .btn {
                font-size: 20px !important;
                padding: 10px 15px; font-weight: 600px;
            }

            .ticket-description h2 {
                font-size: 22px;
            }

            .ticket-option input[type="number"] {
                width: 50px;
            }

            .total-price {
                font-size: 18px;
            }
        }
    </style>
    <link rel="stylesheet" href="../styles/formstyle.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
<header>
    <h1>Christmas Events</h1>
    <nav>
        <ul>
            <li><a href="../index.php">Events</a></li>
            <li><a href="#about">About Us</a></li>
            <li><a href="./signup.php">Register</a></li>
            <li><a href="./login.php">Login</a></li>
        </ul>
    </nav>
</header>

<div class="purchase-container">
    <h1>Purchase Ticket for Event: <?php echo htmlspecialchars($event['eventName']); ?></h1>
    <div class="ticket-description">
    <h2>Event Details</h2>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($event['eventDate']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($event['description']); ?></p>
    </div>

    <form method="POST" action="" class="form-container">
        <h2>Select Ticket Type</h2>
        <?php while ($row = $result_tickets->fetch_assoc()): ?>
            <div class="ticket-option">
                <input type="radio" name="seat_type" id="<?php echo htmlspecialchars($row['seat_type']); ?>" value="<?php echo htmlspecialchars($row['seat_type']); ?>" data-price="<?php echo htmlspecialchars($row['price']); ?>" required>
                <label for="<?php echo htmlspecialchars($row['seat_type']); ?>">
                    <?php echo htmlspecialchars($row['seat_type']); ?> - $<?php echo number_format($row['price'], 2); ?>
                </label>
            </div>
        <?php endwhile; ?>
        <div >
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" value="1" min="1" required>
        </div>
        <div id="total-price" class="total-price" id="total-price">Total Price: $0.00</div>
        <button type="submit" class="btn">Buy Ticket Now</button>
    </form>
</div>
<script>
    document.querySelectorAll('input[name="seat_type"]').forEach(input => {
        input.addEventListener('change', calculateTotal);
    });
    document.querySelector('#quantity').addEventListener('input', calculateTotal);

    function calculateTotal() {
        const quantity = parseInt(document.querySelector('#quantity').value) || 1;
        const selectedTicket = document.querySelector('input[name="seat_type"]:checked');
        if (selectedTicket) {
            const price = parseFloat(selectedTicket.dataset.price);
            const totalPrice = quantity * price;
            document.querySelector('#total-price').textContent = `Total Price: $${totalPrice.toFixed(2)}`;
        }
    }
</script>
</body>
</html>
