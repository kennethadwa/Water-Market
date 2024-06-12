<?php

include('config.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $report_id = $_POST['report_id'];
    $payment_method = $_POST['payment_method'];

    try {
    
        $stmt = $conn->prepare("UPDATE sales_report SET payment_method = :payment_method WHERE report_id = :report_id");
        $stmt->bindParam(':payment_method', $payment_method);
        $stmt->bindParam(':report_id', $report_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo 'Payment method updated successfully.';
        } else {
            echo 'Failed to update payment method.';
        }
    } catch (PDOException $e) {
       
        echo 'Error updating payment method: ' . $e->getMessage();
    }
} else {
  
    echo 'Invalid request method.';
}
?>
