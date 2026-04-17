
<?php
// session_start();
// if(empty($_SESSION['username'])){
//     header('Location: login.php'); 
//     exit();
// }

require 'db.php';

$sql = "SELECT id, name, location, price, status, image_path FROM property ORDER BY id DESC";
$properties = [];
if($result = $conn->query($sql)){
 if($result->num_rows > 0){
    $properties = $result->fetch_all(MYSQLI_ASSOC);
 }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Maintain aspect ratio for carousel images so they don't look stretched */
        .carousel-item img {
            height: 500px;
            object-fit: cover;
        }
        /* Ensure all card images are the same height */
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        /* Add spacing between sections */
        section {
            padding: 40px 0;
        }
        #nav { padding: 0; }
    </style>
</head>
<body>

<section id="nav">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">REAL ESTATE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Properties</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
                </ul>
                <form class="d-flex" role="search" id="searchForm" onsubmit="return validateSearchForm();" novalidate>
                    <input class="form-control me-2" id="searchInput" type="search" placeholder="Search location...">
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
</section>

<section id='hero' class="p-0">
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://plus.unsplash.com/premium_photo-1663089688180-444ff0066e5d?q=80&w=1740&auto=format&fit=crop" class="d-block w-100" alt="Luxury Home">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                    <h5>Modern Architecture</h5>
                    <p>Find the home of your dreams in our premium listings.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=1546&auto=format&fit=crop" class="d-block w-100" alt="Office Space">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                    <h5>Commercial Spaces</h5>
                    <p>High-end office locations for your growing business.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

<section id='listing'>
    <div class="container">
        <div class="mb-4">
            <h2 class="mb-0 text-center text-md-start">Featured Properties</h2>
        </div>
        <div class="row g-4"> 

                <?php if(isset($properties)): ?>
    <?php foreach($properties as $property): ?>
    <?php
    $imagePath = 'assets/property-placeholder.svg';
    if (!empty($property['image_path']) && file_exists(__DIR__ . '/' . $property['image_path'])) {
        $imagePath = $property['image_path'];
    }
    ?>
    <div class="col-sm-6 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <img src="<?php echo htmlspecialchars($imagePath); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($property['name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($property['name']); ?></h5>
                        <h5 class="card-title">$<?php echo number_format((float) $property['price'], 2); ?></h5>
                        <p class="card-text text-muted small"><?php echo htmlspecialchars($property['location']); ?></p>
                        <div class="d-grid">
                            <a href="property_details.php?id=<?php echo (int) $property['id']; ?>" class="btn btn-primary w-100">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
    <?php endforeach; ?>
    <?php endif; ?>
    <?php if(empty($properties )): ?>
        <div class="col-12">
            <p class="text-center text-muted">No properties found.</p>
        </div>
    <?php endif; ?>

            

        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function validateSearchForm() {
    var search = document.getElementById('searchInput').value.trim();

    if (search === '') {
        alert('Please enter a location to search.');
        document.getElementById('searchInput').focus();
        return false;
    }

    if (search.length < 2) {
        alert('Please enter at least 2 characters to search.');
        document.getElementById('searchInput').focus();
        return false;
    }

    return true;
}
</script>
</body>
</html>
