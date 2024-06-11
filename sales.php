<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sales Records</title>
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
            border: 3px solid black; 
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
        .add-transaction-btn {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="app">
    <header class="app-header fixed-top">
        <?php @include('navbar.php'); ?>
        <?php @include('sidebar.php'); ?>
    </header>

    <div class="app-wrapper">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center my-4">
                <h1>Sales Records</h1>
                <div class="add-transaction-btn">
                    <a href="add_transaction.php" class="btn btn-success">
                        <i class="fas fa-plus"></i> Add Transaction
                    </a>
                </div>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Product</th>
                        <th scope="col">Transaction Type</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total Price</th>
                        <th scope="col">Payment Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include('config.php');

                    try {
                        $stmt = $conn->prepare("
                            SELECT s.sale_id, s.customer_name, p.product_name, s.transaction_type, s.quantity, p.price, s.payment_status
                            FROM sales s
                            JOIN products p ON s.product_id = p.product_id
                        ");
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $total_price = $row['price'] * $row['quantity'];
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['customer_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['product_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['transaction_type']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['quantity']) . '</td>';
                            echo '<td>' . htmlspecialchars($total_price) . '</td>';
                            echo '<td>' . htmlspecialchars($row['payment_status']) . '</td>';
                            echo '<td>';
                            echo '<div class="btn-group" role="group" aria-label="Actions">';
                            echo '<a href="edit_sale.php?id=' . htmlspecialchars($row['sale_id']) . '" class="btn btn-primary btn-sm m-1"><i class="fas fa-edit"></i> Edit</a>';
                            echo '<form method="POST" action="delete_sale.php" style="display:inline-block;" onsubmit="return confirm(\'Are you sure you want to delete this sale?\');">';
                            echo '<input type="hidden" name="sale_id" value="' . htmlspecialchars($row['sale_id']) . '">';
                            echo '<button type="submit" class="btn btn-danger btn-sm m-1"><i class="fas fa-trash-alt"></i> Delete</button>';
                            echo '</form>';
                            echo '</div>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } catch (PDOException $e) {
                        echo 'Query failed: ' . $e->getMessage();
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Javascript -->
    <script src="assets/plugins/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html>
