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
    <?php $redirect = $_GET['redirect'] ?? ''; ?>
    <form method='post' action='processLogin.php' enctype="multipart/form-data" id="loginForm" onsubmit="return validateLoginForm();" novalidate>
  <div class="mb-3" >
    <label for="exampleInputEmail1" class="form-label">Email address</label>
    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name='email'>
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" name='password'>
  </div>
  <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
  
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
<script>
function validateLoginForm() {
    var email = document.getElementById('exampleInputEmail1').value.trim();
    var password = document.getElementById('exampleInputPassword1').value.trim();
    var atPosition = email.indexOf('@');
    var dotPosition = email.lastIndexOf('.');

    if (email === '') {
        alert('Please enter your email address.');
        document.getElementById('exampleInputEmail1').focus();
        return false;
    }

    if (atPosition < 1 || dotPosition < atPosition + 2 || dotPosition === email.length - 1) {
        alert('Please enter a valid email address.');
        document.getElementById('exampleInputEmail1').focus();
        return false;
    }

    if (password === '') {
        alert('Please enter your password.');
        document.getElementById('exampleInputPassword1').focus();
        return false;
    }

    return true;
}
</script>
</body>
</html>
