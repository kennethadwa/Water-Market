<!DOCTYPE html>
<html lang="en"> 
<head>
    <title>Customers</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal - Bootstrap 5 Admin Dashboard Template For Developers">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">    
    <link rel="shortcut icon" href="favicon.ico"> 
    
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
    <!-- jQuery and DataTables CSS/JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
    <style>
        .top-customers-section {
            margin-bottom: 30px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5em 1em;
        }
    </style>
</head> 

<body class="app">   	
    <header class="app-header fixed-top">	   	            
        <?php include('navbar.php'); ?>
        <?php include('sidebar.php'); ?>
    </header>
    
    <div class="app-wrapper">
        <div class="container">
            <h1 class="text-center mt-5">Top Customers</h1>
            <div class="row top-customers-section">
                <div class="col-md-12 mb-3">
                    <input type="search" id="topCustomerSearch" class="form-control" placeholder="Search Customer">
                </div>
                <?php 
                include('fetch_customers.php');
                foreach ($top_customers as $index => $top_customer): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo ($index + 1) . 'st'; ?> Customer</h5>
                                <p class="card-text"><?php echo htmlspecialchars($top_customer['customer_name']); ?></p>
                                <p class="card-text">Successful Transactions: <?php echo htmlspecialchars($top_customer['successful_transactions']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <hr>

            <h1 class="text-center">Customer Transactions</h1>
            <table id="customerTransactionsTable" class="table">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>First Transaction Date</th>
                        <th>Last Transaction Date</th>
                        <th>Successful Transactions</th>
                        <th>Pending Transactions</th>
                        <th>Refunds</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($customer['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($customer['first_transaction_date']); ?></td>
                            <td><?php echo htmlspecialchars($customer['last_transaction_date']); ?></td>
                            <td><?php echo htmlspecialchars($customer['successful_transactions']); ?></td>
                            <td><?php echo htmlspecialchars($customer['pending_transactions']); ?></td>
                            <td><?php echo htmlspecialchars($customer['refunds']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-3">
                <nav aria-label="Page navigation">
                    <ul id="pagination" class="pagination"></ul>
                </nav>
            </div>
        </div>
    </div>
    
    <!-- Javascript -->          
    <script src="assets/plugins/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>  
    <script src="assets/js/app.js"></script> 
    <script>
        $(document).ready(function() {
            var table = $('#customerTransactionsTable').DataTable({
                "paging": true,
                "searching": true,
                "info": true,
                "lengthChange": false,
                "pageLength": 10
            });

            $('#topCustomerSearch').keyup(function() {
                table.search($(this).val()).draw();
            });
        });
    </script>
</body>
</html>
