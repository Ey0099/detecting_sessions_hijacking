<?php
session_start();
require_once 'db.php';  // Include database connection and functions

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch user logs
$db = getDBConnection();
$stmt = $db->prepare("SELECT * FROM logs WHERE user_id = ? ORDER BY timestamp DESC");
$stmt->execute([$userId]);
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the current session info (most recent log)
$currentSession = isset($logs[0]) ? $logs[0] : null;

// Get the last login date (second most recent log)
$lastLoginDate = isset($logs[1]['timestamp']) ? $logs[1]['timestamp'] : 'No previous login';

// Fetch and aggregate logs by day (for chart)
$stmt_agg = $db->prepare("
    SELECT DATE(timestamp) as log_date, COUNT(*) as session_count
    FROM logs
    WHERE user_id = ?
    GROUP BY log_date
    ORDER BY log_date ASC
");
$stmt_agg->execute([$userId]);
$logs_agg = $stmt_agg->fetchAll(PDO::FETCH_ASSOC);

// Aggregate the anomaly data by type for the chart
$stmt_anomaly = $db->prepare("
    SELECT anomaly_type, COUNT(*) as anomaly_count
    FROM anomalies
    WHERE user_id = ? AND anomaly_type IS NOT NULL
    GROUP BY anomaly_type
");
$stmt_anomaly->execute([$userId]);
$anomaly_data = $stmt_anomaly->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for the chart (Geo Shift, IP Change)
$anomaly_types = [];
$anomaly_counts = [];
foreach ($anomaly_data as $anomaly) {
    $anomaly_types[] = $anomaly['anomaly_type'];
    $anomaly_counts[] = $anomaly['anomaly_count'];
}
?>

<?php include('src/header.php'); ?>



<!-- Activity Logs Section with Table and Chart -->
<section class="py-5 bg-light" id="logs">
    <div class="container">
        <h2 class="text-center mb-4">Your Activity Logs</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert" style="border-radius: 10px;">
                        <table class="table table-bordered  mb-0">
                            <thead>
                            <tr>
                                <th colspan="3"><strong>Current Session Info</strong></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><strong>Session ID:</strong></td>
                                <td><strong>IP Address:</strong></td>
                                <td><strong>Last Login:</strong></td>

                            </tr>
                            <tr>
                                <td><?php echo htmlspecialchars($currentSession['session_id']); ?></td>
                                <td><?php echo htmlspecialchars($currentSession['ip_address']); ?></td>
                                <td><?php echo htmlspecialchars($lastLoginDate); ?></td>

                            </tr>

                            </tbody>
                        </table>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
            </div>
            <div class="col-md-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="mb-0">Activity Logs Table</h5>
                    </div>
                    <div class="card-body">
                        <table id="logsTable" class="table table-bordered table-hover">
                            <thead class="thead-dark">
                            <tr>
                                <th>Session ID</th>
                                <th>IP Address</th>
                                <th>Location</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($log['session_id']); ?></td>
                                    <td><?php echo htmlspecialchars($log['ip_address']); ?></td>
                                    <td><?php echo htmlspecialchars($log['geolocation']); ?></td>
                                    <td>
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#logModal<?php echo $log['id']; ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal for log details -->
                                <div class="modal fade" id="logModal<?php echo $log['id']; ?>" tabindex="-1" aria-labelledby="logModalLabel<?php echo $log['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="logModalLabel<?php echo $log['id']; ?>">Log Details (ID: <?php echo $log['id']; ?>)</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Log ID:</strong> <?php echo htmlspecialchars($log['id']); ?></p>
                                                <p><strong>IP Address:</strong> <?php echo htmlspecialchars($log['ip_address']); ?></p>
                                                <p><strong>Session ID:</strong> <?php echo htmlspecialchars($log['session_id']); ?></p>
                                                <p><strong>Geolocation:</strong> <?php echo htmlspecialchars($log['geolocation']); ?></p>
                                                <p><strong>Device Info:</strong> <?php echo htmlspecialchars($log['device_info']); ?></p>
                                                <p><strong>Timestamp:</strong> <?php echo htmlspecialchars($log['timestamp']); ?></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="mb-0">Session Activity Chart</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="sessionChart"></canvas>
                    </div>
                </div>
            </div>
            <!-- Chart for anomaly types -->
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="mb-0">Anomaly Type Chart</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="anomalyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Include jQuery, DataTables, and Chart.js in the correct order -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Initialize DataTable
    $(document).ready(function () {
        $('#logsTable').DataTable();
    });

    // Prepare data for the chart
    const ctx = document.getElementById('sessionChart').getContext('2d');
    const sessionData = {
        labels: [
            <?php foreach ($logs_agg as $log): ?>
            '<?php echo htmlspecialchars($log['log_date']); ?>',
            <?php endforeach; ?>
        ],
        datasets: [{
            label: 'Sessions per Day',
            data: [
                <?php foreach ($logs_agg as $log): ?>
                <?php echo htmlspecialchars($log['session_count']); ?>,
                <?php endforeach; ?>
            ],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2,
            tension: 0.3,  // Smoother curve
            fill: true
        }]
    };

    // Create animated bar chart
    const sessionChart = new Chart(ctx, {
        type: 'line',  // Line chart
        data: sessionData,
        options: {
            responsive: true,
            animation: {
                duration: 3000,  // Longer animation duration
                easing: 'easeInOutQuart'  // Smooth easing
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Session Count'
                    },
                    beginAtZero: true
                }
            }
        }
    });

    // Anomaly Type Chart
    const ctxAnomaly = document.getElementById('anomalyChart').getContext('2d');
    const anomalyData = {
        labels: <?php echo json_encode($anomaly_types); ?>,
        datasets: [{
            label: 'Anomaly Counts',
            data: <?php echo json_encode($anomaly_counts); ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)'
            ],
            borderWidth: 1
        }]
    };

    const anomalyChart = new Chart(ctxAnomaly, {
        type: 'pie',  // Can also be 'pie' or 'doughnut' for different types
        data: anomalyData,
        options: {
            responsive: true,
            scales: {
                // y: {
                //     beginAtZero: true
                // }
            }
        }
    });
</script>

<?php include('src/footer.php'); ?>
