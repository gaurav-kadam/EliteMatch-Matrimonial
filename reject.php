<?php include_once("includes/basic_includes.php");?>
<?php include_once("functions.php"); ?>
<?php
if(!isloggedin()){
  redirectTo("login.php");
}

$id = intval($_SESSION['id']);
$requestId = postInt("request_id", 0, 1);
$redirectPath = getSafeRedirectPath(postString("redirect_to"), "requests.php");

if($_SERVER["REQUEST_METHOD"] !== "POST"){
  redirectTo($redirectPath);
}

if(!validateCSRFToken(postString("csrf_token"))){
  setFlashMessage("danger", "Your session expired. Please try again.");
  redirectTo($redirectPath);
}

if($requestId <= 0){
  setFlashMessage("danger", "Invalid interest request selected.");
  redirectTo($redirectPath);
}

$result = updateInterestRequestStatus($requestId, $id, "rejected");

if($result === "rejected"){
  setFlashMessage("success", "Interest request rejected.");
} elseif($result === "already_processed"){
  setFlashMessage("danger", "This request has already been processed.");
} elseif($result === "not_found"){
  setFlashMessage("danger", "Request not found or you are not allowed to reject it.");
} else {
  setFlashMessage("danger", "Unable to reject this request.");
}

redirectTo($redirectPath);
