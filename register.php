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
    <form method='post' action='signup.php' enctype="multipart/form-data" id="registerForm" onsubmit="return validateRegisterForm();" novalidate>
        <div class="mb-3">
    <label  class="form-label">Username</label>
    <input type="text" class="form-control" id="registerUsername" name='username'>
  </div>

  <div class="mb-3">
    <label  class="form-label">Name</label>
    <input type="text" class="form-control" id="registerName" name='name' >
  </div>

  <div class="mb-3">
    <label  class="form-label">Phone</label>
    <input type="number" class="form-control" id="registerPhone" name='phone' >
  </div>


  <div class="mb-3">
    <label  class="form-label">Email address</label>
    <input type="email" class="form-control" id="registerEmail" name='email' >
  </div>

  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" name='password'>
  </div>

  <button type="submit" class="btn btn-primary">Submit</button>
</form>
<script>
function validateRegisterForm() {
    var username = document.getElementById('registerUsername').value.trim();
    var name = document.getElementById('registerName').value.trim();
    var phone = document.getElementById('registerPhone').value.trim();
    var email = document.getElementById('registerEmail').value.trim();
    var password = document.getElementById('exampleInputPassword1').value.trim();
    var atPosition = email.indexOf('@');
    var dotPosition = email.lastIndexOf('.');

    if (username === '') {
        alert('Please enter a username.');
        document.getElementById('registerUsername').focus();
        return false;
    }

    if (username.length < 3) {
        alert('Username must be at least 3 characters.');
        document.getElementById('registerUsername').focus();
        return false;
    }

    if (name === '') {
        alert('Please enter your name.');
        document.getElementById('registerName').focus();
        return false;
    }

    if (phone === '') {
        alert('Please enter your phone number.');
        document.getElementById('registerPhone').focus();
        return false;
    }

    if (isNaN(phone) || phone.length < 7 || phone.length > 15) {
        alert('Please enter a valid phone number.');
        document.getElementById('registerPhone').focus();
        return false;
    }

    if (email === '') {
        alert('Please enter your email address.');
        document.getElementById('registerEmail').focus();
        return false;
    }

    if (atPosition < 1 || dotPosition < atPosition + 2 || dotPosition === email.length - 1) {
        alert('Please enter a valid email address.');
        document.getElementById('registerEmail').focus();
        return false;
    }

    if (password === '') {
        alert('Please enter a password.');
        document.getElementById('exampleInputPassword1').focus();
        return false;
    }

    if (password.length < 6) {
        alert('Password must be at least 6 characters.');
        document.getElementById('exampleInputPassword1').focus();
        return false;
    }

    return true;
}
</script>
</body>
</html>
