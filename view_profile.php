<?php include_once("includes/basic_includes.php");?>
<?php include_once("functions.php"); ?>
<?php
if(!isloggedin()){
  header("location:login.php");
  exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$profileid = $id;
$myId = intval($_SESSION['id']);

// Handle Express Interest
if(isset($_POST['express_interest']) && $myId != $id){
  $interestResult = expressInterest($myId, $id);
}

// Get profile details
$conn = getConn();
$stmt = mysqli_prepare($conn, "SELECT * FROM customer WHERE cust_id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$profileExists = false;

if($result && mysqli_num_rows($result) > 0){
  $profileExists = true;
  $row = mysqli_fetch_assoc($result);
  $fname = h($row['firstname']);
  $lname = h($row['lastname']);
  $sex = h($row['sex']);
  $email = h($row['email']);
  $dob = h($row['dateofbirth']);
  $religion = h($row['religion']);
  $caste = h($row['caste']);
  $subcaste = h($row['subcaste']);
  $country = h($row['country']);
  $state = h($row['state']);
  $district = h($row['district']);
  $age = h($row['age']);
  $maritalstatus = h($row['maritalstatus']);
  $profileby = h($row['profilecreatedby']);
  $education = h($row['education']);
  $edudescr = h($row['education_sub']);
  $bodytype = h($row['body_type']);
  $physicalstatus = h($row['physical_status']);
  $drink = h($row['drink']);
  $smoke = h($row['smoke']);
  $mothertounge = h($row['mothertounge']);
  $bloodgroup = h($row['blood_group']);
  $weight = h($row['weight']);
  $height = h($row['height']);
  $colour = h($row['colour']);
  $diet = h($row['diet']);
  $occupation = h($row['occupation']);
  $occupationdescr = h($row['occupation_descr']);
  $fatheroccupation = h($row['fathers_occupation']);
  $motheroccupation = h($row['mothers_occupation']);
  $income = h($row['annual_income']);
  $bros = h($row['no_bro']);
  $sis = h($row['no_sis']);
  $aboutme = h($row['aboutme']);

  $pic1=$pic2=$pic3=$pic4="";
  $stmt2 = mysqli_prepare($conn, "SELECT * FROM photos WHERE cust_id = ?");
  mysqli_stmt_bind_param($stmt2, "i", $profileid);
  mysqli_stmt_execute($stmt2);
  $result2 = mysqli_stmt_get_result($stmt2);
  if($result2 && $row2 = mysqli_fetch_array($result2)){
    $pic1=$row2['pic1']; $pic2=$row2['pic2']; $pic3=$row2['pic3']; $pic4=$row2['pic4'];
  }

  // Get partner prefs
  $stmt3 = mysqli_prepare($conn, "SELECT * FROM partnerprefs WHERE custId = ?");
  mysqli_stmt_bind_param($stmt3, "i", $id);
  mysqli_stmt_execute($stmt3);
  $result3 = mysqli_stmt_get_result($stmt3);
  $pref = mysqli_fetch_assoc($result3);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $profileExists ? "$fname $lname - Profile" : "Profile"; ?> | EliteMatch</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
</head>
<body>

<?php include_once("includes/navigation.php");?>

<?php if(!$profileExists): ?>
<div class="em-profile-page">
  <div class="container text-center py-5">
    <i class="fas fa-user-slash" style="font-size:4rem; color:var(--gray-300); margin-bottom:1rem;"></i>
    <h3 style="color:var(--gray-500);">Profile Not Found</h3>
    <p style="color:var(--gray-400);">This profile doesn't exist or hasn't been created yet.</p>
    <a href="<?php echo ($myId == $id) ? 'create_profile.php?id='.$id : 'search.php'; ?>" class="btn-submit mt-3">
      <?php echo ($myId == $id) ? '<i class="fas fa-plus me-2"></i>Create Your Profile' : '<i class="fas fa-search me-2"></i>Search Profiles'; ?>
    </a>
  </div>
</div>
<?php else: ?>

<div class="em-profile-page">
  <div class="container">
    <!-- Profile Hero -->
    <div class="em-profile-hero">
      <img src="<?php echo !empty($pic1) ? "profile/$profileid/$pic1" : 'https://ui-avatars.com/api/?name='.urlencode("$fname $lname").'&background=8B5CF6&color=fff&size=200'; ?>" alt="<?php echo "$fname $lname"; ?>" class="em-profile-avatar">
      <div class="em-profile-hero-info">
        <span class="profile-id-badge"><i class="fas fa-id-badge me-1"></i> EM<?php echo $profileid; ?></span>
        <h2><?php echo "$fname $lname"; ?></h2>
        <p><i class="fas fa-birthday-cake"></i> <?php echo $age; ?> Years | <?php echo $sex; ?></p>
        <p><i class="fas fa-map-marker-alt"></i> <?php echo "$district, $state, $country"; ?></p>
        <p><i class="fas fa-pray"></i> <?php echo "$religion - $caste"; ?></p>
        <?php if($myId != $id): ?>
        <form method="post" style="display:inline;">
          <button type="submit" name="express_interest" class="btn-interest">
            <i class="fas fa-heart"></i> Express Interest
          </button>
        </form>
        <?php if(isset($interestResult)): ?>
          <?php if($interestResult == 'success'): ?>
            <span class="em-badge em-badge-success ms-2" style="font-size:0.9rem; padding:8px 16px;"><i class="fas fa-check me-1"></i> Interest Sent!</span>
          <?php elseif($interestResult == 'already_sent'): ?>
            <span class="em-badge em-badge-warm ms-2" style="font-size:0.9rem; padding:8px 16px;"><i class="fas fa-info-circle me-1"></i> Already Sent</span>
          <?php endif; ?>
        <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-8">
        <!-- About Me -->
        <?php if(!empty($aboutme)): ?>
        <div class="em-detail-card mb-4">
          <div class="card-header-custom"><i class="fas fa-quote-left"></i> About Me</div>
          <div class="card-body-custom"><p style="color:var(--gray-600); line-height:1.8;"><?php echo $aboutme; ?></p></div>
        </div>
        <?php endif; ?>

        <!-- Tabs -->
        <ul class="nav em-nav-tabs mb-3" role="tablist">
          <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#basics">Basics & Lifestyle</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#family">Family</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#career">Education & Career</a></li>
          <?php if($pref): ?>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#partner">Partner Prefs</a></li>
          <?php endif; ?>
        </ul>

        <div class="tab-content">
          <!-- Basics Tab -->
          <div class="tab-pane fade show active" id="basics">
            <div class="row g-4">
              <div class="col-md-6">
                <div class="em-detail-card">
                  <div class="card-header-custom"><i class="fas fa-user"></i> Personal Details</div>
                  <div class="card-body-custom">
                    <table class="em-detail-table">
                      <tr><td>Name</td><td><?php echo "$fname $lname"; ?></td></tr>
                      <tr><td>Age</td><td><?php echo $age; ?> Years</td></tr>
                      <tr><td>Height</td><td><?php echo $height; ?> cm</td></tr>
                      <tr><td>Weight</td><td><?php echo $weight; ?> kg</td></tr>
                      <tr><td>Date of Birth</td><td><?php echo $dob; ?></td></tr>
                      <tr><td>Marital Status</td><td><?php echo $maritalstatus; ?></td></tr>
                      <tr><td>Profile By</td><td><?php echo $profileby; ?></td></tr>
                    </table>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="em-detail-card">
                  <div class="card-header-custom"><i class="fas fa-heartbeat"></i> Lifestyle</div>
                  <div class="card-body-custom">
                    <table class="em-detail-table">
                      <tr><td>Body Type</td><td><?php echo $bodytype; ?></td></tr>
                      <tr><td>Complexion</td><td><?php echo $colour; ?></td></tr>
                      <tr><td>Blood Group</td><td><?php echo $bloodgroup; ?></td></tr>
                      <tr><td>Physical Status</td><td><?php echo $physicalstatus; ?></td></tr>
                      <tr><td>Diet</td><td><?php echo $diet; ?></td></tr>
                      <tr><td>Drink</td><td><?php echo $drink; ?></td></tr>
                      <tr><td>Smoke</td><td><?php echo $smoke; ?></td></tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Family Tab -->
          <div class="tab-pane fade" id="family">
            <div class="em-detail-card">
              <div class="card-header-custom"><i class="fas fa-users"></i> Family Details</div>
              <div class="card-body-custom">
                <div class="row">
                  <div class="col-md-6">
                    <table class="em-detail-table">
                      <tr><td>Father's Occupation</td><td><?php echo $fatheroccupation; ?></td></tr>
                      <tr><td>Mother's Occupation</td><td><?php echo $motheroccupation; ?></td></tr>
                    </table>
                  </div>
                  <div class="col-md-6">
                    <table class="em-detail-table">
                      <tr><td>No. of Brothers</td><td><?php echo $bros; ?></td></tr>
                      <tr><td>No. of Sisters</td><td><?php echo $sis; ?></td></tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Career Tab -->
          <div class="tab-pane fade" id="career">
            <div class="em-detail-card">
              <div class="card-header-custom"><i class="fas fa-graduation-cap"></i> Education & Career</div>
              <div class="card-body-custom">
                <table class="em-detail-table">
                  <tr><td>Education</td><td><?php echo $education; ?></td></tr>
                  <tr><td>Specialization</td><td><?php echo $edudescr; ?></td></tr>
                  <tr><td>Occupation</td><td><?php echo $occupation; ?></td></tr>
                  <tr><td>Occupation Detail</td><td><?php echo $occupationdescr; ?></td></tr>
                  <tr><td>Annual Income</td><td>₹<?php echo $income; ?></td></tr>
                </table>
              </div>
            </div>
          </div>

          <!-- Partner Prefs Tab -->
          <?php if($pref): ?>
          <div class="tab-pane fade" id="partner">
            <div class="em-detail-card">
              <div class="card-header-custom"><i class="fas fa-heart"></i> Partner Preferences</div>
              <div class="card-body-custom">
                <table class="em-detail-table">
                  <tr><td>Age Range</td><td><?php echo h($pref['agemin']) . ' to ' . h($pref['agemax']); ?> Yrs</td></tr>
                  <tr><td>Marital Status</td><td><?php echo h($pref['maritalstatus']); ?></td></tr>
                  <tr><td>Height</td><td><?php echo h($pref['height']); ?> cm</td></tr>
                  <tr><td>Diet</td><td><?php echo h($pref['diet']); ?></td></tr>
                  <tr><td>Religion</td><td><?php echo h($pref['religion']); ?></td></tr>
                  <tr><td>Caste</td><td><?php echo h($pref['caste']); ?></td></tr>
                  <tr><td>Mother Tongue</td><td><?php echo h($pref['mothertounge']); ?></td></tr>
                  <tr><td>Education</td><td><?php echo h($pref['education']); ?></td></tr>
                  <tr><td>Occupation</td><td><?php echo h($pref['occupation']); ?></td></tr>
                  <tr><td>Country</td><td><?php echo h($pref['country']); ?></td></tr>
                </table>
                <?php if(!empty($pref['descr'])): ?>
                <div class="mt-3 p-3" style="background:var(--gray-50); border-radius:var(--radius-sm);">
                  <strong style="color:var(--gray-600); font-size:0.85rem;">Description:</strong>
                  <p class="mb-0 mt-1" style="color:var(--gray-700);"><?php echo h($pref['descr']); ?></p>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Sidebar - Photos & Recent Profiles -->
      <div class="col-lg-4">
        <!-- Photo Gallery -->
        <?php if(!empty($pic1) || !empty($pic2) || !empty($pic3) || !empty($pic4)): ?>
        <div class="em-detail-card mb-4">
          <div class="card-header-custom"><i class="fas fa-images"></i> Photo Gallery</div>
          <div class="card-body-custom">
            <div class="row g-2">
              <?php foreach([$pic1,$pic2,$pic3,$pic4] as $pic): ?>
              <?php if(!empty($pic)): ?>
              <div class="col-6">
                <img src="profile/<?php echo $profileid; ?>/<?php echo $pic; ?>" alt="Photo" style="width:100%; height:140px; object-fit:cover; border-radius:var(--radius-sm);">
              </div>
              <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <!-- Recent Profiles -->
        <div class="em-detail-card">
          <div class="card-header-custom"><i class="fas fa-clock"></i> Recent Profiles</div>
          <div class="card-body-custom">
            <?php
            $sqlRecent = "SELECT c.*, p.pic1 FROM customer c LEFT JOIN photos p ON c.cust_id = p.cust_id ORDER BY c.profilecreationdate DESC LIMIT 5";
            $resRecent = mysqlexec($sqlRecent);
            if($resRecent){
              while($rp = mysqli_fetch_assoc($resRecent)){
                $rpId = intval($rp['cust_id']);
                $rpPic = !empty($rp['pic1']) ? "profile/$rpId/".$rp['pic1'] : "https://ui-avatars.com/api/?name=".urlencode($rp['firstname'])."&background=8B5CF6&color=fff&size=50";
            ?>
            <a href="view_profile.php?id=<?php echo $rpId; ?>" style="display:flex; align-items:center; gap:12px; padding:10px; border-radius:var(--radius-sm); transition:var(--transition); margin-bottom:8px; text-decoration:none; color:inherit;" onmouseover="this.style.background='var(--gray-50)'" onmouseout="this.style.background='transparent'">
              <img src="<?php echo $rpPic; ?>" alt="" style="width:45px; height:45px; border-radius:50%; object-fit:cover;">
              <div>
                <h6 style="margin:0; font-size:0.9rem; font-weight:600; color:var(--dark);"><?php echo h($rp['firstname']); ?></h6>
                <small style="color:var(--gray-500);"><?php echo h($rp['age']); ?> Yrs, <?php echo h($rp['religion']); ?></small>
              </div>
            </a>
            <?php }} ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php include_once("footer.php");?>