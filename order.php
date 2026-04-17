<?php
session_start();
require 'db.php';

$property_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($property_id <= 0) {
    die("Invalid property ID.");
}

if (empty($_SESSION['user_id'])) {
    header('Location: login.php?redirect=' . urlencode('order.php?id=' . $property_id));
    exit();
}

$user_id = (int) $_SESSION['user_id'];
$user = null;
$property = null;
$existingOrder = null;
$flash = $_SESSION['order_flash'] ?? null;

unset($_SESSION['order_flash']);

$userStmt = $conn->prepare("SELECT id, username, email, name FROM users WHERE id = ?");
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$userResult = $userStmt->get_result();

if ($userResult->num_rows > 0) {
    $user = $userResult->fetch_assoc();
} else {
    session_unset();
    session_destroy();
    header('Location: login.php?redirect=' . urlencode('order.php?id=' . $property_id));
    exit();
}

$propertyStmt = $conn->prepare("SELECT id, name, location, price, status, image_path FROM property WHERE id = ?");
$propertyStmt->bind_param("i", $property_id);
$propertyStmt->execute();
$propertyResult = $propertyStmt->get_result();

if ($propertyResult->num_rows > 0) {
    $property = $propertyResult->fetch_assoc();
} else {
    die("Property not found.");
}

$orderStmt = $conn->prepare("SELECT id, status, created_at FROM orders WHERE user_id = ? AND property_id = ? ORDER BY id DESC LIMIT 1");
$orderStmt->bind_param("ii", $user_id, $property_id);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();

if ($orderResult->num_rows > 0) {
    $existingOrder = $orderResult->fetch_assoc();
}

$imagePath = 'assets/property-placeholder.svg';
if (!empty($property['image_path']) && file_exists(__DIR__ . '/' . $property['image_path'])) {
    $imagePath = $property['image_path'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Order | Real Estate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; color: #444; font-family: 'Inter', sans-serif; }
        .order-card { max-width: 500px; margin: 80px auto; background: white; padding: 40px; border-radius: 12px; border: 1px solid #e0e0e0; }
        .user-badge { background: #e9ecef; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; color: #495057; }
    </style>
</head>
<body>

<div class="container">
    <div class="order-card text-center">
        <div class="mb-3">
            <span class="user-badge">Logged in as <strong><?php echo htmlspecialchars($user['username']); ?></strong></span>
        </div>

        <?php if ($flash): ?>
            <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?>" role="alert">
                <?php echo htmlspecialchars($flash['message']); ?>
            </div>
        <?php endif; ?>

        <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($property['name']); ?>" class="img-fluid rounded mb-4" style="max-height: 240px; width: 100%; object-fit: cover;">

        <h1 class="h4 fw-bold mb-2"><?php echo htmlspecialchars($property['name']); ?></h1>
        <p class="text-muted mb-1"><?php echo htmlspecialchars($property['location']); ?></p>
        <p class="fs-5 fw-semibold mb-4">$<?php echo number_format((float) $property['price'], 2); ?></p>
        
        <?php if ($existingOrder): ?>
            <p class="text-muted mb-4">
                You already placed an order for this property.
                Current status: <strong><?php echo htmlspecialchars($existingOrder['status']); ?></strong>.
            </p>
        <?php else: ?>
            <p class="text-muted mb-4">
                You are about to place an order for this property. Please click the button below to confirm your interest.
            </p>
        <?php endif; ?>
        
        <div class="d-grid gap-2">
            <form action="process_order.php" method="POST" id="orderForm" onsubmit="return validateOrderForm();">
                <input type="hidden" name="property_id" id="orderPropertyId" value="<?php echo $property_id; ?>">
                <button type="submit" class="btn btn-dark btn-lg w-100" <?php echo $existingOrder ? 'disabled' : ''; ?>>
                    <?php echo $existingOrder ? 'Order Already Placed' : 'Confirm Order'; ?>
                </button>
            </form>
            <a href="property_details.php?id=<?php echo $property_id; ?>" class="btn btn-link btn-sm text-secondary text-decoration-none">Cancel and go back</a>
        </div>
    </div>
</div>

<script>
function validateOrderForm() {
    var propertyId = document.getElementById('orderPropertyId').value;

    if (propertyId === '' || Number(propertyId) <= 0) {
        alert('Invalid property selected.');
        return false;
    }

    if (!confirm('Are you sure you want to confirm this order?')) {
        return false;
    }

    return true;
}
</script>
</body>
</html>
