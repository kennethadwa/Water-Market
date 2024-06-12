<?php
include('config.php');

try {
    $stmt = $conn->prepare("
        SELECT 
            customer_name,
            MIN(transaction_date) AS first_transaction_date,
            MAX(transaction_date) AS last_transaction_date,
            SUM(CASE WHEN payment_status = 'Paid' THEN 1 ELSE 0 END) AS successful_transactions,
            SUM(CASE WHEN payment_status = 'Not Paid' THEN 1 ELSE 0 END) AS pending_transactions,
            SUM(CASE WHEN payment_status = 'Refund' THEN 1 ELSE 0 END) AS refunds
        FROM 
            sales
        GROUP BY 
            customer_name
    ");
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get top 3 customers by successful transactions
    usort($customers, function($a, $b) {
        return $b['successful_transactions'] - $a['successful_transactions'];
    });

    $top_customers = array_slice($customers, 0, 3);

} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
