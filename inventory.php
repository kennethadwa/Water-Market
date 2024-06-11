<!DOCTYPE html>
<html lang="en">
<head>
    <title>Inventory</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        td img {
            max-width: 80px;
            height: auto;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
        td, th {
            border-spacing: 2px;
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
                <h1>Water Market Inventory</h1>
                <a href="add_product.php" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add Product
                </a>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Image</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Price</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Added At</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include('config.php');

                    try {
                        // Deduct stock based on paid sales
                        $updateStockStmt = $conn->prepare("
                            UPDATE products p 
                            JOIN (
                                SELECT product_id, SUM(quantity) AS total_quantity 
                                FROM sales 
                                WHERE payment_status = 'Paid' 
                                GROUP BY product_id
                            ) s ON p.product_id = s.product_id
                            SET p.stock = p.stock - s.total_quantity
                            WHERE s.total_quantity > 0
                        ");
                        $updateStockStmt->execute();

                        // Fetch updated product information
                        $stmt = $conn->query('SELECT product_id, image_path, product_name, description, price, stock, created_at FROM products ORDER BY created_at DESC');

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr>';
                            echo '<td><img src="' . htmlspecialchars($row['image_path']) . '" alt="Product Image"></td>';
                            echo '<td>' . htmlspecialchars($row['product_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                            echo '<td>₱' . htmlspecialchars($row['price']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['stock']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
                            echo '<td>';
                            echo '<div class="btn-group">';
                            echo '<a href="edit_product.php?id=' . htmlspecialchars($row['product_id']) . '" class="btn btn-primary btn-sm m-1"><i class="fas fa-edit"></i></a>';
                            echo '<form method="POST" action="delete.php" style="display:inline-block;" onsubmit="return confirm(\'Are you sure you want to delete this product?\');">';
                            echo '<input type="hidden" name="product_id" value="' . htmlspecialchars($row['product_id']) . '">';
                            echo '<button type="submit" class="btn btn-danger btn-sm m-1"><i class="fas fa-trash-alt"></i></button>';
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
    <script src="https://kit.fontawesome.com/b931534883.js" crossorigin="anonymous"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html>
