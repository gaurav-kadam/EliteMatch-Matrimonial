<?php include_once("includes/basic_includes.php");?>
<?php include_once("functions.php"); ?>
<?php
if(!isloggedin()){
  header("location:login.php");
  exit;
}
$id = intval($_SESSION['id']);
$getid = isset($_GET['id']) ? intval($_GET['id']) : $id;

// Get user info
$conn = getConn();
$stmt = mysqli_prepare($conn, "SELECT username FROM users WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$userResult = mysqli_stmt_get_result($stmt);
$userData = mysqli_fetch_assoc($userResult);
$username = $userData ? h($userData['username']) : 'User';

$completeness = getProfileCompleteness($id);
$interestsCount = getInterestsCount($id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Dashboard - EliteMatch</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
</head>
<body>

<?php include_once("includes/navigation.php");?>

<div class="em-dashboard">
  <div class="container">
    <!-- Welcome Banner -->
    <div class="em-welcome-banner">
      <h2><i class="fas fa-sparkles me-2"></i>Welcome back, <?php echo $username; ?>!</h2>
      <p>Manage your profile and discover your perfect match</p>
      <div class="row mt-3" style="position:relative; z-index:2;">
        <div class="col-md-4">
          <div style="background: rgba(255,255,255,0.15); border-radius: 12px; padding: 1rem; text-align: center;">
            <h4 style="margin:0; font-weight:800;"><?php echo $completeness; ?>%</h4>
            <small>Profile Complete</small>
            <div class="em-progress mt-2" style="background: rgba(255,255,255,0.2);">
              <div class="em-progress-bar" style="width: <?php echo $completeness; ?>%; background: var(--accent);"></div>
            </div>
          </div>
        </div>
        <div class="col-md-4 mt-2 mt-md-0">
          <div style="background: rgba(255,255,255,0.15); border-radius: 12px; padding: 1rem; text-align: center;">
            <h4 style="margin:0; font-weight:800;"><?php echo $interestsCount; ?></h4>
            <small>Interests Received</small>
          </div>
        </div>
        <div class="col-md-4 mt-2 mt-md-0">
          <div style="background: rgba(255,255,255,0.15); border-radius: 12px; padding: 1rem; text-align: center;">
            <h4 style="margin:0; font-weight:800;">EM<?php echo $id; ?></h4>
            <small>Your Profile ID</small>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-4 mb-4">
      <div class="col-lg-3 col-md-6">
        <a href="view_profile.php?id=<?php echo $id;?>" class="em-action-card">
          <i class="fas fa-user-circle"></i>
          <h5>View Profile</h5>
          <p>See how others view your profile</p>
        </a>
      </div>
      <div class="col-lg-3 col-md-6">
        <a href="create_profile.php?id=<?php echo $id;?>" class="em-action-card">
          <i class="fas fa-edit"></i>
          <h5>Edit Profile</h5>
          <p>Update your profile details</p>
        </a>
      </div>
      <div class="col-lg-3 col-md-6">
        <a href="photouploader.php?id=<?php echo $id;?>" class="em-action-card">
          <i class="fas fa-camera"></i>
          <h5>Upload Photos</h5>
          <p>Add or change your photos</p>
        </a>
      </div>
      <div class="col-lg-3 col-md-6">
        <a href="search.php" class="em-action-card">
          <i class="fas fa-search"></i>
          <h5>Search Profiles</h5>
          <p>Find your perfect match</p>
        </a>
      </div>
    </div>

    <!-- More Actions -->
    <div class="row g-4">
      <div class="col-lg-3 col-md-6">
        <a href="partner_preference.php?id=<?php echo $id;?>" class="em-action-card">
          <i class="fas fa-sliders-h"></i>
          <h5>Partner Preferences</h5>
          <p>Set what you're looking for</p>
        </a>
      </div>
      <div class="col-lg-3 col-md-6">
        <a href="search-id.php" class="em-action-card">
          <i class="fas fa-id-badge"></i>
          <h5>Search by ID</h5>
          <p>Find a specific profile</p>
        </a>
      </div>
      <div class="col-lg-3 col-md-6">
        <a href="contact.php" class="em-action-card">
          <i class="fas fa-headset"></i>
          <h5>Get Help</h5>
          <p>Contact our support team</p>
        </a>
      </div>
      <div class="col-lg-3 col-md-6">
        <a href="logout.php" class="em-action-card" style="border-color: rgba(239,68,68,0.2);">
          <i class="fas fa-sign-out-alt" style="background: linear-gradient(135deg, #EF4444, #DC2626); -webkit-background-clip:text;"></i>
          <h5 style="color: var(--danger);">Logout</h5>
          <p>Sign out of your account</p>
        </a>
      </div>
    </div>
  </div>
</div>

<?php include_once("footer.php");?>