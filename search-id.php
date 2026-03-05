<?php include_once("includes/basic_includes.php");?>
<?php include_once("functions.php"); ?>
<?php
if(!isloggedin()){
  header("location:login.php");
  exit;
}
$result = searchid();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Search by Profile ID - EliteMatch</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
</head>
<body>

<?php include_once("includes/navigation.php");?>

<div class="em-page-header">
  <div class="container">
    <h1><i class="fas fa-id-badge me-2"></i>Search by Profile ID</h1>
    <p>Enter a profile ID to find a specific member</p>
  </div>
</div>

<div class="em-form-page">
  <div class="container" style="max-width: 600px;">
    <div class="em-form-section text-center">
      <h4 style="justify-content:center;"><i class="fas fa-search"></i> Enter Profile ID</h4>
      <form action="" method="post">
        <div class="row g-3 justify-content-center">
          <div class="col-md-8">
            <input type="number" name="profid" class="form-control text-center" placeholder="Enter Profile ID (e.g. 12)" required style="font-size:1.2rem; padding:1rem;">
          </div>
          <div class="col-md-4">
            <button type="submit" class="btn-submit w-100" style="padding:1rem;"><i class="fas fa-search me-2"></i>Search</button>
          </div>
        </div>
      </form>
    </div>

    <?php if($result && mysqli_num_rows($result) > 0): ?>
    <div class="mt-4">
      <?php while($row = mysqli_fetch_assoc($result)):
        $profid = intval($row['cust_id']);
        $sql2 = "SELECT pic1 FROM photos WHERE cust_id=$profid";
        $r2 = mysqlexec($sql2);
        $pic = "https://ui-avatars.com/api/?name=".urlencode($row['firstname'])."&background=8B5CF6&color=fff";
        if($r2 && $p = mysqli_fetch_assoc($r2)){
          if(!empty($p['pic1'])) $pic = "profile/$profid/".$p['pic1'];
        }
      ?>
      <div class="em-profile-card">
        <div class="row g-0 align-items-center">
          <div class="col-md-4">
            <img src="<?php echo $pic; ?>" alt="" style="width:100%; height:200px; object-fit:cover; border-radius:var(--radius-lg) 0 0 var(--radius-lg);">
          </div>
          <div class="col-md-8">
            <div class="card-body">
              <span class="profile-id">ID: EM<?php echo $profid; ?></span>
              <h5><?php echo h($row['firstname']) . ' ' . h($row['lastname']); ?></h5>
              <p><i class="fas fa-birthday-cake"></i> <?php echo h($row['age']); ?> Years</p>
              <p><i class="fas fa-pray"></i> <?php echo h($row['religion']); ?></p>
              <p><i class="fas fa-map-marker-alt"></i> <?php echo h($row['state']).', '.h($row['country']); ?></p>
              <a href="view_profile.php?id=<?php echo $profid; ?>" class="btn-view mt-2">View Full Profile</a>
            </div>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
    <?php elseif($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
    <div class="text-center py-4">
      <i class="fas fa-user-slash" style="font-size:3rem; color:var(--gray-300); margin-bottom:1rem; display:block;"></i>
      <h5 style="color:var(--gray-500);">No profile found</h5>
      <p style="color:var(--gray-400);">Please check the profile ID and try again</p>
    </div>
    <?php endif; ?>
  </div>
</div>

<?php include_once("footer.php");?>