<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$name = trim($_POST['name'] ?? '');
$location = trim($_POST['location'] ?? '');
$price = trim($_POST['price'] ?? '');
$status = trim($_POST['status'] ?? '');
$removeImage = isset($_POST['remove_image']) && $_POST['remove_image'] === '1';

if ($name === '' || $location === '' || $price === '' || $status === '') {
    $message = urlencode('Please fill in all required fields.');
    $target = $id > 0 ? 'property_form.php?id=' . $id . '&error=' . $message : 'property_form.php?error=' . $message;
    header('Location: ' . $target);
    exit();
}

$existingProperty = null;

if ($id > 0) {
    $stmt = $conn->prepare("SELECT id, image_path FROM property WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Property not found.");
    }

    $existingProperty = $result->fetch_assoc();
}

$imagePath = $existingProperty['image_path'] ?? null;
$uploadError = $_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE;

if ($uploadError !== UPLOAD_ERR_NO_FILE) {
    if ($uploadError !== UPLOAD_ERR_OK) {
        $message = urlencode('Image upload failed. Please try again.');
        $target = $id > 0 ? 'property_form.php?id=' . $id . '&error=' . $message : 'property_form.php?error=' . $message;
        header('Location: ' . $target);
        exit();
    }

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $allowedExtensions, true) || !getimagesize($_FILES['image']['tmp_name'])) {
        $message = urlencode('Please upload a valid JPG, JPEG, PNG, GIF, or WEBP image.');
        $target = $id > 0 ? 'property_form.php?id=' . $id . '&error=' . $message : 'property_form.php?error=' . $message;
        header('Location: ' . $target);
        exit();
    }

    if (($_FILES['image']['size'] ?? 0) > 5 * 1024 * 1024) {
        $message = urlencode('Image must be smaller than 5 MB.');
        $target = $id > 0 ? 'property_form.php?id=' . $id . '&error=' . $message : 'property_form.php?error=' . $message;
        header('Location: ' . $target);
        exit();
    }

    $uploadFolder = __DIR__ . '/uploads/properties';
    if (!is_dir($uploadFolder)) {
        mkdir($uploadFolder, 0775, true);
    }

    $newFileName = 'property_' . time() . '_' . mt_rand(1000, 9999) . '.' . $extension;
    $destination = $uploadFolder . '/' . $newFileName;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
        $message = urlencode('Unable to save uploaded image.');
        $target = $id > 0 ? 'property_form.php?id=' . $id . '&error=' . $message : 'property_form.php?error=' . $message;
        header('Location: ' . $target);
        exit();
    }

    if (!empty($imagePath) && file_exists(__DIR__ . '/' . $imagePath)) {
        unlink(__DIR__ . '/' . $imagePath);
    }

    $imagePath = 'uploads/properties/' . $newFileName;
} elseif ($removeImage) {
    if (!empty($imagePath) && file_exists(__DIR__ . '/' . $imagePath)) {
        unlink(__DIR__ . '/' . $imagePath);
    }
    $imagePath = null;
}

if ($id > 0) {
    $stmt = $conn->prepare("UPDATE property SET name = ?, location = ?, price = ?, status = ?, image_path = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $name, $location, $price, $status, $imagePath, $id);
    $stmt->execute();

    header('Location: property_details.php?id=' . $id);
    exit();
}

$stmt = $conn->prepare("INSERT INTO property (name, location, price, status, image_path) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $location, $price, $status, $imagePath);
$stmt->execute();

$newId = $conn->insert_id;
header('Location: property_details.php?id=' . $newId);
exit();
?>
