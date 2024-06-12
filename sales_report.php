<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sales Report</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal - Bootstrap 5 Admin Dashboard Template For Developers">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">
    <link rel="shortcut icon" href="favicon.ico">
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
        .add-transaction-btn {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5em 1em;
        }
    </style>
    <!-- jQuery and DataTables CSS/JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
</head>
<body class="app">
    <header class="app-header fixed-top">
        <?php @include('navbar.php'); ?>
        <?php @include('sidebar.php'); ?>
    </header>

    <div class="app-wrapper">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center my-4">
                <h1>Sales Report</h1>
                <div class="add-transaction-btn">
                    <a href="add_transaction.php" class="btn btn-success">
                        <i class="fas fa-plus"></i> Add Transaction
                    </a>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>
                        Show 
                        <select id="entries" class="form-select form-select-sm" aria-controls="salesReportTable">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        entries
                    </label>
                </div>
                <div class="col-md-6 text-end">
                    <input type="search" id="customSearchBox" class="form-control form-control-sm" placeholder="Search...">
                </div>
            </div>
            <table id="salesReportTable" class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Transaction Date</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Product</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Unit Price</th>
                        <th scope="col">Total Price</th>                    
                        <th scope="col">Transaction Type</th>
                        <th scope="col">Payment Method</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include('config.php');

                    try {
                        $sql = "
                            SELECT 
                                s.transaction_date,
                                s.customer_name,
                                p.product_name,
                                s.quantity,
                                p.price AS unit_price,
                                (s.quantity * p.price) AS total_price,
                                s.transaction_type,
                                sr.report_id,
                                sr.payment_method
                            FROM 
                                sales_report sr
                            INNER JOIN 
                                sales s ON sr.sale_id = s.sale_id
                            INNER JOIN 
                                products p ON s.product_id = p.product_id
                        ";

                        $stmt = $conn->prepare($sql);
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['transaction_date']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['customer_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['product_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['quantity']) . '</td>';
                            echo '<td>₱' . htmlspecialchars($row['unit_price']) . '</td>';
                            echo '<td>₱' . htmlspecialchars($row['total_price']) . '</td>';       
                            echo '<td>' . htmlspecialchars($row['transaction_type']) . '</td>';
                            echo '<td>';
                            echo '<select class="form-select form-select-sm" id="payment_method_' . htmlspecialchars($row['report_id']) . '">';
                            $methods = ['Cash', 'Gcash', 'Paymaya'];
                            foreach ($methods as $method) {
                                $selected = ($method == $row['payment_method']) ? 'selected' : '';
                                echo '<option value="' . $method . '" ' . $selected . '>' . $method . '</option>';
                            }
                            echo '</select>';
                            echo '</td>';
                            echo '<td>';
                            echo '<button type="button" class="btn btn-primary btn-sm m-1" onclick="updatePaymentMethod(' . htmlspecialchars($row['report_id']) . ')"><i class="fas fa-edit"></i> Edit</button>';
                            echo '<form method="POST" action="delete_sale.php" style="display:inline-block;" onsubmit="return confirm(\'Are you sure you want to delete this transaction?\');">';
                            echo '<input type="hidden" name="report_id" value="' . htmlspecialchars($row['report_id']) . '">';
                            echo '<button type="submit" class="btn btn-danger btn-sm m-1"><i class="fas fa-trash-alt"></i> Delete</button>';
                            echo '</form>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } catch (PDOException $e) {
                        echo 'Error: ' . $e->getMessage();
                    }
                    ?>
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
            var table = $('#salesReportTable').DataTable({
                "paging": true,
                "searching": true,
                "info": true,
                "lengthChange": false,
                "pageLength": 10,
                "dom": 'lrtip' // Remove the default search box
            });

            $('#entries').change(function() {
                table.page.len($(this).val()).draw();
            });

            $('#customSearchBox').keyup(function() {
                table.search($(this).val()).draw();
            });
        });

        function updatePaymentMethod(reportId) {
            var paymentMethod = $('#payment_method_' + reportId).val();
            $.ajax({
                url: 'update_payment_method.php',
                type: 'POST',
                data: { report_id: reportId, payment_method: paymentMethod },
                success: function(response) {
                    alert('Payment method updated successfully.');
                    location.reload(); 
                },
                error: function(xhr, status, error) {
                    console.error('Error updating payment method:', error);
                    alert('Failed to update payment method. Please try again.');
                }
            });
        }
    </script>
</body>
</html>
