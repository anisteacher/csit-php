<?php 
session_start();
if(empty($_SESSION['username'])){
    header('Location: login.php'); 
    exit();
}

require 'db.php';
$sql = "SELECT * FROM users";
if($result = $conn->query($sql)){
 if($result->num_rows > 0){
    $users = $result->fetch_all(MYSQLI_ASSOC);
 }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</head>
<body>

    <p>Welcome, <?php echo $_SESSION['name']; ?>!</p>
    <a href="register.php" class="btn btn-primary">Register New User</a>
    
    <table class="table">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Username</th>
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th scope="col">Phone</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php if(isset($users)): ?>
    <?php foreach($users as $user): ?>
    <tr>
      <th scope="row"><?php echo $user['id']; ?></th>
      <td><?php echo $user['username']; ?></td>
      <td><?php echo $user['name']; ?></td>
      <td><?php echo $user['email']; ?></td>
      <td><?php echo $user['phone']; ?></td>
      <td>
        <a href="edit.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
        <a href="delete.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
    <?php if(empty($users)): ?>
        <tr>
            <td colspan="6">No users found.</td>
        </tr>
    <?php endif; ?>
  </tbody>
</table>

</body>
</html>