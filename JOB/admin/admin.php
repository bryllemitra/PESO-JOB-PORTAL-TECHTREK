<?php
include '../includes/config.php';
include '../includes/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/index.php");
    exit();
}

// Fetch the count of jobs
$job_query = "SELECT COUNT(*) AS total_jobs FROM jobs";
$job_result = $conn->query($job_query);
$job_data = $job_result->fetch_assoc();
$total_jobs = $job_data['total_jobs'];

// Fetch the count of users
$user_query = "SELECT COUNT(*) AS total_users FROM users";
$user_result = $conn->query($user_query);
$user_data = $user_result->fetch_assoc();
$total_users = $user_data['total_users'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom Styles -->
    <link rel="stylesheet" href="/JOB/assets/dashboard.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div>
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="admin.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="job_list.php"><i class="fas fa-briefcase"></i> Job List</a></li>
            <li><a href="user_list.php"><i class="fas fa-users"></i> Users</a></li>
        </ul>
    </div>
    <div class="toggle-btn" onclick="toggleSidebar()">
        <i class="fas fa-angle-right"></i>
    </div>
</div>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <div class="header">
        <h1>Admin Dashboard</h1>
    </div>

    <!-- Charts Row -->
    <div class="row mt-4 g-4">
        <!-- Pie Chart (Left Column) -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fas fa-users me-2"></i> User and Job Distribution
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <div class="chart-container">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bar Chart (Right Column) -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-2"></i> Jobs vs Users Comparison
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <div class="chart-container">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Section -->
    <div class="row mt-4 g-4">
        <div class="col-md-4">
            <div class="stats-card card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title text-success"><i class="fas fa-user-check me-2"></i> Active Users</h5>
                    <p class="card-text"><?= $total_users ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title text-info"><i class="fas fa-briefcase me-2"></i> Total Jobs</h5>
                    <p class="card-text"><?= $total_jobs ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title text-warning"><i class="fas fa-chart-line me-2"></i> Growth Rate</h5>
                    <p class="card-text">+12%</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pie Chart Script -->
<script>
    const ctxPie = document.getElementById('pieChart').getContext('2d');
    const pieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Users', 'Jobs Listed'],
            datasets: [{
                data: [<?= $total_users ?>, <?= $total_jobs ?>],
                backgroundColor: [
                    'rgba(74, 144, 226, 0.8)', // Blue
                    'rgba(28, 200, 138, 0.8)'  // Green
                ],
                borderColor: ['#ffffff', '#ffffff'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        font: {
                            size: 14,
                            family: 'Segoe UI'
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return `${context.label}: ${context.raw} (${Math.round(context.parsed * 100)}%)`;
                        }
                    }
                }
            },
            animation: {
                animateScale: true,
                duration: 1000
            }
        }
    });

    // Bar Chart for Jobs vs Users Comparison
    const ctxBar = document.getElementById('barChart').getContext('2d');
    const barChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: ['Jobs Listed', 'Registered Users'],
            datasets: [{
                label: 'Total Count',
                data: [<?= $total_jobs ?>, <?= $total_users ?>],
                backgroundColor: [
                    'rgba(74, 144, 226, 0.8)', // Blue
                    'rgba(28, 200, 138, 0.8)'  // Green
                ],
                borderColor: ['#ffffff', '#ffffff'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: 'Segoe UI'
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: 'Segoe UI'
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return `${context.dataset.label}: ${context.raw}`;
                        }
                    }
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutQuad'
            }
        }
    });
</script>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        sidebar.classList.toggle('hidden');
        mainContent.classList.toggle('hidden');
    }
</script>

</body>
</html>