<?php
include '../../db.php';

// Fetch user registration data for the graph
$sql = "SELECT MONTHNAME(createdAt) AS month, COUNT(*) AS count FROM customers GROUP BY MONTH(createdAt)";
$result = $conn->query($sql);

$months = [];
$counts = [];

while ($row = $result->fetch_assoc()) {
    $months[] = $row['month'];
    $counts[] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../../styles/dashboardstyle.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="dashboard-container">
        
        <main class="main-content">
            
            <section class="overview-graph">
                <h2>Overview</h2>
                <canvas id="myChart" width="400" height="200"></canvas>
            </section>
        </main>
    </div>

    <script>
        const labels = <?php echo json_encode($months); ?>;
        const data = <?php echo json_encode($counts); ?>;

        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Users Registered Per Month',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
