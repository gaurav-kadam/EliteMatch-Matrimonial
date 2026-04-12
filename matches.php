<?php include_once("includes/basic_includes.php");?>
<?php include_once("functions.php"); ?>
<?php
if(!isloggedin()){
  header("location:login.php");
  exit;
}

$id = intval($_SESSION['id']);
$flashMessage = popFlashMessage();

$matchesData = getMatchesForUser($id);
$profile = $matchesData['profile'];
$pref = $matchesData['preferences'];
$matches = $matchesData['matches'];
$interactionStates = getInteractionStatesForProfiles($id, array_column($matches, 'cust_id'));
$locationSummary = 'Anywhere';
if(!empty($profile) && !empty($profile['state'])){
  $locationSummary = $profile['state'];
} elseif(!empty($pref) && !empty($pref['country'])){
  $locationSummary = $pref['country'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>My Matches - EliteMatch</title>
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
    <h1><i class="fas fa-heart-circle-check me-2"></i>Your Best Matches</h1>
    <p>Profiles shortlisted using your partner preferences and compatibility score</p>
  </div>
</div>

<div class="em-search-page">
  <div class="container">
    <?php if($flashMessage): ?>
      <div class="alert alert-<?php echo h($flashMessage['type']); ?> mb-4"><i class="fas fa-circle-info me-2"></i><?php echo h($flashMessage['message']); ?></div>
    <?php endif; ?>

    <?php if($matchesData['status'] === 'profile_missing'): ?>
    <div class="em-form-section text-center" style="max-width:760px; margin:0 auto;">
      <i class="fas fa-user-plus" style="font-size:3rem; color:var(--primary); margin-bottom:1rem;"></i>
      <h3 style="color:var(--dark);">Complete your profile to unlock matches</h3>
      <p style="color:var(--gray-500);">We need your profile details before we can compare your preferences against active members.</p>
      <a href="create_profile.php?id=<?php echo $id; ?>" class="btn-submit"><i class="fas fa-edit me-2"></i>Create Profile</a>
    </div>
    <?php else: ?>

    <div class="row g-4 mb-4">
      <div class="col-lg-3 col-md-6">
        <div class="em-form-section h-100 mb-0">
          <small class="text-uppercase" style="color:var(--gray-500); font-weight:700;">Age Range</small>
          <h4 style="border:none; margin:0.45rem 0 0; padding:0; color:var(--dark);"><?php echo h($pref['agemin']); ?> - <?php echo h($pref['agemax']); ?> yrs</h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="em-form-section h-100 mb-0">
          <small class="text-uppercase" style="color:var(--gray-500); font-weight:700;">Religion</small>
          <h4 style="border:none; margin:0.45rem 0 0; padding:0; color:var(--dark);"><?php echo !empty($pref['religion']) ? h($pref['religion']) : 'Any'; ?></h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="em-form-section h-100 mb-0">
          <small class="text-uppercase" style="color:var(--gray-500); font-weight:700;">Location</small>
          <h4 style="border:none; margin:0.45rem 0 0; padding:0; color:var(--dark);"><?php echo h($locationSummary); ?></h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="em-form-section h-100 mb-0">
          <small class="text-uppercase" style="color:var(--gray-500); font-weight:700;">Preferred Height</small>
          <h4 style="border:none; margin:0.45rem 0 0; padding:0; color:var(--dark);"><?php echo !empty($pref['height']) ? h($pref['height']) . ' cm' : 'Any'; ?></h4>
        </div>
      </div>
    </div>

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
      <div>
        <h4 style="margin:0; color:var(--dark); font-weight:700;"><i class="fas fa-users me-2" style="color:var(--primary);"></i><?php echo count($matches); ?> matching profiles</h4>
        <p style="margin:0.4rem 0 0; color:var(--gray-500);">Results are sorted by highest compatibility percentage first.</p>
      </div>
      <div class="d-flex gap-2">
        <a href="partner_preference.php?id=<?php echo $id; ?>" class="btn-submit" style="box-shadow:none;"><i class="fas fa-sliders-h me-2"></i>Update Preferences</a>
        <a href="userhome.php?id=<?php echo $id; ?>" class="btn-submit" style="background:var(--gray-600); box-shadow:none;"><i class="fas fa-arrow-left me-2"></i>Dashboard</a>
      </div>
    </div>

    <?php if(empty($matches)): ?>
    <div class="em-form-section text-center">
      <i class="fas fa-heart-crack" style="font-size:3rem; color:var(--gray-300); margin-bottom:1rem;"></i>
      <h3 style="color:var(--dark);">No matches found yet</h3>
      <p style="color:var(--gray-500); max-width:640px; margin:0 auto 1.25rem;">Try widening your age, religion, location, or height preferences. We only show active profiles that fit your current criteria.</p>
      <a href="partner_preference.php?id=<?php echo $id; ?>" class="btn-submit"><i class="fas fa-pen-to-square me-2"></i>Adjust Preferences</a>
    </div>
    <?php else: ?>
    <div class="row g-4">
      <?php foreach($matches as $match): ?>
      <?php
        $matchId = intval($match['cust_id']);
        $matchName = getDisplayName($match);
        $matchPhoto = $match['photo_url'];
        $interactionState = isset($interactionStates[$matchId]) ? $interactionStates[$matchId] : getInteractionState($id, $matchId);
      ?>
      <div class="col-xl-4 col-md-6">
        <div class="em-profile-card">
          <div class="card-img-wrapper">
            <img src="<?php echo $matchPhoto; ?>" alt="<?php echo h($matchName); ?>">
            <div class="card-overlay" style="opacity:1; background:linear-gradient(transparent, rgba(0,0,0,0.75));">
              <span class="em-badge em-badge-success" style="font-size:0.95rem; padding:8px 14px;">
                <i class="fas fa-bolt"></i> Match: <?php echo intval($match['match_percentage']); ?>%
              </span>
            </div>
          </div>
          <div class="card-body">
            <span class="profile-id">ID: EM<?php echo $matchId; ?></span>
            <h5><?php echo h($matchName); ?></h5>
            <p><i class="fas fa-birthday-cake"></i> <?php echo h($match['age']); ?> Years</p>
            <p><i class="fas fa-location-dot"></i> <?php echo h($match['state']) . ', ' . h($match['country']); ?></p>
            <p><i class="fas fa-pray"></i> <?php echo h($match['religion']); ?></p>
            <p><i class="fas fa-graduation-cap"></i> <?php echo h($match['education']); ?></p>
            <p><i class="fas fa-ruler-vertical"></i> <?php echo h($match['height']); ?> cm</p>
          </div>
          <div class="card-footer flex-column align-items-stretch gap-2">
            <a href="view_profile.php?id=<?php echo $matchId; ?>" class="btn-view text-center">View Profile</a>
            <?php if($interactionState['can_chat']): ?>
            <a href="chat.php?user=<?php echo $matchId; ?>" class="btn btn-outline-primary rounded-pill">
              <i class="fas fa-comments me-2"></i>Chat
            </a>
            <?php elseif($interactionState['status'] === 'pending' && $interactionState['direction'] === 'outgoing'): ?>
            <span class="em-badge em-badge-warm justify-content-center"><i class="fas fa-hourglass-half"></i>Interest Sent</span>
            <?php elseif($interactionState['status'] === 'pending' && $interactionState['direction'] === 'incoming'): ?>
            <a href="requests.php" class="btn btn-outline-success rounded-pill">
              <i class="fas fa-envelope-open-text me-2"></i>Respond
            </a>
            <?php elseif($interactionState['status'] === 'rejected' && $interactionState['direction'] === 'outgoing'): ?>
            <span class="em-badge em-badge-warm justify-content-center"><i class="fas fa-ban"></i>Rejected</span>
            <?php else: ?>
            <form action="send_interest.php" method="post" class="d-grid">
              <?php echo renderCsrfField(); ?>
              <input type="hidden" name="profile_id" value="<?php echo $matchId; ?>">
              <input type="hidden" name="redirect_to" value="matches.php">
              <button type="submit" class="btn btn-outline-danger rounded-pill">
                <i class="fas fa-heart me-2"></i>Send Interest
              </button>
            </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
  </div>
</div>

<?php include_once("footer.php");?>
