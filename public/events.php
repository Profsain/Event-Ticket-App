
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event Detail</title>
  <link rel="stylesheet" href="styles/styles.css">
  <link rel="stylesheet" href="styles/eventstyle.css">
  <style>
    #event-detail {
      font-size: 20px;
      margin-top: 20px;
    }
  </style>


</head>
<body>
  <h1>Event Details</h1>
  <div id="event-detail">Loading...</div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // Get the event ID from the URL
      const url = new URL(window.location.href);
      const eventId = url.pathname.split("/").pop();

      // Display the event ID
      const detailContainer = document.getElementById("event-detail");
      if (detailContainer) {
        detailContainer.textContent = `Event Detail for ID: ${eventId}`;
      }

      // (Optional) Fetch event details using the event ID
      fetch(`/api/events/${eventId}`)
        .then((response) => {
          if (!response.ok) {
            throw new Error("Failed to fetch event details");
          }
          return response.json();
        })
        .then((event) => {
          detailContainer.textContent = `Event: ${event.name}\nDescription: ${event.description}`;
        })
        .catch((error) => {
          detailContainer.textContent = "Error loading event details.";
          console.error(error);
        });
    });
  </script>
</body>
</html>


<?php
// Connect to the database
include '../db.php';

// Get the event ID from the URL
$eventId = $_GET['id'];

// Fetch the event details
$sql = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $eventId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $event = $result->fetch_assoc();
    echo json_encode($event);
} else {
    http_response_code(404);
    echo json_encode(["error" => "Event not found"]);
}
?>