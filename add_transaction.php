<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Transaction</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal - Bootstrap 5 Admin Dashboard Template For Developers">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">    
    <link rel="shortcut icon" href="favicon.ico"> 
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #218838;
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
            <h1 class="my-4 text-center">Add Transaction</h1>
            <div class="form-container">
                <form action="save_transaction.php" method="POST">
                    <div class="form-group">
                        <label for="customer_name">Customer Name:</label>
                        <input type="text" id="customer_name" name="customer_name" placeholder="Enter customer name" required>
                    </div>
                    <div class="form-group">
                        <label for="product_id">Product:</label>
                        <select id="product_id" name="product_id" required>
                            <?php
                            include('config.php');
                            try {
                                $stmt = $conn->prepare("SELECT product_id, product_name, stock FROM products WHERE stock > 0");
                                $stmt->execute();
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="' . htmlspecialchars($row['product_id']) . '">' . htmlspecialchars($row['product_name']) . ' (' . htmlspecialchars($row['stock']) . ' in stock)</option>';
                                }
                            } catch (PDOException $e) {
                                echo 'Error fetching products: ' . $e->getMessage();
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="transaction_type">Transaction Type:</label>
                        <select id="transaction_type" name="transaction_type" required>
                            <option value="Walk In">Walk In</option>
                            <option value="Bulk Order">Bulk Order</option>
                            <option value="Phone Order">Phone Order</option>
                            <option value="For Delivery">For Delivery</option>
                        </select>
                    </div>    
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" placeholder="Enter quantity" required min="1">
                    </div>
                    <div class="form-group">
                        <label for="payment_status">Payment Status:</label>
                        <select id="payment_status" name="payment_status" required>
                            <option value="Not Paid">Not Paid</option>
                            <option value="Paid">Paid</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Save Transaction">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Javascript -->          
    <script src="assets/plugins/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>  
    <script src="assets/js/app.js"></script> 
</body>
</html>
