<?php include_once("includes/basic_includes.php");?>
<?php include_once("functions.php"); ?>
<?php
if(!isloggedin()){
  redirectTo("login.php");
}

$id = intval($_SESSION['id']);
$otherId = isset($_GET['user']) ? intval($_GET['user']) : 0;
$flashMessage = popFlashMessage();
$otherProfile = $otherId > 0 ? getCustomerProfile($otherId, true) : null;
$canChat = $otherProfile ? canUsersChat($id, $otherId) : false;

if($_SERVER["REQUEST_METHOD"] === "POST"){
  $redirectPath = "chat.php?user=" . $otherId;

  if(!validateCSRFToken(postString("csrf_token"))){
    setFlashMessage("danger", "Your session expired. Please try again.");
    redirectTo($redirectPath);
  }

  $sendResult = sendChatMessage($id, $otherId, postString("message"));

  if($sendResult === "success"){
    redirectTo($redirectPath);
  }

  if($sendResult === "too_long"){
    setFlashMessage("danger", "Message is too long. Please keep it under 2000 characters.");
  } elseif($sendResult === "not_allowed"){
    setFlashMessage("danger", "Chat is not allowed until the request is accepted.");
  } else {
    setFlashMessage("danger", "Unable to send your message right now.");
  }

  redirectTo($redirectPath);
}

$messages = ($otherProfile && $canChat) ? getConversationMessages($id, $otherId) : false;
$chatName = $otherProfile ? getDisplayName($otherProfile) : "Member";
$chatPhoto = $otherProfile ? getProfilePhotoUrl($otherId, $otherProfile['pic1'] ?? '', $chatName) : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Chat - EliteMatch</title>
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
    <h1><i class="fas fa-comments me-2"></i>Private Chat</h1>
    <p>Secure conversation is enabled only after an interest request is accepted</p>
  </div>
</div>

<div class="em-search-page">
  <div class="container">
    <?php if($flashMessage): ?>
    <div class="alert alert-<?php echo h($flashMessage['type']); ?> mb-4">
      <i class="fas fa-circle-info me-2"></i><?php echo h($flashMessage['message']); ?>
    </div>
    <?php endif; ?>

    <?php if(!$otherProfile): ?>
    <div class="em-form-section text-center" style="max-width:760px; margin:0 auto;">
      <i class="fas fa-user-slash" style="font-size:3rem; color:var(--gray-300); margin-bottom:1rem;"></i>
      <h3 style="color:var(--dark);">Chat partner not found</h3>
      <p style="color:var(--gray-500);">The selected profile is not available.</p>
      <a href="requests.php" class="btn-submit"><i class="fas fa-arrow-left me-2"></i>Back to Requests</a>
    </div>
    <?php else: ?>
    <div class="row g-4">
      <div class="col-lg-4">
        <div class="em-detail-card">
          <div class="card-header-custom"><i class="fas fa-user"></i>Connection Details</div>
          <div class="card-body-custom text-center">
            <img src="<?php echo $chatPhoto; ?>" alt="<?php echo h($chatName); ?>" style="width:120px; height:120px; object-fit:cover; border-radius:50%; border:4px solid rgba(139,92,246,0.18);">
            <h4 class="mt-3 mb-1" style="color:var(--dark);"><?php echo h($chatName); ?></h4>
            <p style="color:var(--gray-500);" class="mb-1"><?php echo h($otherProfile['age']); ?> Years</p>
            <p style="color:var(--gray-500);" class="mb-3"><?php echo h($otherProfile['state']) . ', ' . h($otherProfile['country']); ?></p>
            <div class="d-grid gap-2">
              <a href="view_profile.php?id=<?php echo $otherId; ?>" class="btn-view text-center">View Profile</a>
              <a href="requests.php" class="btn btn-outline-secondary rounded-pill"><i class="fas fa-arrow-left me-2"></i>Back to Requests</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="em-detail-card">
          <div class="card-header-custom"><i class="fas fa-comment-dots"></i>Conversation</div>
          <div class="card-body-custom">
            <?php if(!$canChat): ?>
            <div class="alert alert-danger mb-0">
              <i class="fas fa-lock me-2"></i>Chat not allowed until request is accepted.
            </div>
            <?php else: ?>
            <div class="border rounded-4 p-3 mb-3" style="background:var(--gray-50); min-height:420px; max-height:520px; overflow-y:auto;">
              <?php if($messages && mysqli_num_rows($messages) > 0): ?>
                <?php while($message = mysqli_fetch_assoc($messages)): ?>
                <?php $isMine = intval($message['sender_id']) === $id; ?>
                <div class="d-flex mb-3 <?php echo $isMine ? 'justify-content-end' : 'justify-content-start'; ?>">
                  <div style="max-width:75%; background:<?php echo $isMine ? 'linear-gradient(135deg, #8B5CF6, #EC4899)' : '#FFFFFF'; ?>; color:<?php echo $isMine ? '#FFFFFF' : 'var(--gray-800)'; ?>; border-radius:18px; padding:12px 16px; box-shadow:var(--shadow-sm);">
                    <div style="white-space:pre-wrap; word-break:break-word;"><?php echo nl2br(h($message['message'])); ?></div>
                    <div class="mt-2" style="font-size:0.78rem; opacity:0.8; text-align:<?php echo $isMine ? 'right' : 'left'; ?>;">
                      <?php echo h(date("d M Y, h:i A", strtotime($message['created_at']))); ?>
                    </div>
                  </div>
                </div>
                <?php endwhile; ?>
              <?php else: ?>
              <div class="text-center py-5">
                <i class="fas fa-comments" style="font-size:3rem; color:var(--gray-300); margin-bottom:1rem;"></i>
                <h5 style="color:var(--gray-500);">No messages yet</h5>
                <p style="color:var(--gray-400);">Start the conversation with a thoughtful message.</p>
              </div>
              <?php endif; ?>
            </div>

            <form method="post">
              <?php echo renderCsrfField(); ?>
              <div class="mb-3">
                <label class="form-label">Message</label>
                <textarea name="message" class="form-control" rows="4" maxlength="2000" placeholder="Write your message here..." required></textarea>
              </div>
              <div class="d-flex justify-content-end">
                <button type="submit" class="btn-submit"><i class="fas fa-paper-plane me-2"></i>Send Message</button>
              </div>
            </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>

<?php include_once("footer.php");?>
