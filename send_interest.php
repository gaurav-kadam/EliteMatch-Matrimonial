<?php include_once("includes/basic_includes.php");?>
<?php include_once("functions.php"); ?>
<?php
if(!isloggedin()){
  redirectTo("login.php");
}

$id = intval($_SESSION['id']);
$profileId = postInt("profile_id", 0, 1);
$defaultRedirect = $profileId > 0 ? "view_profile.php?id=" . $profileId : "userhome.php?id=" . $id;
$redirectPath = getSafeRedirectPath(postString("redirect_to"), $defaultRedirect);

if($_SERVER["REQUEST_METHOD"] !== "POST"){
  redirectTo($redirectPath);
}

if(!validateCSRFToken(postString("csrf_token"))){
  setFlashMessage("danger", "Your session expired. Please try again.");
  redirectTo($redirectPath);
}

$result = sendInterestRequest($id, $profileId);

if($result === "success"){
  setFlashMessage("success", "Interest request sent successfully.");
} elseif($result === "already_sent"){
  setFlashMessage("danger", "You have already sent an interest request to this profile.");
} elseif($result === "incoming_request_exists"){
  setFlashMessage("success", "This member has already sent you a request. Review it from your requests page.");
} elseif($result === "already_connected"){
  setFlashMessage("success", "You are already connected with this member. Chat is available.");
} elseif($result === "profile_required"){
  setFlashMessage("danger", "Please complete your profile before sending interest requests.");
} elseif($result === "not_found"){
  setFlashMessage("danger", "This profile is not available.");
} else {
  setFlashMessage("danger", "Unable to send interest right now.");
}

redirectTo($redirectPath);
