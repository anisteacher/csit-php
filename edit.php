<?php
 require 'db.php';

 if(isset($_REQUEST['id'])){
    $id = $_REQUEST['id'];
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit(); 
    }
 }

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<body>
    <form method='get' action='update.php' enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">
        <div class="mb-3">
    <label  class="form-label">Username</label>
    <input type="text" class="form-control"  name='username' value="<?php echo isset($row['username']) ? $row['username'] : ''; ?>" >
  </div>

  <div class="mb-3">
    <label  class="form-label">Name</label>
    <input type="text" class="form-control"  name='name' value="<?php echo isset($row['name']) ? $row['name'] : ''; ?>" >
  </div>

  <div class="mb-3">
    <label  class="form-label">Phone</label>
    <input type="number" class="form-control"  name='phone' value="<?php echo isset($row['phone']) ? $row['phone'] : ''; ?>" >
  </div>


  <div class="mb-3">
    <label  class="form-label">Email address</label>
    <input type="email" class="form-control"  name='email' value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>" >
  </div>


  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</body>
</html>