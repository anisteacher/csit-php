<?php
require 'db.php';

$property = [
    'id' => '',
    'name' => '',
    'location' => '',
    'price' => '',
    'status' => '',
    'image_path' => null,
];

$isEdit = false;
$error = '';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $conn->prepare("SELECT id, name, location, price, status, image_path FROM property WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Property not found.");
    }

    $property = $result->fetch_assoc();
    $isEdit = true;
}

if (isset($_GET['error'])) {
    $error = trim($_GET['error']);
}

$currentImage = 'assets/property-placeholder.svg';
if (!empty($property['image_path']) && file_exists(__DIR__ . '/' . $property['image_path'])) {
    $currentImage = $property['image_path'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? 'Edit Property' : 'Add Property'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .property-form-card { max-width: 760px; margin: 40px auto; background: #fff; border-radius: 16px; padding: 32px; box-shadow: 0 14px 40px rgba(0, 0, 0, 0.08); }
        .preview-image { width: 100%; max-width: 280px; height: 200px; object-fit: cover; border-radius: 14px; border: 1px solid #dee2e6; }
    </style>
</head>
<body>
<div class="container">
    <div class="property-form-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1"><?php echo $isEdit ? 'Edit Property' : 'Add Property'; ?></h1>
                <p class="text-muted mb-0">Upload a property image and keep listing cards in sync automatically.</p>
            </div>
            <a href="index.php" class="btn btn-outline-secondary">Back to Listings</a>
        </div>

        <?php if ($error !== ''): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" action="save_property.php" enctype="multipart/form-data" id="propertyForm" onsubmit="return validatePropertyForm();" novalidate>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars((string) $property['id']); ?>">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Property Name</label>
                    <input type="text" class="form-control" id="propertyName" name="name" value="<?php echo htmlspecialchars($property['name']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Location</label>
                    <input type="text" class="form-control" id="propertyLocation" name="location" value="<?php echo htmlspecialchars($property['location']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Price</label>
                    <input type="number" class="form-control" id="propertyPrice" name="price" value="<?php echo htmlspecialchars((string) $property['price']); ?>" min="0" step="0.01" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control" id="propertyStatus" name="status" value="<?php echo htmlspecialchars($property['status']); ?>" placeholder="Write a short property description" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Property Image</label>
                    <input type="file" class="form-control" id="propertyImage" name="image" accept="image/jpeg,image/png,image/webp,image/gif">
                    <div class="form-text">Supported formats: JPG, PNG, WEBP, GIF. Maximum size: 5 MB.</div>
                </div>

                <div class="col-12">
                    <div class="d-flex flex-column flex-md-row gap-3 align-items-start align-items-md-center">
                        <img src="<?php echo htmlspecialchars($currentImage); ?>" alt="Property preview" class="preview-image">
                        <div>
                            <p class="mb-2 fw-semibold">Current preview</p>
                            <p class="text-muted mb-3">Upload a new image to replace the current one. If you leave the file input empty, the current image stays as it is.</p>
                            <?php if (!empty($property['image_path'])): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="removeImage">
                                    <label class="form-check-label" for="removeImage">
                                        Remove current image
                                    </label>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-dark"><?php echo $isEdit ? 'Update Property' : 'Create Property'; ?></button>
                <?php if ($isEdit): ?>
                    <a href="property_details.php?id=<?php echo (int) $property['id']; ?>" class="btn btn-outline-secondary">View Property</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>
<script>
function validatePropertyForm() {
    var name = document.getElementById('propertyName').value.trim();
    var location = document.getElementById('propertyLocation').value.trim();
    var price = document.getElementById('propertyPrice').value.trim();
    var status = document.getElementById('propertyStatus').value.trim();
    var imageInput = document.getElementById('propertyImage');

    if (name === '') {
        alert('Please enter the property name.');
        document.getElementById('propertyName').focus();
        return false;
    }

    if (location === '') {
        alert('Please enter the property location.');
        document.getElementById('propertyLocation').focus();
        return false;
    }

    if (price === '') {
        alert('Please enter the property price.');
        document.getElementById('propertyPrice').focus();
        return false;
    }

    if (isNaN(price) || Number(price) <= 0) {
        alert('Please enter a valid price greater than 0.');
        document.getElementById('propertyPrice').focus();
        return false;
    }

    if (status === '') {
        alert('Please enter the property description.');
        document.getElementById('propertyStatus').focus();
        return false;
    }

    if (imageInput.value !== '') {
        var file = imageInput.files[0];
        var fileName = imageInput.value.toLowerCase();
        var allowedExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp'];
        var validImage = false;

        for (var i = 0; i < allowedExtensions.length; i++) {
            if (fileName.endsWith(allowedExtensions[i])) {
                validImage = true;
                break;
            }
        }

        if (!validImage) {
            alert('Please choose a JPG, JPEG, PNG, GIF, or WEBP image.');
            imageInput.focus();
            return false;
        }

        if (file && file.size > 5 * 1024 * 1024) {
            alert('Image size must be less than 5 MB.');
            imageInput.focus();
            return false;
        }
    }

    return true;
}
</script>
</body>
</html>
