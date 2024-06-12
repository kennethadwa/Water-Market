<?php
session_start();
require_once 'config.php';

// Check for 'remember_me' cookie and handle session
if (isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];
    $sql = "SELECT user_id, name, role FROM users WHERE remember_token = :token";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
        }
    } catch (PDOException $e) {
        error_log('PDOException: ' . $e->getMessage(), 3, 'errors.log');
        echo '<div class="alert alert-danger" role="alert">An error occurred while handling the session.</div>';
    }
}

// Redirect if not logged in or not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch user name based on user_id
$user_id = $_SESSION['user_id'] ?? null;
$user_name = 'Developer'; // Default name

if ($user_id) {
    try {
        $stmt = $conn->prepare('SELECT name FROM users WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $user_name = htmlspecialchars($user['name']); // Ensure safe output
        }
    } catch (PDOException $e) {
        error_log('PDOException: ' . $e->getMessage(), 3, 'errors.log');
        echo '<div class="alert alert-danger" role="alert">An error occurred while fetching the user name.</div>';
    }
}

// Fetch total sales
$totalSales = 0;
$sql = "SELECT SUM(total_price) AS total_sales FROM sales WHERE payment_status = 'Paid'";
try {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result && isset($result['total_sales'])) {
        $totalSales = $result['total_sales'];
    }
} catch (PDOException $e) {
    error_log('PDOException: ' . $e->getMessage(), 3, 'errors.log');
    echo '<div class="alert alert-danger" role="alert">An error occurred while fetching the total sales.</div>';
}

// Fetch the total number of products
$totalProducts = 0;
$sql = "SELECT COUNT(*) AS total_products FROM products";
try {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result && isset($result['total_products'])) {
        $totalProducts = $result['total_products'];
    }
} catch (PDOException $e) {
    error_log('PDOException: ' . $e->getMessage(), 3, 'errors.log');
    echo '<div class="alert alert-danger" role="alert">An error occurred while fetching the total products.</div>';
}

// Fetch the number of successful transactions
$successfulTransactions = 0;
$sql = "SELECT COUNT(*) AS successful_transactions FROM sales WHERE payment_status = 'Paid'";
try {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result && isset($result['successful_transactions'])) {
        $successfulTransactions = $result['successful_transactions'];
    }
} catch (PDOException $e) {
    error_log('PDOException: ' . $e->getMessage(), 3, 'errors.log');
    echo '<div class="alert alert-danger" role="alert">An error occurred while fetching the successful transactions.</div>';
}

// Fetch the number of refunded transactions
$refundedTransactions = 0;
$sql = "SELECT COUNT(*) AS refunded_transactions FROM sales WHERE payment_status = 'refund'";
try {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result && isset($result['refunded_transactions'])) {
        $refundedTransactions = $result['refunded_transactions'];
    }
} catch (PDOException $e) {
    error_log('PDOException: ' . $e->getMessage(), 3, 'errors.log');
    echo '<div class="alert alert-danger" role="alert">An error occurred while fetching the refunded transactions.</div>';
}

// Fetch total sales per month where payment_status is 'Paid'
$salesData = [];
$sql = "SELECT DATE_FORMAT(transaction_date, '%Y-%m') AS month, SUM(total_price) AS total_sales
        FROM sales
        WHERE payment_status = 'Paid'
        GROUP BY month
        ORDER BY month ASC";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $salesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('PDOException: ' . $e->getMessage(), 3, 'errors.log');
    echo '<div class="alert alert-danger" role="alert">An error occurred while fetching the monthly sales data.</div>';
}

// Fetch the number of paid transactions per month
$salesData_bar = [];
$query = "SELECT MONTHNAME(transaction_date) AS month, COUNT(*) AS total_paid_transactions
          FROM sales
          WHERE payment_status = 'Paid'
          GROUP BY MONTH(transaction_date)";

try {
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $salesData_bar = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('PDOException: ' . $e->getMessage(), 3, 'errors.log');
    echo '<div class="alert alert-danger" role="alert">An error occurred while fetching the monthly paid transactions data.</div>';
}
?>

