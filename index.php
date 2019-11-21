<?php
    include_once 'includes/db_connect.php';
    include_once 'includes/functions.php';
sec_session_start();
    if (login_check($mysqli) == true) 
    {
    $logged = 'in';
    } 
    else 
    {
    $logged = 'out';
    }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Calendar Project | Log In</title>
    <link rel="stylesheet" href="css/css.css" type="text/css">
    <script type="text/JavaScript" src="js/sha512.js"></script>
    <script type="text/JavaScript" src="js/forms.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
  </head>
  <body style=" 
  margin: 0; 
  padding: 0; 
  font-family: sans-serif; 
  background: url(css/90977.jpg);
  background-size: cover;">
    <?php
    if (isset($_GET['error'])) {
    echo "<script>
Swal.fire({
  title: 'Error to Login!',
  text: 'Email or Password are not correct',
  type: 'error',
  confirmButtonText: 'OK'
})
    </script>";
    }
    ?>
    
    <form class="box" action="includes/process_login.php" method="post" name="login_form">
      <h1>Login</h1>
      <img src="styles/logo.png" alt="Smiley face" width="130">
      <input type="text" name="email" placeholder="Email">
      <input type="password" name="password" id="password" placeholder="Password">
      <button class="btn btnindex" onclick="formhash(this.form, this.form.password);" />Login </button>
      <div style="color: #9C9C9C; font-size: 13px;">
        <p>If you don't have a login, please <a class="login_btn" href="register.php">register</a></p>
        <p>If you are done, please <a class="login_btn" href="includes/logout.php">log out</a>.</p>
        <p>You are currently logged <?php echo $logged ?>.</p>
      </div>
    </form>
  </body>
</html>