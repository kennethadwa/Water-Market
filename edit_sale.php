<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Sale</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <script>
        function validateForm() {
            var transactionType = document.getElementById('transaction_type').value;
            var paymentStatus = document.getElementById('payment_status').value;
            var quantity = parseInt(document.getElementById('quantity').value);
            var currentQuantity = parseInt(document.getElementById('current_quantity').value);

            if (paymentStatus === 'Refund' && transactionType !== 'Return') {
                alert('Transaction type must be "Return" when payment status is "Refund".');
                return false;
            }

            if (transactionType === 'Return' && paymentStatus === 'Refund') {
                if (quantity > currentQuantity) {
                    alert('Quantity for Return and Refund cannot be greater than the current quantity.');
                    return false;
                }
            }
            return true;
        }
    </script>
</head>
<body class="app">
    <header class="app-header fixed-top">
        <?php @include('navbar.php'); ?>
        <?php @include('sidebar.php'); ?>
    </header>
    <div class="app-wrapper">
        <div class="container">
            <h1 class="my-4 text-center">Edit Sale Record</h1>
            <?php
            include('config.php');
            if (isset($_GET['id'])) {
                $sale_id = $_GET['id'];
                try {
                    $stmt = $conn->prepare("SELECT sale_id, customer_name, product_id, transaction_type, quantity, payment_status FROM sales WHERE sale_id = :sale_id");
                    $stmt->bindParam(':sale_id', $sale_id, PDO::PARAM_INT);
                    $stmt->execute();
                    $sale = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($sale) {
                        $customer_name = htmlspecialchars($sale['customer_name']);
                        $product_id = $sale['product_id'];
                        $transaction_type = htmlspecialchars($sale['transaction_type']);
                        $quantity = htmlspecialchars($sale['quantity']);
                        $payment_status = htmlspecialchars($sale['payment_status']);
                    } else {
                        echo '<p>Sale record not found.</p>';
                        exit;
                    }
                } catch (PDOException $e) {
                    echo 'Query failed: ' . $e->getMessage();
                    exit;
                }
            } else {
                echo '<p>No sale ID provided.</p>';
                exit;
            }
            ?>
            <div class="form-container">
                <form action="save_edit_sale.php" method="post" onsubmit="return validateForm()">
                    <div class="form-group">
                        <label for="customer_name">Customer Name:</label>
                        <input type="text" id="customer_name" name="customer_name" value="<?php echo $customer_name; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="product_id">Product:</label>
                        <select id="product_id" name="product_id" required>
                            <?php
                            try {
                                $productStmt = $conn->prepare("SELECT product_id, product_name FROM products");
                                $productStmt->execute();
                                while ($product = $productStmt->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = ($product['product_id'] == $product_id) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($product['product_id']) . '" ' . $selected . '>' . htmlspecialchars($product['product_name']) . '</option>';
                                }
                            } catch (PDOException $e) {
                                echo 'Query failed: ' . $e->getMessage();
                                exit;
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="transaction_type">Transaction Type:</label>
                        <select id="transaction_type" name="transaction_type" required>
                            <option value="Walk In" <?php echo ($transaction_type == 'Walk In') ? 'selected' : ''; ?>>Walk In</option>
                            <option value="For Delivery" <?php echo ($transaction_type == 'For Delivery') ? 'selected' : ''; ?>>For Delivery</option>
                            <option value="Return" <?php echo ($transaction_type == 'Return') ? 'selected' : ''; ?>>Return</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" value="<?php echo $quantity; ?>" required min="1">
                        <input type="hidden" id="current_quantity" name="current_quantity" value="<?php echo $quantity; ?>">
                    </div>
                    <div class="form-group">
                        <label for="payment_status">Payment Status:</label>
                        <select id="payment_status" name="payment_status" required>
                            <option value="Not Paid" <?php echo ($payment_status == 'Not Paid') ? 'selected' : ''; ?>>Not Paid</option>
                            <option value="Paid" <?php echo ($payment_status == 'Paid') ? 'selected' : ''; ?>>Paid</option>
                            <option value="Refund" <?php echo ($payment_status == 'Refund') ? 'selected' : ''; ?>>Refund</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="sale_id" value="<?php echo $sale_id; ?>">
                        <input type="submit" value="Save Changes">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="assets/plugins/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html>
