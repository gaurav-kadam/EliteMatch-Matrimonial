<?php include_once("includes/basic_includes.php");?>
<?php include_once("functions.php"); ?>
<?php 
if(!isloggedin()){
  header("location:login.php");
  exit;
}
$id = intval($_SESSION['id']);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  processprofile_form($id);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Create/Edit Profile - EliteMatch</title>
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
    <h1><i class="fas fa-edit me-2"></i>Create / Edit Profile</h1>
    <p>Complete your profile to increase your chances of finding a match</p>
  </div>
</div>

<div class="em-form-page">
  <div class="container" style="max-width: 900px;">
    <form action="" method="POST">
      <!-- Personal Information -->
      <div class="em-form-section">
        <h4><i class="fas fa-user"></i> Personal Information</h4>
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">First Name <span class="text-danger">*</span></label>
            <input type="text" name="fname" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Last Name <span class="text-danger">*</span></label>
            <input type="text" name="lname" class="form-control" required>
          </div>
          <div class="col-md-2">
            <label class="form-label">Gender <span class="text-danger">*</span></label>
            <select name="sex" class="form-select"><option value="Male">Male</option><option value="Female">Female</option></select>
          </div>
          <div class="col-md-2">
            <label class="form-label">Age <span class="text-danger">*</span></label>
            <select name="age" class="form-select">
              <?php for($i=18;$i<=70;$i++): ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php endfor; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
            <div class="row g-2">
              <div class="col-4"><select name="day" class="form-select"><option value="">Day</option><?php for($i=1;$i<=31;$i++): ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php endfor; ?></select></div>
              <div class="col-4"><select name="month" class="form-select"><option value="">Month</option><?php $m=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']; for($i=0;$i<12;$i++): ?><option value="<?php echo str_pad($i+1,2,'0',STR_PAD_LEFT); ?>"><?php echo $m[$i]; ?></option><?php endfor; ?></select></div>
              <div class="col-4"><select name="year" class="form-select"><option value="">Year</option><?php for($i=date('Y')-18;$i>=1970;$i--): ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php endfor; ?></select></div>
            </div>
          </div>
          <div class="col-md-3"><label class="form-label">Marital Status</label><select name="maritalstatus" class="form-select"><option value="Single">Single</option><option value="Married">Married</option><option value="Divorced">Divorced</option><option value="Widowed">Widowed</option></select></div>
          <div class="col-md-3"><label class="form-label">Profile By</label><select name="profileby" class="form-select"><option value="Self">Self</option><option value="Parent">Parent</option><option value="Sibling">Sibling</option><option value="Other">Other</option></select></div>
          <div class="col-md-3"><label class="form-label">Mother Tongue</label><select name="mothertounge" class="form-select"><option value="Hindi">Hindi</option><option value="Marathi">Marathi</option><option value="English">English</option><option value="Tamil">Tamil</option><option value="Telugu">Telugu</option><option value="Malayalam">Malayalam</option><option value="Bengali">Bengali</option><option value="Gujarati">Gujarati</option><option value="Urdu">Urdu</option></select></div>
          <div class="col-md-3"><label class="form-label">Blood Group</label><select name="bloodgroup" class="form-select"><option value="O +ve">O +ve</option><option value="O -ve">O -ve</option><option value="A +ve">A +ve</option><option value="A -ve">A -ve</option><option value="B +ve">B +ve</option><option value="B -ve">B -ve</option><option value="AB +ve">AB +ve</option><option value="AB -ve">AB -ve</option></select></div>
        </div>
      </div>

      <!-- Religion & Location -->
      <div class="em-form-section">
        <h4><i class="fas fa-pray"></i> Religion & Location</h4>
        <div class="row g-3">
          <div class="col-md-4"><label class="form-label">Religion</label><select name="religion" class="form-select"><option value="Hindu">Hindu</option><option value="Muslim">Muslim</option><option value="Christian">Christian</option><option value="Sikh">Sikh</option><option value="Jain">Jain</option><option value="Not Applicable">Not Applicable</option></select></div>
          <div class="col-md-4"><label class="form-label">Caste</label><select name="caste" class="form-select"><option value="General">General</option><option value="OBC">OBC</option><option value="SC">SC</option><option value="ST">ST</option><option value="NT">NT</option></select></div>
          <div class="col-md-4"><label class="form-label">Sub Caste</label><input type="text" name="subcaste" class="form-control" placeholder="Enter sub caste"></div>
          <div class="col-md-4"><label class="form-label">Country</label><select name="country" class="form-select"><option value="India">India</option><option value="USA">USA</option><option value="UK">UK</option><option value="UAE">UAE</option><option value="Canada">Canada</option><option value="Australia">Australia</option></select></div>
          <div class="col-md-4"><label class="form-label">State</label><select name="state" class="form-select"><option value="Maharashtra">Maharashtra</option><option value="Kerala">Kerala</option><option value="Karnataka">Karnataka</option><option value="Tamilnadu">Tamil Nadu</option><option value="Delhi">Delhi</option><option value="Gujarat">Gujarat</option><option value="Rajasthan">Rajasthan</option></select></div>
          <div class="col-md-4"><label class="form-label">District/City</label><input type="text" name="district" class="form-control" placeholder="Enter city"></div>
        </div>
      </div>

      <!-- Physical & Lifestyle -->
      <div class="em-form-section">
        <h4><i class="fas fa-heartbeat"></i> Physical & Lifestyle</h4>
        <div class="row g-3">
          <div class="col-md-3"><label class="form-label">Height (cm)</label><input type="number" name="height" class="form-control" placeholder="170"></div>
          <div class="col-md-3"><label class="form-label">Weight (kg)</label><input type="number" name="weight" class="form-control" placeholder="65"></div>
          <div class="col-md-3"><label class="form-label">Body Type</label><select name="bodytype" class="form-select"><option value="Slim">Slim</option><option value="Average">Average</option><option value="Athletic">Athletic</option><option value="Heavy">Heavy</option></select></div>
          <div class="col-md-3"><label class="form-label">Complexion</label><select name="colour" class="form-select"><option value="Fair">Fair</option><option value="Wheatish">Wheatish</option><option value="Dark">Dark</option></select></div>
          <div class="col-md-3"><label class="form-label">Physical Status</label><select name="physicalstatus" class="form-select"><option value="Normal">Normal</option><option value="Physically Challenged">Physically Challenged</option></select></div>
          <div class="col-md-3"><label class="form-label">Diet</label><select name="diet" class="form-select"><option value="Veg">Vegetarian</option><option value="Non Veg">Non-Vegetarian</option><option value="Eggetarian">Eggetarian</option></select></div>
          <div class="col-md-3"><label class="form-label">Drinks</label><select name="drink" class="form-select"><option value="No">No</option><option value="Yes">Yes</option><option value="Occasionally">Occasionally</option></select></div>
          <div class="col-md-3"><label class="form-label">Smoke</label><select name="smoke" class="form-select"><option value="No">No</option><option value="Yes">Yes</option><option value="Occasionally">Occasionally</option></select></div>
        </div>
      </div>

      <!-- Education & Career -->
      <div class="em-form-section">
        <h4><i class="fas fa-graduation-cap"></i> Education & Career</h4>
        <div class="row g-3">
          <div class="col-md-4"><label class="form-label">Education</label><select name="education" class="form-select"><option value="Primary">Primary</option><option value="10th">10th</option><option value="12th">12th / Diploma</option><option value="Degree">Bachelor's Degree</option><option value="PG">Post Graduate</option><option value="Doctorate">Doctorate</option></select></div>
          <div class="col-md-4"><label class="form-label">Specialization</label><input type="text" name="edudescr" class="form-control" placeholder="e.g. Computer Science"></div>
          <div class="col-md-4"><label class="form-label">Occupation</label><input type="text" name="occupation" class="form-control" placeholder="e.g. Software Engineer"></div>
          <div class="col-md-4"><label class="form-label">Occupation Detail</label><input type="text" name="occupationdescr" class="form-control" placeholder="Company / Role"></div>
          <div class="col-md-4"><label class="form-label">Annual Income (₹)</label><input type="text" name="income" class="form-control" placeholder="e.g. 500000"></div>
        </div>
      </div>

      <!-- Family -->
      <div class="em-form-section">
        <h4><i class="fas fa-users"></i> Family Details</h4>
        <div class="row g-3">
          <div class="col-md-3"><label class="form-label">Father's Occupation</label><input type="text" name="fatheroccupation" class="form-control"></div>
          <div class="col-md-3"><label class="form-label">Mother's Occupation</label><input type="text" name="motheroccupation" class="form-control"></div>
          <div class="col-md-3"><label class="form-label">No. of Brothers</label><select name="bros" class="form-select"><?php for($i=0;$i<=10;$i++): ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php endfor; ?></select></div>
          <div class="col-md-3"><label class="form-label">No. of Sisters</label><select name="sis" class="form-select"><?php for($i=0;$i<=10;$i++): ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php endfor; ?></select></div>
        </div>
      </div>

      <!-- About Me -->
      <div class="em-form-section">
        <h4><i class="fas fa-pen-fancy"></i> About Me</h4>
        <textarea name="aboutme" class="form-control" rows="5" placeholder="Write about yourself, your interests, hobbies, and what you're looking for in a partner..."></textarea>
      </div>

      <div class="text-center">
        <button type="submit" class="btn-submit"><i class="fas fa-save me-2"></i>Save Profile</button>
      </div>
    </form>
  </div>
</div>

<?php include_once("footer.php");?>