<?php include_once("includes/basic_includes.php");?>
<?php include_once("functions.php"); ?>
<?php
if(!isloggedin()){
  header("location:login.php");
  exit;
}
$id = intval($_SESSION['id']);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  writepartnerprefs($id);
}

// Always keep a preference row ready for the matcher.
$pref = getPartnerPreferences($id, true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Partner Preferences - EliteMatch</title>
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
    <h1><i class="fas fa-sliders-h me-2"></i>Partner Preferences</h1>
    <p>Tell us what you're looking for in your ideal partner</p>
  </div>
</div>

<div class="em-form-page">
  <div class="container" style="max-width: 800px;">
    <form action="" method="POST">
      <div class="em-form-section">
        <h4><i class="fas fa-heart"></i> Basic Preferences</h4>
        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label">Min Age</label>
            <input type="number" name="agemin" class="form-control" value="<?php echo $pref ? h($pref['agemin']) : '18'; ?>" min="18" max="70">
          </div>
          <div class="col-md-3">
            <label class="form-label">Max Age</label>
            <input type="number" name="agemax" class="form-control" value="<?php echo $pref ? h($pref['agemax']) : '40'; ?>" min="18" max="70">
          </div>
          <div class="col-md-3">
            <label class="form-label">Height (cm)</label>
            <input type="number" name="height" class="form-control" value="<?php echo $pref ? h($pref['height']) : ''; ?>" placeholder="e.g. 170">
          </div>
          <div class="col-md-3">
            <label class="form-label">Marital Status</label>
            <select name="maritalstatus" class="form-select">
              <option value="Single" <?php echo ($pref && $pref['maritalstatus']=='Single')?'selected':''; ?>>Single</option>
              <option value="Divorced" <?php echo ($pref && $pref['maritalstatus']=='Divorced')?'selected':''; ?>>Divorced</option>
              <option value="Widowed" <?php echo ($pref && $pref['maritalstatus']=='Widowed')?'selected':''; ?>>Widowed</option>
              <option value="Any" <?php echo ($pref && $pref['maritalstatus']=='Any')?'selected':''; ?>>Any</option>
            </select>
          </div>
        </div>
      </div>

      <div class="em-form-section">
        <h4><i class="fas fa-pray"></i> Religion & Culture</h4>
        <div class="row g-3">
          <div class="col-md-4"><label class="form-label">Religion</label><select name="religion" class="form-select"><option value="" <?php echo ($pref && $pref['religion']=='')?'selected':''; ?>>Any</option><option value="Hindu" <?php echo ($pref && $pref['religion']=='Hindu')?'selected':''; ?>>Hindu</option><option value="Muslim" <?php echo ($pref && $pref['religion']=='Muslim')?'selected':''; ?>>Muslim</option><option value="Christian" <?php echo ($pref && $pref['religion']=='Christian')?'selected':''; ?>>Christian</option><option value="Sikh" <?php echo ($pref && $pref['religion']=='Sikh')?'selected':''; ?>>Sikh</option><option value="Jain" <?php echo ($pref && $pref['religion']=='Jain')?'selected':''; ?>>Jain</option></select></div>
          <div class="col-md-4"><label class="form-label">Caste</label><select name="caste" class="form-select"><option value="" <?php echo ($pref && $pref['caste']=='')?'selected':''; ?>>Any</option><option value="General" <?php echo ($pref && $pref['caste']=='General')?'selected':''; ?>>General</option><option value="OBC" <?php echo ($pref && $pref['caste']=='OBC')?'selected':''; ?>>OBC</option><option value="SC" <?php echo ($pref && $pref['caste']=='SC')?'selected':''; ?>>SC</option><option value="ST" <?php echo ($pref && $pref['caste']=='ST')?'selected':''; ?>>ST</option></select></div>
          <div class="col-md-4"><label class="form-label">Mother Tongue</label><select name="mothertounge" class="form-select"><option value="" <?php echo ($pref && $pref['mothertounge']=='')?'selected':''; ?>>Any</option><option value="Hindi" <?php echo ($pref && $pref['mothertounge']=='Hindi')?'selected':''; ?>>Hindi</option><option value="Marathi" <?php echo ($pref && $pref['mothertounge']=='Marathi')?'selected':''; ?>>Marathi</option><option value="English" <?php echo ($pref && $pref['mothertounge']=='English')?'selected':''; ?>>English</option><option value="Tamil" <?php echo ($pref && $pref['mothertounge']=='Tamil')?'selected':''; ?>>Tamil</option><option value="Telugu" <?php echo ($pref && $pref['mothertounge']=='Telugu')?'selected':''; ?>>Telugu</option><option value="Malayalam" <?php echo ($pref && $pref['mothertounge']=='Malayalam')?'selected':''; ?>>Malayalam</option></select></div>
        </div>
      </div>

      <div class="em-form-section">
        <h4><i class="fas fa-briefcase"></i> Lifestyle & Career</h4>
        <div class="row g-3">
          <div class="col-md-3"><label class="form-label">Complexion</label><select name="colour" class="form-select"><option value="" <?php echo ($pref && $pref['complexion']=='')?'selected':''; ?>>Any</option><option value="Fair" <?php echo ($pref && $pref['complexion']=='Fair')?'selected':''; ?>>Fair</option><option value="Wheatish" <?php echo ($pref && $pref['complexion']=='Wheatish')?'selected':''; ?>>Wheatish</option><option value="Dark" <?php echo ($pref && $pref['complexion']=='Dark')?'selected':''; ?>>Dark</option></select></div>
          <div class="col-md-3"><label class="form-label">Diet</label><select name="diet" class="form-select"><option value="" <?php echo ($pref && $pref['diet']=='')?'selected':''; ?>>Any</option><option value="Veg" <?php echo ($pref && $pref['diet']=='Veg')?'selected':''; ?>>Vegetarian</option><option value="Non Veg" <?php echo ($pref && $pref['diet']=='Non Veg')?'selected':''; ?>>Non-Veg</option></select></div>
          <div class="col-md-3"><label class="form-label">Education</label><select name="education" class="form-select"><option value="" <?php echo ($pref && $pref['education']=='')?'selected':''; ?>>Any</option><option value="10th" <?php echo ($pref && $pref['education']=='10th')?'selected':''; ?>>10th</option><option value="12th" <?php echo ($pref && $pref['education']=='12th')?'selected':''; ?>>12th</option><option value="Degree" <?php echo ($pref && $pref['education']=='Degree')?'selected':''; ?>>Degree</option><option value="PG" <?php echo ($pref && $pref['education']=='PG')?'selected':''; ?>>PG</option><option value="Doctorate" <?php echo ($pref && $pref['education']=='Doctorate')?'selected':''; ?>>Doctorate</option></select></div>
          <div class="col-md-3"><label class="form-label">Country</label><select name="country" class="form-select"><option value="" <?php echo ($pref && $pref['country']=='')?'selected':''; ?>>Any</option><option value="India" <?php echo ($pref && $pref['country']=='India')?'selected':''; ?>>India</option><option value="USA" <?php echo ($pref && $pref['country']=='USA')?'selected':''; ?>>USA</option><option value="UK" <?php echo ($pref && $pref['country']=='UK')?'selected':''; ?>>UK</option><option value="UAE" <?php echo ($pref && $pref['country']=='UAE')?'selected':''; ?>>UAE</option><option value="Canada" <?php echo ($pref && $pref['country']=='Canada')?'selected':''; ?>>Canada</option></select></div>
          <div class="col-12">
            <label class="form-label">Occupation Preference</label>
            <input type="text" name="occupation" class="form-control" value="<?php echo $pref ? h($pref['occupation']) : ''; ?>" placeholder="e.g. Engineer, Doctor, Any">
          </div>
          <div class="col-12">
            <label class="form-label">Additional Description</label>
            <textarea name="descr" class="form-control" rows="4" placeholder="Describe your ideal partner..."><?php echo $pref ? h($pref['descr']) : ''; ?></textarea>
          </div>
        </div>
      </div>

      <div class="text-center">
        <button type="submit" class="btn-submit"><i class="fas fa-save me-2"></i>Save Preferences</button>
        <a href="userhome.php?id=<?php echo $id; ?>" class="btn-submit" style="background: var(--gray-600); box-shadow: none; margin-left: 10px;"><i class="fas fa-arrow-left me-2"></i>Dashboard</a>
      </div>
    </form>
  </div>
</div>

<?php include_once("footer.php");?>
