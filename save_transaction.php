<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST['customer_name'];
    $product_id = $_POST['product_id'];
    $transaction_type = $_POST['transaction_type'];
    $quantity = $_POST['quantity'];
    $payment_status = $_POST['payment_status'];

    try {
        $stmt = $conn->prepare("INSERT INTO sales (customer_name, product_id, transaction_type, quantity, payment_status) VALUES (:customer_name, :product_id, :transaction_type, :quantity, :payment_status)");
        $stmt->bindParam(':customer_name', $customer_name);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':transaction_type', $transaction_type);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':payment_status', $payment_status);

        $stmt->execute();

        header("Location: sales.php");
        exit();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
