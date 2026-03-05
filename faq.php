<?php include_once("includes/basic_includes.php");?>
<?php include_once("functions.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>FAQ - EliteMatch</title>
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
    <h1><i class="fas fa-question-circle me-2"></i>Frequently Asked Questions</h1>
    <p>Find answers to common questions about EliteMatch</p>
  </div>
</div>

<section class="em-section" style="background:var(--gray-50);">
  <div class="container" style="max-width:800px;">
    <div class="em-faq">
      <div class="accordion" id="faqAccordion">
        <div class="accordion-item">
          <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">How do I register on EliteMatch?</button></h2>
          <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion"><div class="accordion-body">Simply click the "Register Free" button on the homepage. Fill in your basic details like username, email, password, date of birth, and gender. Once registered, you can login and create your detailed profile with personal information, photos, and partner preferences.</div></div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">Is EliteMatch free to use?</button></h2>
          <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Yes, creating a profile, searching for matches, and viewing profiles is completely free. You can also express interest in profiles at no cost. We believe everyone deserves a chance to find their perfect partner.</div></div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">How do I search for profiles?</button></h2>
          <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">You can search profiles using our advanced filters including age, religion, location, education, and more. You can also search by a specific Profile ID if you know it. Go to the Search page from the navigation menu to get started.</div></div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">What is "Express Interest"?</button></h2>
          <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Express Interest is a feature that lets you show your interest in another profile. When you click the "Express Interest" button, the other person will be notified. This helps initiate communication between compatible profiles.</div></div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">How can I upload my photos?</button></h2>
          <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">After logging in, go to your Dashboard and click "Upload Photos". You can upload up to 4 photos in JPG, PNG, or GIF format. Clear, recent photos of yourself work best and help you get more responses from potential matches.</div></div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">Is my personal information safe?</button></h2>
          <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Absolutely. We take privacy very seriously. Your personal information is stored securely with encrypted passwords. We never share your contact details with anyone without your explicit consent. Read our Privacy Policy for more details.</div></div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq7">How do I contact support?</button></h2>
          <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">You can reach our support team through our Contact page, email us at support@elitematch.com, or call us at +91 98765 43210. Our support team is available 24/7 to assist you with any queries or concerns.</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include_once("footer.php");?>
