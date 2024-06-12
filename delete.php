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
<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];

    try {
        // Disable foreign key checks temporarily
        $conn->exec("SET foreign_key_checks = 0");
        
        // Delete associated sales records first
        $deleteSalesStmt = $conn->prepare("DELETE FROM sales WHERE product_id = :product_id");
        $deleteSalesStmt->bindParam(':product_id', $product_id);
        $deleteSalesStmt->execute();

        // Now delete the product itself
        $deleteProductStmt = $conn->prepare("DELETE FROM products WHERE product_id = :product_id");
        $deleteProductStmt->bindParam(':product_id', $product_id);
        $deleteProductStmt->execute();

        // Re-enable foreign key checks
        $conn->exec("SET foreign_key_checks = 1");

        header("Location: inventory.php");
        exit();
    } catch (PDOException $e) {
        echo 'Deletion failed: ' . $e->getMessage();
    }
}
?>
