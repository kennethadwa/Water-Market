<?php
include('config.php');  


$search = isset($_GET['query']) ? trim($_GET['query']) : '';


if ($search) {
    try {
        $stmt = $conn->prepare('SELECT product_id, image_path, product_name, description, price, stock, created_at FROM products WHERE product_name LIKE :search ORDER BY created_at DESC');
        $stmt->execute(['search' => '%' . $search . '%']);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


        echo json_encode($results);
    } catch (PDOException $e) {

        echo json_encode(['error' => $e->getMessage()]);
    }
} else {

    echo json_encode([]);
}
?>
