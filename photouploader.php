<?php include_once("includes/basic_includes.php");?>
<?php include_once("functions.php"); ?>
<?php
if(!isloggedin()){
  header("location:login.php");
  exit;
}
$id = intval($_SESSION['id']);
if ($_SERVER['REQUEST_METHOD'] == 'POST'){ uploadphoto($id); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Upload Photos - EliteMatch</title>
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
    <h1><i class="fas fa-camera me-2"></i>Upload Photos</h1>
    <p>Add up to 4 photos to your profile</p>
  </div>
</div>

<div class="em-form-page">
  <div class="container" style="max-width: 700px;">
    <div class="em-form-section">
      <h4><i class="fas fa-images"></i> Your Photos</h4>
      <p style="color:var(--gray-500); font-size:0.9rem; margin-bottom:1.5rem;">Upload clear, recent photos of yourself. Recommended size: 300x250 pixels or larger. Supported formats: JPG, PNG, GIF, WEBP.</p>
      
      <form action="" method="post" enctype="multipart/form-data">
        <div class="row g-4">
          <div class="col-md-6">
            <div class="em-upload-area" onclick="document.getElementById('pic1').click()">
              <i class="fas fa-cloud-upload-alt"></i>
              <h5>Photo 1 (Main)</h5>
              <p>Click to choose file</p>
              <input type="file" id="pic1" name="pic1" accept="image/*" style="display:none" onchange="previewImg(this, 'preview1')">
              <img id="preview1" src="" alt="" style="max-width:100%; max-height:150px; margin-top:10px; border-radius:8px; display:none;">
            </div>
          </div>
          <div class="col-md-6">
            <div class="em-upload-area" onclick="document.getElementById('pic2').click()">
              <i class="fas fa-cloud-upload-alt"></i>
              <h5>Photo 2</h5>
              <p>Click to choose file</p>
              <input type="file" id="pic2" name="pic2" accept="image/*" style="display:none" onchange="previewImg(this, 'preview2')">
              <img id="preview2" src="" alt="" style="max-width:100%; max-height:150px; margin-top:10px; border-radius:8px; display:none;">
            </div>
          </div>
          <div class="col-md-6">
            <div class="em-upload-area" onclick="document.getElementById('pic3').click()">
              <i class="fas fa-cloud-upload-alt"></i>
              <h5>Photo 3</h5>
              <p>Click to choose file</p>
              <input type="file" id="pic3" name="pic3" accept="image/*" style="display:none" onchange="previewImg(this, 'preview3')">
              <img id="preview3" src="" alt="" style="max-width:100%; max-height:150px; margin-top:10px; border-radius:8px; display:none;">
            </div>
          </div>
          <div class="col-md-6">
            <div class="em-upload-area" onclick="document.getElementById('pic4').click()">
              <i class="fas fa-cloud-upload-alt"></i>
              <h5>Photo 4</h5>
              <p>Click to choose file</p>
              <input type="file" id="pic4" name="pic4" accept="image/*" style="display:none" onchange="previewImg(this, 'preview4')">
              <img id="preview4" src="" alt="" style="max-width:100%; max-height:150px; margin-top:10px; border-radius:8px; display:none;">
            </div>
          </div>
        </div>
        <div class="text-center mt-4">
          <button type="submit" class="btn-submit"><i class="fas fa-upload me-2"></i>Upload Photos</button>
          <a href="userhome.php?id=<?php echo $id; ?>" class="btn-submit" style="background: var(--gray-600); box-shadow: none; margin-left: 10px;"><i class="fas fa-arrow-left me-2"></i>Back to Dashboard</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function previewImg(input, previewId) {
  const preview = document.getElementById(previewId);
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      preview.src = e.target.result;
      preview.style.display = 'block';
      input.parentElement.querySelector('i').style.display = 'none';
      input.parentElement.querySelector('h5').textContent = input.files[0].name;
      input.parentElement.querySelector('p').textContent = 'Click to change';
    }
    reader.readAsDataURL(input.files[0]);
  }
}
</script>

<?php include_once("footer.php");?>