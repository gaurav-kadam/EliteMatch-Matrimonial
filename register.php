<?php include_once("includes/basic_includes.php");?>
<?php include_once("functions.php"); ?>
<?php
if(isloggedin()){
  header("Location: userhome.php?id=" . $_SESSION['id']);
  exit;
}
register();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Register - EliteMatch Matrimonial</title>
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
  
  <div class="em-auth-card" style="max-width: 520px;">
    <div class="text-center mb-3">
      <i class="fas fa-heart" style="font-size: 2.5rem; background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
    </div>
    <h2>Create Account</h2>
    <p class="auth-subtitle">Start your journey to find your perfect partner</p>
    
    <form action="" method="POST">
      <div class="mb-3">
        <label class="form-label">Username <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" placeholder="Choose a username" required maxlength="50">
      </div>
      <div class="row g-3 mb-3">
        <div class="col-md-6">
          <label class="form-label">Password <span class="text-danger">*</span></label>
          <input type="password" name="pass" class="form-control" placeholder="Create password" required minlength="4">
        </div>
        <div class="col-md-6">
          <label class="form-label">Email <span class="text-danger">*</span></label>
          <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
        <div class="row g-2">
          <div class="col-4">
            <select name="day" class="form-select" required>
              <option value="">Day</option>
              <?php for($i=1;$i<=31;$i++): ?>
              <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
              <?php endfor; ?>
            </select>
          </div>
          <div class="col-4">
            <select name="month" class="form-select" required>
              <option value="">Month</option>
              <?php 
              $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
              for($i=0;$i<12;$i++): ?>
              <option value="<?php echo str_pad($i+1,2,'0',STR_PAD_LEFT); ?>"><?php echo $months[$i]; ?></option>
              <?php endfor; ?>
            </select>
          </div>
          <div class="col-4">
            <select name="year" class="form-select" required>
              <option value="">Year</option>
              <?php for($i=date('Y')-18; $i>=1970; $i--): ?>
              <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
              <?php endfor; ?>
            </select>
          </div>
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Gender <span class="text-danger">*</span></label>
        <div class="d-flex gap-4">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" value="male" id="gender-male" checked>
            <label class="form-check-label" for="gender-male"><i class="fas fa-mars me-1" style="color: var(--primary);"></i> Male</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" value="female" id="gender-female">
            <label class="form-check-label" for="gender-female"><i class="fas fa-venus me-1" style="color: var(--secondary);"></i> Female</label>
          </div>
        </div>
      </div>
      <button type="submit" class="btn-auth"><i class="fas fa-user-plus me-2"></i>Create My Account</button>
    </form>
    <div class="text-center mt-4">
      <p style="color: var(--gray-500); font-size: 0.92rem;">Already have an account? <a href="login.php" style="font-weight: 600;">Sign In</a></p>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
