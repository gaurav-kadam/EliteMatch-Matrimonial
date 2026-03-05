<?php include_once("includes/basic_includes.php");?>
<?php include_once("functions.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Contact Us - EliteMatch</title>
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
    <h1><i class="fas fa-headset me-2"></i>Contact Us</h1>
    <p>We're here to help you find your perfect match</p>
  </div>
</div>

<div class="em-contact-page">
  <div class="container">
    <!-- Contact Cards -->
    <div class="row g-4 mb-5">
      <div class="col-md-4">
        <div class="em-contact-card">
          <i class="fas fa-map-marker-alt"></i>
          <h5>Our Office</h5>
          <p>123 Business Park, Andheri East,<br>Mumbai, Maharashtra 400069</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="em-contact-card">
          <i class="fas fa-phone-alt"></i>
          <h5>Call Us</h5>
          <p>+91 98765 43210<br>+91 22 2345 6789<br><small>(Mon-Sat, 9am-8pm)</small></p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="em-contact-card">
          <i class="fas fa-envelope"></i>
          <h5>Email Us</h5>
          <p>support@elitematch.com<br>help@elitematch.com<br><small>(24/7 Support)</small></p>
        </div>
      </div>
    </div>

    <!-- Contact Form -->
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="em-form-section">
          <h4><i class="fas fa-paper-plane"></i> Send us a Message</h4>
          <form id="contact-form">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Your Name</label>
                <input type="text" class="form-control" placeholder="John Doe" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Your Email</label>
                <input type="email" class="form-control" placeholder="john@example.com" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Phone Number</label>
                <input type="tel" class="form-control" placeholder="+91 98765 43210">
              </div>
              <div class="col-md-6">
                <label class="form-label">Subject</label>
                <select class="form-select">
                  <option value="">Select a topic</option>
                  <option>Account Issues</option>
                  <option>Profile Help</option>
                  <option>Payment Query</option>
                  <option>Report a Problem</option>
                  <option>General Inquiry</option>
                </select>
              </div>
              <div class="col-12">
                <label class="form-label">Message</label>
                <textarea class="form-control" rows="5" placeholder="Tell us how we can help you..." required></textarea>
              </div>
              <div class="col-12 text-center">
                <button type="submit" class="btn-submit"><i class="fas fa-paper-plane me-2"></i>Send Message</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once("footer.php");?>
