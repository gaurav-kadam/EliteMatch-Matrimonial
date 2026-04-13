<?php include_once("includes/basic_includes.php");?>
<?php include_once("functions.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>EliteMatch - Find Your Perfect Life Partner</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="EliteMatch - India's most trusted matrimonial platform. Find your perfect life partner from millions of verified profiles.">
<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome 6 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<!-- Custom CSS -->
<link href="css/style.css" rel="stylesheet">
</head>
<body>

<?php include_once("includes/navigation.php");?>

<!-- Hero Section -->
<section class="em-hero">
  <div class="container">
    <div class="em-hero-content">
      <h1>Find Your <span>Perfect Match</span></h1>
      <p>Join millions of happy couples who found their life partner on EliteMatch. Your journey to forever starts here.</p>
      <div>
        <?php if(!isloggedin()): ?>
        <a href="register.php" class="btn-hero">Get Started Free</a>
        <a href="login.php" class="btn-hero btn-hero-outline">Sign In</a>
        <?php else: ?>
        <a href="search.php" class="btn-hero"><i class="fas fa-search me-2"></i>Search Profiles</a>
        <a href="userhome.php?id=<?php echo $_SESSION['id'];?>" class="btn-hero btn-hero-outline">My Dashboard</a>
        <?php endif; ?>
      </div>
      <div class="em-hero-stats">
        <div class="em-hero-stat">
          <h3><?php echo getTotalProfiles(); ?>+</h3>
          <p>Verified Profiles</p>
        </div>
        <div class="em-hero-stat">
          <h3>500+</h3>
          <p>Happy Couples</p>
        </div>
        <div class="em-hero-stat">
          <h3>50+</h3>
          <p>Cities Covered</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Featured Profiles -->
<section class="em-section em-section-light">
  <div class="container">
    <div class="em-section-title">
      <h2>Featured Profiles</h2>
      <p>Discover amazing people waiting to meet someone like you</p>
      <div class="em-divider"><span></span><i class="fas fa-heart"></i><span></span></div>
    </div>
    <div class="row g-4">
      <?php
      $result = getFeaturedProfiles(8);
      if($result){
        while($row = mysqli_fetch_assoc($result)){
          $displayName = getDisplayName($row);
          $name = h($displayName);
          $profileid = intval($row['cust_id']);
          $age = h($row['age']);
          $place = h($row['state']) . ", " . h($row['country']);
          $job = h($row['occupation']);
          $religion = h($row['religion']);
          $pic1 = getProfilePhotoUrl($profileid, $row['pic1'] ?? '', $displayName);
      ?>
      <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="em-profile-card">
          <div class="card-img-wrapper">
            <img src="<?php echo $pic1; ?>" alt="<?php echo $name; ?>">
            <div class="card-overlay">
              <small>Click to view full profile</small>
            </div>
          </div>
          <div class="card-body">
            <span class="profile-id">ID: EM<?php echo $profileid; ?></span>
            <h5><?php echo $name; ?></h5>
            <p><i class="fas fa-birthday-cake"></i> <?php echo $age; ?> Years</p>
            <p><i class="fas fa-map-marker-alt"></i> <?php echo $place; ?></p>
            <p><i class="fas fa-pray"></i> <?php echo $religion; ?></p>
          </div>
          <div class="card-footer">
            <small class="text-muted"><?php echo $job; ?></small>
            <a href="view_profile.php?id=<?php echo $profileid; ?>" class="btn-view">View Profile</a>
          </div>
        </div>
      </div>
      <?php
        }
      }
      ?>
    </div>
  </div>
</section>

<!-- Stats Section -->
<section class="em-stats">
  <div class="container">
    <div class="row">
      <div class="col-md-3 col-6">
        <div class="em-stat-box">
          <i class="fas fa-users"></i>
          <h3><?php echo getTotalProfiles(); ?>+</h3>
          <p>Total Profiles</p>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="em-stat-box">
          <i class="fas fa-heart"></i>
          <h3>500+</h3>
          <p>Successful Matches</p>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="em-stat-box">
          <i class="fas fa-globe-asia"></i>
          <h3>50+</h3>
          <p>Cities</p>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="em-stat-box">
          <i class="fas fa-star"></i>
          <h3>4.8</h3>
          <p>User Rating</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Success Stories -->
<section class="em-section em-section-light">
  <div class="container">
    <div class="em-section-title">
      <h2>Success Stories</h2>
      <p>Real couples who found love on EliteMatch</p>
      <div class="em-divider"><span></span><i class="fas fa-heart"></i><span></span></div>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="em-story-card">
          <div class="story-header">
            <img src="images/7.jpg" alt="Couple" class="story-avatar" onerror="this.src='https://ui-avatars.com/api/?name=R+S&background=8B5CF6&color=fff'">
            <div class="story-meta">
              <h5>Rahul & Sneha</h5>
              <span>Married Dec 2025</span>
            </div>
          </div>
          <p>"We found each other on EliteMatch and instantly connected over shared values and dreams. The platform made it so easy to find someone truly compatible. We're now happily married!"</p>
          <div class="story-date"><i class="fas fa-calendar-alt me-1"></i> December 20, 2024</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="em-story-card">
          <div class="story-header">
            <img src="images/8.jpg" alt="Couple" class="story-avatar" onerror="this.src='https://ui-avatars.com/api/?name=A+P&background=EC4899&color=fff'">
            <div class="story-meta">
              <h5>Amit & Priya</h5>
              <span>Married jan 2026</span>
            </div>
          </div>
          <p>"Thanks to EliteMatch's detailed profiles and smart matching, we discovered we had so much in common. From our first conversation to our wedding, it was a beautiful journey."</p>
          <div class="story-date"><i class="fas fa-calendar-alt me-1"></i> October 15, 2024</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="em-story-card">
          <div class="story-header">
            <img src="images/9.jpg" alt="Couple" class="story-avatar" onerror="this.src='https://ui-avatars.com/api/?name=V+N&background=F59E0B&color=fff'">
            <div class="story-meta">
              <h5>Vikram & Neha</h5>
              <span>Married mar 2026</span>
            </div>
          </div>
          <p>"We're grateful to EliteMatch for bringing us together. The partner preference matching was incredibly accurate. We couldn't be happier with our life together!"</p>
          <div class="story-date"><i class="fas fa-calendar-alt me-1"></i> August 8, 2024</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- How It Works -->
<section class="em-section" style="background: var(--gray-50);">
  <div class="container">
    <div class="em-section-title">
      <h2>How It Works</h2>
      <p>Finding your perfect partner is just 3 steps away</p>
      <div class="em-divider"><span></span><i class="fas fa-heart"></i><span></span></div>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="em-action-card">
          <i class="fas fa-user-plus"></i>
          <h5>Create Profile</h5>
          <p>Register and create your detailed profile with photos and preferences</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="em-action-card">
          <i class="fas fa-search-plus"></i>
          <h5>Find Matches</h5>
          <p>Search and discover compatible profiles using our smart filters</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="em-action-card">
          <i class="fas fa-paper-plane"></i>
          <h5>Express Interest</h5>
          <p>Connect with profiles you like and start your journey together</p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include_once 'footer.php'; ?>
