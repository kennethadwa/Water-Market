<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    try {
  
        $stmt = $conn->prepare('UPDATE users SET name = :name, email = :email, role = :role WHERE user_id = :user_id');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

     
        header("Location: account.php?update=success");
        exit();
    } catch (PDOException $e) {
        
        echo 'Error: ' . $e->getMessage();
    }
} else {
    header("Location: account.php");
    exit();
}
?>