<!DOCTYPE html>
<html lang="en"> 
<head>
    <title>Water Market Dashboard</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal - Bootstrap 5 Admin Dashboard Template For Developers">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">    
    <link rel="shortcut icon" href="favicon.ico"> 
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
</head> 
<body class="app">   	
    <header class="app-header fixed-top">	   	            
        <?php include('navbar.php'); ?>
        <?php include('sidebar.php'); ?>
    </header>
    <div class="app-wrapper">
        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <h1 class="app-page-title">Dashboard</h1>
                <div class="app-card alert alert-dismissible shadow-sm mb-4 border-left-decoration" role="alert">
    <div class="inner">
        <div class="app-card-body p-3 p-lg-4">
            <h3 class="mb-3">Welcome, <?php echo $user_name; ?>!</h3>
            <div class="row gx-5 gy-3">
                <div class="col-12 col-lg-9">
                    <div>Water Market is a water refilling station that sells purified and alkaline waters.</div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
                <div class="row g-4 mb-4">
                    <div class="col-6 col-lg-3">
                        <div class="app-card app-card-stat shadow-sm h-100">
                            <div class="app-card-body p-3 p-lg-4">
                                <h4 class="stats-type mb-1">Total Sales</h4>
                                <div class="stats-figure">₱<?php echo number_format($totalSales, 2); ?></div>
                                <div class="stats-meta text-success">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
                                    </svg> 20%
                                </div>
                            </div>
                            <a class="app-card-link-mask" href="#"></a>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="app-card app-card-stat shadow-sm h-100">
                            <div class="app-card-body p-3 p-lg-4">
                                <h4 class="stats-type mb-1">Total Products</h4>
                                <div class="stats-figure"><?php echo $totalProducts; ?></div>
                                <div class="stats-meta text-success">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-box-seam" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M2.5 1a.5.5 0 0 1 .5.5v13a.5.5 0 0 1-.5.5H.5a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 .5-.5h2zM4.5 1.5v13h7v-13h-7zm5.5 1v6.5l-3.5 2.5v-11h3z"/>
                                    </svg> Total Products
                                </div>
                            </div>
                            <a class="app-card-link-mask" href="#"></a>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="app-card app-card-stat shadow-sm h-100">
                            <div class="app-card-body p-3 p-lg-4">
                                <h4 class="stats-type mb-1">Successful Transactions</h4>
                                <div class="stats-figure"><?php echo $successfulTransactions; ?></div>
                                <div class="stats-meta text-success">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" clas     bi-check-circle"            fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm-.354 12.8     5 0 0 1-.           708 0l-3-3a.5.5 0 1 1 .708-.708L7 11.293l5.646-5.647a.     0 1 .708.708l-6 6z"/>
                                    </svg> 
                                    Completed Payments
                                </div>
                            </div>
                            <a class="app-card-link-mask" href="#"></a>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="app-card app-card-stat shadow-sm h-100">
                            <div class="app-card-body p-3 p-lg-4">
                                <h4 class="stats-type mb-1">Refunded Transactions</h4>
                                <div class="stats-figure"><?php echo $refundedTransactions; ?></div>
                                <div class="stats-meta text-danger">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x-cirfill="currentColor"          xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm.354 4.146a.50 1 .708          0l3.5 3.5a.5.5 0 0 1 0 .708l-3.5 3.5a.5.5 0 0 1-.708-.708L108 8.854 6.854a.5.5 0 0 1          0-.708l-.5-.5a.5.5 0 0 1 .708-.708l.5.5a.5.51 0 .708L8 6.293 6.854 8 8 8.854a.5.5 0 0          1 0 .708l-.5.5a.5.5 0 0 1-.708L5.707 8l2.147-2.146z"/>
                                    </svg>
                                    Canceled Payments
                                </div>
                            </div>
                            <a class="app-card-link-mask" href="#"></a>
                        </div>
                             </div>
                       <div class="row g-4 mb-4">             
                         <div class="row g-4 mb-4">
                             <div class="col-12 col-lg-6">
                                 <div class="app-card app-card-chart h-100 shadow-sm">
                                     <div class="app-card-header p-3">
                                         <h4 class="app-card-title">Line Chart Example</h4>
                                     </div>
                                     <div class="app-card-body p-3 p-lg-4">
                                         <canvas id="chart-line"></canvas>
                                     </div>
                                 </div>
                             </div>
         
                             <div class="col-12 col-lg-6">
                                 <div class="app-card app-card-chart h-100 shadow-sm">
                                     <div class="app-card-header p-3">
                                         <h4 class="app-card-title">Bar Chart Example</h4>
                                     </div>
                                     <div class="app-card-body p-3 p-lg-4">
                                         <canvas id="chart-bar"></canvas>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/plugins/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/plugins/chart.js/chart.min.js"></script>
    <script src="assets/js/index-charts.js"></script> 
    <script src="assets/js/app.js"></script> 
		<script>
			// Ensure the document is fully loaded before running scripts
document.addEventListener('DOMContentLoaded', function() {
    // Line Chart Data
    const salesData = <?php echo json_encode($salesData); ?>;
    const lineChartMonths = salesData.map(item => item.month);
    const lineChartTotals = salesData.map(item => item.total_sales);

    // Bar Chart Data
    const barChartData = <?php echo json_encode($salesData_bar); ?>;
    const barChartMonths = barChartData.map(item => item.month);
    const barChartTransactions = barChartData.map(item => item.total_paid_transactions || 0);

    // Initialize Line Chart
    const lineChartCtx = document.getElementById('chart-line').getContext('2d');
    const lineChart = new Chart(lineChartCtx, {
        type: 'line',
        data: {
            labels: lineChartMonths,
            datasets: [{
                label: 'Total Sales per Month',
                data: lineChartTotals,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                x: {
                    type: 'category',
                    title: { display: true, text: 'Month' }
                },
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Total Sales ($)' }
                }
            }
        }
    });

    // Initialize Bar Chart
    const barChartCtx = document.getElementById('chart-bar').getContext('2d');
    const barChart = new Chart(barChartCtx, {
        type: 'bar',
        data: {
            labels: barChartMonths,
            datasets: [{
                label: 'Number of Paid Transactions per Month',
                data: barChartTransactions,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                x: {
                    type: 'category',
                    title: { display: true, text: 'Month' }
                },
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Number of Paid Transactions' }
                }
            }
        }
    });
});

		</script>
</body>
</html>
