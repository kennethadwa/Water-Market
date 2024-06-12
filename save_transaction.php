<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST['customer_name'];
    $product_id = $_POST['product_id'];
    $transaction_type = $_POST['transaction_type'];
    $quantity = $_POST['quantity'];
    $payment_status = $_POST['payment_status'];

    try {
       
        $checkStockStmt = $conn->prepare("SELECT stock FROM products WHERE product_id = :product_id");
        $checkStockStmt->bindParam(':product_id', $product_id);
        $checkStockStmt->execute();
        $product = $checkStockStmt->fetch(PDO::FETCH_ASSOC);

        if (!$product || $product['stock'] < $quantity) {
            echo '<script>alert("Transaction Failed: The product selected is currently unavailable or the quantity exceeds the available stock."); window.history.back();</script>';
            exit();
        }

       
        $insertStmt = $conn->prepare("
            INSERT INTO sales (customer_name, product_id, transaction_type, quantity, payment_status)
            VALUES (:customer_name, :product_id, :transaction_type, :quantity, :payment_status)
        ");
        $insertStmt->bindParam(':customer_name', $customer_name);
        $insertStmt->bindParam(':product_id', $product_id);
        $insertStmt->bindParam(':transaction_type', $transaction_type);
        $insertStmt->bindParam(':quantity', $quantity);
        $insertStmt->bindParam(':payment_status', $payment_status);
        $insertStmt->execute();

        
        $sale_id = $conn->lastInsertId();

        
        $insertReportStmt = $conn->prepare("
            INSERT INTO sales_report (sale_id, product_id, payment_method, created_at, updated_at)
            VALUES (:sale_id, :product_id, :payment_method, NOW(), NOW())
        ");
        $payment_method = $payment_status;
        $insertReportStmt->bindParam(':sale_id', $sale_id);
        $insertReportStmt->bindParam(':product_id', $product_id);
        $insertReportStmt->bindParam(':payment_method', $payment_method);
        $insertReportStmt->execute();

        
        if ($payment_status == 'Paid') {
            $updateStockStmt = $conn->prepare("
                UPDATE products
                SET stock = stock - :quantity
                WHERE product_id = :product_id
            ");
            $updateStockStmt->bindParam(':quantity', $quantity);
            $updateStockStmt->bindParam(':product_id', $product_id);
            $updateStockStmt->execute();
        }

        header("Location: sales.php");
        exit();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
