<?php include_once("includes/basic_includes.php");?>
<?php include_once("functions.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Privacy Policy - EliteMatch</title>
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
    <h1><i class="fas fa-shield-alt me-2"></i>Privacy Policy</h1>
    <p>Last updated: <?php echo date('F Y'); ?></p>
  </div>
</div>

<section class="em-section" style="background:var(--gray-50);">
  <div class="container" style="max-width:800px;">
    <div class="em-form-section">
      <h4><i class="fas fa-database"></i> Information We Collect</h4>
      <p style="color:var(--gray-600); line-height:1.8;">We collect personal information that you voluntarily provide when registering, including your name, email address, date of birth, gender, photographs, and profile details such as religion, education, and occupation. This information is used to create your profile and match you with compatible partners.</p>
    </div>
    <div class="em-form-section">
      <h4><i class="fas fa-lock"></i> How We Protect Your Data</h4>
      <p style="color:var(--gray-600); line-height:1.8;">We implement industry-standard security measures including password encryption, secure database connections, and input validation to protect your personal information. Your password is hashed and never stored in plain text. We regularly update our security practices to stay ahead of potential threats.</p>
    </div>
    <div class="em-form-section">
      <h4><i class="fas fa-share-alt"></i> Information Sharing</h4>
      <p style="color:var(--gray-600); line-height:1.8;">We do not sell, trade, or rent your personal information to third parties. Your profile information is visible to registered members of EliteMatch only. Contact details are shared only with your explicit consent. We may share anonymized, aggregated data for analytical purposes.</p>
    </div>
    <div class="em-form-section">
      <h4><i class="fas fa-user-cog"></i> Your Rights</h4>
      <p style="color:var(--gray-600); line-height:1.8;">You have the right to access, update, or delete your personal information at any time through your profile settings. You can deactivate your account by contacting our support team. Upon account deletion, your personal data will be removed from our active databases.</p>
    </div>
  </div>
</section>

<?php include_once("footer.php");?>
