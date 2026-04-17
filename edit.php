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
    <form method='get' action='update.php' enctype="multipart/form-data" id="editUserForm" onsubmit="return validateEditUserForm();" novalidate>
      <input type="hidden" name="id" value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">
        <div class="mb-3">
    <label  class="form-label">Username</label>
    <input type="text" class="form-control" id="editUsername" name='username' value="<?php echo isset($row['username']) ? $row['username'] : ''; ?>" >
  </div>

  <div class="mb-3">
    <label  class="form-label">Name</label>
    <input type="text" class="form-control" id="editName" name='name' value="<?php echo isset($row['name']) ? $row['name'] : ''; ?>" >
  </div>

  <div class="mb-3">
    <label  class="form-label">Phone</label>
    <input type="number" class="form-control" id="editPhone" name='phone' value="<?php echo isset($row['phone']) ? $row['phone'] : ''; ?>" >
  </div>


  <div class="mb-3">
    <label  class="form-label">Email address</label>
    <input type="email" class="form-control" id="editEmail" name='email' value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>" >
  </div>


  <button type="submit" class="btn btn-primary">Submit</button>
</form>
<script>
function validateEditUserForm() {
    var username = document.getElementById('editUsername').value.trim();
    var name = document.getElementById('editName').value.trim();
    var phone = document.getElementById('editPhone').value.trim();
    var email = document.getElementById('editEmail').value.trim();
    var atPosition = email.indexOf('@');
    var dotPosition = email.lastIndexOf('.');

    if (username === '') {
        alert('Please enter a username.');
        document.getElementById('editUsername').focus();
        return false;
    }

    if (username.length < 3) {
        alert('Username must be at least 3 characters.');
        document.getElementById('editUsername').focus();
        return false;
    }

    if (name === '') {
        alert('Please enter a name.');
        document.getElementById('editName').focus();
        return false;
    }

    if (phone === '') {
        alert('Please enter a phone number.');
        document.getElementById('editPhone').focus();
        return false;
    }

    if (isNaN(phone) || phone.length < 7 || phone.length > 15) {
        alert('Please enter a valid phone number.');
        document.getElementById('editPhone').focus();
        return false;
    }

    if (email === '') {
        alert('Please enter an email address.');
        document.getElementById('editEmail').focus();
        return false;
    }

    if (atPosition < 1 || dotPosition < atPosition + 2 || dotPosition === email.length - 1) {
        alert('Please enter a valid email address.');
        document.getElementById('editEmail').focus();
        return false;
    }

    return true;
}
</script>
</body>
</html>
