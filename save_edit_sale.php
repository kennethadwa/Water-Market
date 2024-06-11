<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sale_id = $_POST['sale_id'];
    $customer_name = $_POST['customer_name'];
    $product_id = $_POST['product_id'];
    $transaction_type = $_POST['transaction_type'];
    $quantity = $_POST['quantity'];
    $payment_status = $_POST['payment_status'];

    try {
        $stmt = $conn->prepare("
            UPDATE sales 
            SET customer_name = :customer_name, 
                product_id = :product_id, 
                transaction_type = :transaction_type, 
                quantity = :quantity, 
                payment_status = :payment_status 
            WHERE sale_id = :sale_id
        ");
        $stmt->bindParam(':customer_name', $customer_name);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':transaction_type', $transaction_type);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':payment_status', $payment_status);
        $stmt->bindParam(':sale_id', $sale_id);
        $stmt->execute();

        header("Location: sales.php");
        exit();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
