<?php include_once("includes/basic_includes.php");?>
<?php include_once("functions.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>About Us - EliteMatch</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
</head>
<body>

<?php include_once("includes/navigation.php");?>

<div class="em-about-hero">
  <div class="container">
    <h1><i class="fas fa-heart me-2"></i>About EliteMatch</h1>
    <p>India's most trusted matrimonial platform connecting hearts since 2020</p>
  </div>
</div>

<section class="em-section em-section-light">
  <div class="container" style="max-width:900px;">
    <div class="row g-5 align-items-center mb-5">
      <div class="col-md-6">
        <h2 style="font-weight:700; color:var(--dark); margin-bottom:1rem;">Our Mission</h2>
        <p style="color:var(--gray-600); line-height:1.8;">At EliteMatch, we believe that finding a life partner is one of the most important decisions you'll ever make. Our platform combines traditional matchmaking values with cutting-edge technology to help you discover your perfect match.</p>
        <p style="color:var(--gray-600); line-height:1.8;">With millions of verified profiles and a dedicated team, we've helped thousands of couples begin their journey together. Our AI-powered matching algorithm considers your preferences, values, and lifestyle to suggest the most compatible partners.</p>
      </div>
      <div class="col-md-6">
        <div style="background: var(--gradient-primary); border-radius: var(--radius-xl); padding: 3rem; text-align: center; color: white;">
          <i class="fas fa-users" style="font-size: 3rem; margin-bottom: 1rem;"></i>
          <h3 style="font-weight: 800; font-size: 2.5rem;"><?php echo getTotalProfiles(); ?>+</h3>
          <p style="opacity: 0.9;">Verified Profiles & Growing</p>
        </div>
      </div>
    </div>

    <div class="row g-4 mt-4">
      <div class="col-md-4">
        <div class="em-action-card">
          <i class="fas fa-shield-alt"></i>
          <h5>100% Verified</h5>
          <p>Every profile is manually verified by our team for authenticity</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="em-action-card">
          <i class="fas fa-lock"></i>
          <h5>Privacy First</h5>
          <p>Your data is encrypted and protected with industry standards</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="em-action-card">
          <i class="fas fa-headset"></i>
          <h5>24/7 Support</h5>
          <p>Our dedicated team is always available to help you</p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include_once("footer.php");?>