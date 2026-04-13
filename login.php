<?php include_once("includes/basic_includes.php");?>
<?php include_once("functions.php"); ?>
<?php
if(isloggedin()){
  header("Location: userhome.php?id=" . $_SESSION['id']);
  exit;
}
$loginError = '';
if(isset($_SESSION['login_error'])){
  $loginError = $_SESSION['login_error'];
  unset($_SESSION['login_error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Login - EliteMatch Matrimonial</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
</head>
<body>

<div class="em-auth-page">
  <a href="index.php" class="em-auth-back"><i class="fas fa-arrow-left me-2"></i>Home</a>
  
  <div class="em-auth-card">
    <div class="text-center mb-3">
      <i class="fas fa-heart" style="font-size: 2.5rem; background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
    </div>
    <h2>Welcome</h2>
    <p class="auth-subtitle">Sign in to your EliteMatch account</p>
    
    <?php if(!empty($loginError)): ?>
    <div class="alert alert-danger">
      <i class="fas fa-exclamation-circle me-2"></i><?php echo h($loginError); ?>
    </div>
    <?php endif; ?>

    <form action="auth/auth.php?user=1" method="post">
      <div class="mb-3">
        <label class="form-label" for="login-username">Username</label>
        <div class="input-group">
          <span class="input-group-text" style="border: 2px solid var(--gray-200); border-right:none; border-radius: var(--radius) 0 0 var(--radius); background: var(--gray-50);"><i class="fas fa-user" style="color: var(--primary);"></i></span>
          <input type="text" id="login-username" name="username" class="form-control" placeholder="Enter your username" required style="border-left:none; border-radius: 0 var(--radius) var(--radius) 0;">
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label" for="login-password">Password</label>
        <div class="input-group">
          <span class="input-group-text" style="border: 2px solid var(--gray-200); border-right:none; border-radius: var(--radius) 0 0 var(--radius); background: var(--gray-50);"><i class="fas fa-lock" style="color: var(--primary);"></i></span>
          <input type="password" id="login-password" name="password" class="form-control" placeholder="Enter your password" required style="border-left:none; border-radius: 0 var(--radius) var(--radius) 0;">
        </div>
      </div>
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="remember">
          <label class="form-check-label" for="remember" style="font-size:0.88rem; color: var(--gray-600);">Remember me</label>
        </div>
      </div>
      <button type="submit" class="btn-auth"><i class="fas fa-sign-in-alt me-2"></i>Sign In</button>
    </form>
    <div class="text-center mt-4">
      <p style="color: var(--gray-500); font-size: 0.92rem;">Don't have an account? <a href="register.php" style="font-weight: 600;">Register Free</a></p>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
