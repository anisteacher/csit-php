<?php
require 'db.php';

// 1. Use a ternary operator for a cleaner ID check
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // 2. Use Prepared Statements to prevent SQL Injection
    $stmt = $conn->prepare("SELECT id, name, location, price, status, image_path FROM property WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("Property not found.");
    }
} else {
    die("Invalid ID.");
}

$imagePath = 'assets/property-placeholder.svg';
if (!empty($row['image_path']) && file_exists(__DIR__ . '/' . $row['image_path'])) {
    $imagePath = $row['image_path'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row['name']); ?> | Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; color: #333; }
        .details-container { max-width: 700px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .price-tag { font-size: 1.5rem; font-weight: bold; color: #198754; }
        .label { font-weight: 600; text-transform: uppercase; font-size: 0.8rem; color: #6c757d; display: block; margin-top: 15px; }
    </style>
</head>
<body>

<div class="container">
    <div class="details-container">
        <a href="index.php" class="btn btn-link btn-sm p-0 mb-3 text-decoration-none">← Back to Listings</a>

        <img src="<?php echo htmlspecialchars($imagePath); ?>" class="img-fluid rounded mb-4 w-100" alt="<?php echo htmlspecialchars($row['name']); ?>" style="max-height: 420px; object-fit: cover;">
        
        <h1 class="h3 mb-1"><?php echo htmlspecialchars($row['name']); ?></h1>
        <p class="text-muted mb-4">Property ID: #<?php echo $row['id']; ?></p>

        <span class="label">Price</span>
        <div class="price-tag mb-3">$<?php echo number_format($row['price'], 2); ?></div>

        <span class="label">Description</span>
        <p class="lead" style="font-size: 1rem;"><?php echo nl2br(htmlspecialchars($row['status'])); ?></p>
        
        <hr>
        
        <div class="d-grid mt-4">
            <a href='order.php?id=<?php echo $row['id']; ?>' class="btn btn-dark btn-lg">Order Now</a>
        </div>
    </div>
</div>

</body>
</html>
