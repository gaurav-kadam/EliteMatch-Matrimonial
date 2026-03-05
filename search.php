<?php include_once("includes/basic_includes.php");?>
<?php include_once("functions.php"); ?>
<?php 
if(!isloggedin()){
  header("location:login.php");
  exit;
}
$result = search();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Search Profiles - EliteMatch</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
</head>
<body>

<?php include_once("includes/navigation.php");?>

<!-- Page Header -->
<div class="em-page-header">
  <div class="container">
    <h1><i class="fas fa-search me-2"></i>Search Profiles</h1>
    <p>Find your ideal partner using our advanced filters</p>
  </div>
</div>

<div class="em-search-page">
  <div class="container">
    <div class="row g-4">
      <!-- Search Filters -->
      <div class="col-lg-4">
        <div class="em-search-filters">
          <h4><i class="fas fa-filter me-2"></i>Filter Profiles</h4>
          <form action="" method="post">
            <div class="mb-3">
              <label class="form-label">Looking for</label>
              <div class="d-flex gap-3">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="sex" value="male" id="sex-male" checked>
                  <label class="form-check-label" for="sex-male">Groom</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="sex" value="female" id="sex-female">
                  <label class="form-check-label" for="sex-female">Bride</label>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Age Range</label>
              <div class="row g-2">
                <div class="col-6">
                  <input type="number" class="form-control" name="agemin" placeholder="Min (18)" min="18" max="70" value="18">
                </div>
                <div class="col-6">
                  <input type="number" class="form-control" name="agemax" placeholder="Max (40)" min="18" max="70" value="40">
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Marital Status</label>
              <select name="maritalstatus" class="form-select">
                <option value="Single">Single</option>
                <option value="divorced">Divorced</option>
                <option value="widowed">Widowed</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Religion</label>
              <select name="religion" class="form-select">
                <option value="">Any Religion</option>
                <option value="Hindu">Hindu</option>
                <option value="Muslim">Muslim</option>
                <option value="Christian">Christian</option>
                <option value="Sikh">Sikh</option>
                <option value="Jain">Jain</option>
                <option value="Not Applicable">Not Applicable</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Country</label>
              <select name="country" class="form-select">
                <option value="">Any Country</option>
                <option value="India">India</option>
                <option value="UAE">UAE</option>
                <option value="USA">USA</option>
                <option value="UK">UK</option>
                <option value="Canada">Canada</option>
                <option value="Australia">Australia</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">State</label>
              <select name="state" class="form-select">
                <option value="">Any State</option>
                <option value="Maharashtra">Maharashtra</option>
                <option value="Kerala">Kerala</option>
                <option value="Karnataka">Karnataka</option>
                <option value="Tamilnadu">Tamil Nadu</option>
                <option value="Delhi">Delhi</option>
                <option value="Gujarat">Gujarat</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Mother Tongue</label>
              <select name="mothertounge" class="form-select">
                <option value="">Any Language</option>
                <option value="Hindi">Hindi</option>
                <option value="Marathi">Marathi</option>
                <option value="Malayalam">Malayalam</option>
                <option value="Tamil">Tamil</option>
                <option value="Telugu">Telugu</option>
                <option value="Bengali">Bengali</option>
                <option value="English">English</option>
                <option value="Urdu">Urdu</option>
              </select>
            </div>
            <button type="submit" name="search" class="btn-search">
              <i class="fas fa-search me-2"></i>Search Profiles
            </button>
          </form>
        </div>
      </div>

      <!-- Results -->
      <div class="col-lg-8">
        <div class="em-search-results">
          <?php if(isset($_POST['search'])): ?>
            <h4><i class="fas fa-users me-2" style="color:var(--primary)"></i>Search Results</h4>
            <div class="row g-3">
            <?php
            if($result){
              $count = 0;
              while($row = mysqli_fetch_assoc($result)){
                $count++;
                $profid = intval($row['cust_id']);
                $sql2 = "SELECT pic1 FROM photos WHERE cust_id=$profid";
                $result2 = mysqlexec($sql2);
                $pic = 'images/default-avatar.png';
                if($result2 && $photo = mysqli_fetch_assoc($result2)){
                  if(!empty($photo['pic1'])) $pic = "profile/$profid/".$photo['pic1'];
                }
            ?>
              <div class="col-md-6">
                <div class="em-profile-card">
                  <div class="card-img-wrapper" style="height: 200px;">
                    <img src="<?php echo $pic; ?>" alt="<?php echo h($row['firstname']); ?>">
                  </div>
                  <div class="card-body">
                    <span class="profile-id">ID: EM<?php echo $profid; ?></span>
                    <h5><?php echo h($row['firstname']) . ' ' . h($row['lastname']); ?></h5>
                    <p><i class="fas fa-birthday-cake"></i> <?php echo h($row['age']); ?> Yrs</p>
                    <p><i class="fas fa-pray"></i> <?php echo h($row['religion']); ?></p>
                    <p><i class="fas fa-map-marker-alt"></i> <?php echo h($row['state']) . ', ' . h($row['country']); ?></p>
                  </div>
                  <div class="card-footer">
                    <small class="text-muted"><?php echo h($row['occupation']); ?></small>
                    <a href="view_profile.php?id=<?php echo $profid; ?>" class="btn-view">View</a>
                  </div>
                </div>
              </div>
            <?php
              }
              if($count == 0){
                echo '<div class="col-12"><div class="text-center py-5"><i class="fas fa-search" style="font-size:3rem; color:var(--gray-300); margin-bottom:1rem;"></i><h5 style="color:var(--gray-500);">No profiles found</h5><p style="color:var(--gray-400);">Try adjusting your search filters</p></div></div>';
              }
            }
            ?>
            </div>
          <?php else: ?>
            <div class="text-center py-5">
              <i class="fas fa-arrow-left" style="font-size:3rem; color:var(--gray-300); margin-bottom:1rem; display:block;"></i>
              <h5 style="color:var(--gray-500);">Use the filters to search</h5>
              <p style="color:var(--gray-400);">Select your preferences and click "Search Profiles" to find matches</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once("footer.php");?>