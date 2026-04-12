<?php include_once("includes/basic_includes.php");?>
<?php include_once("functions.php"); ?>
<?php
if(!isloggedin()){
  redirectTo("login.php");
}

$id = intval($_SESSION['id']);
$flashMessage = popFlashMessage();
$pendingCount = getPendingRequestCount($id);
$acceptedCount = getAcceptedConnectionCount($id);
$pendingRequests = getIncomingInterestRequests($id, "pending");
$acceptedConnections = getAcceptedConnections($id);
$sentRequests = getOutgoingInterestRequests($id, "pending");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Interest Requests - EliteMatch</title>
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
    <h1><i class="fas fa-envelope-open-text me-2"></i>Interest Requests</h1>
    <p>Review requests, accept the right matches, and start chatting with approved members</p>
  </div>
</div>

<div class="em-search-page">
  <div class="container">
    <?php if($flashMessage): ?>
    <div class="alert alert-<?php echo h($flashMessage['type']); ?> mb-4">
      <i class="fas fa-circle-info me-2"></i><?php echo h($flashMessage['message']); ?>
    </div>
    <?php endif; ?>

    <div class="row g-4 mb-4">
      <div class="col-lg-4 col-md-6">
        <div class="em-form-section h-100 mb-0">
          <small class="text-uppercase" style="color:var(--gray-500); font-weight:700;">Pending Requests</small>
          <h4 style="border:none; margin:0.45rem 0 0; padding:0; color:var(--dark);"><?php echo $pendingCount; ?></h4>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="em-form-section h-100 mb-0">
          <small class="text-uppercase" style="color:var(--gray-500); font-weight:700;">Chat Connections</small>
          <h4 style="border:none; margin:0.45rem 0 0; padding:0; color:var(--dark);"><?php echo $acceptedCount; ?></h4>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="em-form-section h-100 mb-0">
          <small class="text-uppercase" style="color:var(--gray-500); font-weight:700;">Requests Sent</small>
          <h4 style="border:none; margin:0.45rem 0 0; padding:0; color:var(--dark);"><?php echo $sentRequests ? mysqli_num_rows($sentRequests) : 0; ?></h4>
        </div>
      </div>
    </div>

    <div class="em-form-section">
      <h4><i class="fas fa-inbox"></i>Incoming Requests</h4>
      <?php if($pendingRequests && mysqli_num_rows($pendingRequests) > 0): ?>
      <div class="row g-4">
        <?php while($request = mysqli_fetch_assoc($pendingRequests)): ?>
        <?php
          $senderId = intval($request['sender_id']);
          $senderName = getDisplayName($request);
          $senderPhoto = getProfilePhotoUrl($senderId, $request['pic1'] ?? '', $senderName);
        ?>
        <div class="col-lg-6">
          <div class="em-profile-card">
            <div class="row g-0 align-items-center">
              <div class="col-md-4">
                <img src="<?php echo $senderPhoto; ?>" alt="<?php echo h($senderName); ?>" style="width:100%; height:100%; min-height:230px; object-fit:cover;">
              </div>
              <div class="col-md-8">
                <div class="card-body">
                  <span class="profile-id">Request #<?php echo intval($request['interest_id']); ?></span>
                  <h5><?php echo h($senderName); ?></h5>
                  <p><i class="fas fa-birthday-cake"></i> <?php echo h($request['age']); ?> Years</p>
                  <p><i class="fas fa-location-dot"></i> <?php echo h($request['state']) . ', ' . h($request['country']); ?></p>
                  <p><i class="fas fa-pray"></i> <?php echo h($request['religion']); ?></p>
                  <p><i class="fas fa-clock"></i> Received on <?php echo h(date("d M Y, h:i A", strtotime($request['created_at']))); ?></p>
                </div>
                <div class="card-footer flex-wrap gap-2 justify-content-start">
                  <a href="view_profile.php?id=<?php echo $senderId; ?>" class="btn-view">View Profile</a>
                  <form action="accept.php" method="post" class="d-inline">
                    <?php echo renderCsrfField(); ?>
                    <input type="hidden" name="request_id" value="<?php echo intval($request['interest_id']); ?>">
                    <input type="hidden" name="redirect_to" value="requests.php">
                    <button type="submit" class="btn btn-success rounded-pill px-3">
                      <i class="fas fa-check me-2"></i>Accept
                    </button>
                  </form>
                  <form action="reject.php" method="post" class="d-inline">
                    <?php echo renderCsrfField(); ?>
                    <input type="hidden" name="request_id" value="<?php echo intval($request['interest_id']); ?>">
                    <input type="hidden" name="redirect_to" value="requests.php">
                    <button type="submit" class="btn btn-outline-danger rounded-pill px-3">
                      <i class="fas fa-xmark me-2"></i>Reject
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
      <?php else: ?>
      <div class="text-center py-4">
        <i class="fas fa-envelope" style="font-size:3rem; color:var(--gray-300); margin-bottom:1rem;"></i>
        <h5 style="color:var(--gray-500);">No pending requests</h5>
        <p style="color:var(--gray-400);">New interest requests will appear here.</p>
      </div>
      <?php endif; ?>
    </div>

    <div class="em-form-section" id="connections">
      <h4><i class="fas fa-comments"></i>Accepted Connections</h4>
      <?php if($acceptedConnections && mysqli_num_rows($acceptedConnections) > 0): ?>
      <div class="row g-4">
        <?php while($connection = mysqli_fetch_assoc($acceptedConnections)): ?>
        <?php
          $chatUserId = intval($connection['chat_user_id']);
          $chatName = getDisplayName($connection);
          $chatPhoto = getProfilePhotoUrl($chatUserId, $connection['pic1'] ?? '', $chatName);
        ?>
        <div class="col-lg-4 col-md-6">
          <div class="em-profile-card">
            <div class="card-img-wrapper" style="height:220px;">
              <img src="<?php echo $chatPhoto; ?>" alt="<?php echo h($chatName); ?>">
            </div>
            <div class="card-body">
              <span class="profile-id">Connected</span>
              <h5><?php echo h($chatName); ?></h5>
              <p><i class="fas fa-birthday-cake"></i> <?php echo h($connection['age']); ?> Years</p>
              <p><i class="fas fa-location-dot"></i> <?php echo h($connection['state']) . ', ' . h($connection['country']); ?></p>
              <p><i class="fas fa-check-circle"></i> Accepted on <?php echo h(date("d M Y", strtotime($connection['created_at']))); ?></p>
            </div>
            <div class="card-footer">
              <a href="view_profile.php?id=<?php echo $chatUserId; ?>" class="btn-view">Profile</a>
              <a href="chat.php?user=<?php echo $chatUserId; ?>" class="btn btn-outline-primary rounded-pill px-3">
                <i class="fas fa-comment-dots me-2"></i>Chat
              </a>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
      <?php else: ?>
      <div class="text-center py-4">
        <i class="fas fa-user-group" style="font-size:3rem; color:var(--gray-300); margin-bottom:1rem;"></i>
        <h5 style="color:var(--gray-500);">No accepted connections yet</h5>
        <p style="color:var(--gray-400);">Accepted requests will unlock chat here.</p>
      </div>
      <?php endif; ?>
    </div>

    <div class="em-form-section">
      <h4><i class="fas fa-paper-plane"></i>Requests You Sent</h4>
      <?php if($sentRequests && mysqli_num_rows($sentRequests) > 0): ?>
      <div class="row g-4">
        <?php while($request = mysqli_fetch_assoc($sentRequests)): ?>
        <?php
          $receiverId = intval($request['receiver_id']);
          $receiverName = getDisplayName($request);
          $receiverPhoto = getProfilePhotoUrl($receiverId, $request['pic1'] ?? '', $receiverName);
        ?>
        <div class="col-lg-4 col-md-6">
          <div class="em-profile-card">
            <div class="card-img-wrapper" style="height:220px;">
              <img src="<?php echo $receiverPhoto; ?>" alt="<?php echo h($receiverName); ?>">
            </div>
            <div class="card-body">
              <span class="profile-id">Pending</span>
              <h5><?php echo h($receiverName); ?></h5>
              <p><i class="fas fa-birthday-cake"></i> <?php echo h($request['age']); ?> Years</p>
              <p><i class="fas fa-location-dot"></i> <?php echo h($request['state']) . ', ' . h($request['country']); ?></p>
              <p><i class="fas fa-clock"></i> Sent on <?php echo h(date("d M Y, h:i A", strtotime($request['created_at']))); ?></p>
            </div>
            <div class="card-footer">
              <a href="view_profile.php?id=<?php echo $receiverId; ?>" class="btn-view">View Profile</a>
              <span class="em-badge em-badge-warm"><i class="fas fa-hourglass-half"></i>Awaiting Response</span>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
      <?php else: ?>
      <div class="text-center py-4">
        <i class="fas fa-paper-plane" style="font-size:3rem; color:var(--gray-300); margin-bottom:1rem;"></i>
        <h5 style="color:var(--gray-500);">No pending sent requests</h5>
        <p style="color:var(--gray-400);">Interest requests you send will be listed here.</p>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include_once("footer.php");?>
