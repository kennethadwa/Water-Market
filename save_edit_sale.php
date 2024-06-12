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
       
        $conn->beginTransaction();

       
        $stmt = $conn->prepare("SELECT payment_status FROM sales WHERE sale_id = :sale_id");
        $stmt->bindParam(':sale_id', $sale_id, PDO::PARAM_INT);
        $stmt->execute();
        $currentSale = $stmt->fetch(PDO::FETCH_ASSOC);
        $current_payment_status = $currentSale['payment_status'];

        
        $stmt = $conn->prepare("UPDATE sales SET customer_name = :customer_name, product_id = :product_id, transaction_type = :transaction_type, quantity = :quantity, payment_status = :payment_status WHERE sale_id = :sale_id");
        $stmt->bindParam(':customer_name', $customer_name);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':transaction_type', $transaction_type);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':payment_status', $payment_status);
        $stmt->bindParam(':sale_id', $sale_id, PDO::PARAM_INT);
        $stmt->execute();

        
        if ($current_payment_status == 'Paid' && $payment_status != 'Paid') {
           
            $stmt = $conn->prepare("UPDATE products SET stock = stock + :quantity WHERE product_id = :product_id");
        } elseif ($current_payment_status != 'Paid' && $payment_status == 'Paid') {
          
            $stmt = $conn->prepare("UPDATE products SET stock = stock - :quantity WHERE product_id = :product_id");
        }

        if (isset($stmt)) {
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->execute();
        }

        
        $conn->commit();

    
        header('Location: sales.php');
        exit();
    } catch (Exception $e) {
        
        $conn->rollBack();
        echo 'Failed to update sale record: ' . $e->getMessage();
    }
} else {
    echo 'Invalid request method.';
}
?>
