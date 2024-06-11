<?php
include('config.php');

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    try {
        $stmt = $conn->prepare('DELETE FROM products WHERE product_id = :product_id');
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        
        header('Location: inventory.php');
        exit;
    } catch (PDOException $e) {
        echo 'Deletion failed: ' . $e->getMessage();
    }
} else {
    header('Location: inventory.php');
    exit;
}
?>
