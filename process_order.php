<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

$property_id = isset($_POST['property_id']) ? (int) $_POST['property_id'] : 0;

if ($property_id <= 0) {
    $_SESSION['order_flash'] = [
        'type' => 'danger',
        'message' => 'Invalid property selected.'
    ];
    header('Location: index.php');
    exit();
}

if (empty($_SESSION['user_id'])) {
    header('Location: login.php?redirect=' . urlencode('order.php?id=' . $property_id));
    exit();
}

$user_id = (int) $_SESSION['user_id'];

$propertyStmt = $conn->prepare("SELECT id FROM property WHERE id = ?");
$propertyStmt->bind_param("i", $property_id);
$propertyStmt->execute();
$propertyResult = $propertyStmt->get_result();

if ($propertyResult->num_rows === 0) {
    $_SESSION['order_flash'] = [
        'type' => 'danger',
        'message' => 'Property not found.'
    ];
    header('Location: index.php');
    exit();
}

$existingOrderStmt = $conn->prepare("SELECT id FROM orders WHERE user_id = ? AND property_id = ? LIMIT 1");
$existingOrderStmt->bind_param("ii", $user_id, $property_id);
$existingOrderStmt->execute();
$existingOrderResult = $existingOrderStmt->get_result();

if ($existingOrderResult->num_rows > 0) {
    $_SESSION['order_flash'] = [
        'type' => 'warning',
        'message' => 'You already placed an order for this property.'
    ];
    header('Location: order.php?id=' . $property_id);
    exit();
}

$status = 'pending';
$insertStmt = $conn->prepare("INSERT INTO orders (user_id, property_id, status) VALUES (?, ?, ?)");
$insertStmt->bind_param("iis", $user_id, $property_id, $status);

if ($insertStmt->execute()) {
    $_SESSION['order_flash'] = [
        'type' => 'success',
        'message' => 'Your order has been placed successfully.'
    ];
} else {
    $_SESSION['order_flash'] = [
        'type' => 'danger',
        'message' => 'Unable to place your order right now. Please try again.'
    ];
}

header('Location: order.php?id=' . $property_id);
exit();
?>
