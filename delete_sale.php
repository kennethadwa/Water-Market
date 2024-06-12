<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['sale_id'])) {
        $sale_id = $_POST['sale_id'];

        try {
        
            $conn->beginTransaction();

     
            $stmt = $conn->prepare("DELETE FROM sales WHERE sale_id = :sale_id");
            $stmt->bindParam(':sale_id', $sale_id, PDO::PARAM_INT);
            $stmt->execute();

          
            $conn->commit();

            header('Location: sales.php');
            exit;
        } catch (PDOException $e) {
            
            $conn->rollBack();
            echo 'Error deleting record: ' . $e->getMessage();
        }
    } else {
        echo 'Sale ID not provided.';
    }
} else {
    echo 'Invalid request method.';
}
?>
