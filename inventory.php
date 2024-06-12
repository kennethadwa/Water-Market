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
            border: 1px solid #ddd;
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
                <h1>Water Market Inventory</h1>
                <div class="add-transaction-btn">
                    <a href="add_product.php" class="btn btn-success">
                        <i class="fas fa-plus"></i> Add Product
                    </a>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>
                        Show 
                        <select id="entries" class="form-select form-select-sm" aria-controls="inventoryTable">
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

            <table id="inventoryTable" class="table table-striped">
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
                        $stmt = $conn->prepare('SELECT product_id, image_path, product_name, description, price, stock, created_at FROM products ORDER BY created_at DESC');
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr>';
                            echo '<td><img src="' . htmlspecialchars($row['image_path']) . '" alt="Product Image"></td>';
                            echo '<td>' . htmlspecialchars($row['product_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                            echo '<td>₱' . htmlspecialchars($row['price']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['stock']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
                            echo '<td>';
                            echo '<div class="btn-group" role="group" aria-label="Actions">';
                            echo '<a href="edit_product.php?id=' . htmlspecialchars($row['product_id']) . '" class="btn btn-primary btn-sm m-1"><i class="fas fa-edit"></i> Edit</a>';
                            echo '<form method="POST" action="delete.php" style="display:inline-block;" onsubmit="return confirm(\'Are you sure you want to delete this product?\');">';
                            echo '<input type="hidden" name="product_id" value="' . htmlspecialchars($row['product_id']) . '">';
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

            <div class="d-flex justify-content-center mt-3">
                <nav aria-label="Page navigation">
                    <ul id="pagination" class="pagination"></ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Javascript -->
    <script src="assets/plugins/popper.min.js"></script>
    <script src="https://kit.fontawesome.com/b931534883.js" crossorigin="anonymous"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#inventoryTable').DataTable({
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
    </script>
</body>
</html>
