<?php
// Sidebar widget for matching profiles
$isMember = isloggedin();
$matchSource = $isMember ? getMatchesForUser((int) $_SESSION['id'], 5) : array('matches' => array());
?>
<div class="em-detail-card">
  <div class="card-header-custom"><i class="fas fa-star"></i> Top Matches</div>
  <div class="card-body-custom">
    <?php
    $resm = !empty($matchSource['matches']) ? $matchSource['matches'] : array();
    if(!empty($resm)){
      foreach($resm as $rm){
        $rmId = intval($rm['cust_id']);
        $rmPic = getProfilePhotoUrl($rmId, $rm['pic1'] ?? '', getDisplayName($rm));
    ?>
    <a href="view_profile.php?id=<?php echo $rmId; ?>" style="display:flex; align-items:center; gap:12px; padding:10px; border-radius:8px; margin-bottom:6px; text-decoration:none; color:inherit; transition:all 0.3s ease;" onmouseover="this.style.background='var(--gray-50)'" onmouseout="this.style.background='transparent'">
      <img src="<?php echo $rmPic; ?>" alt="" style="width:45px;height:45px;border-radius:50%;object-fit:cover;">
      <div>
        <h6 style="margin:0;font-size:0.88rem;font-weight:600;color:var(--dark);"><?php echo h(getDisplayName($rm)); ?></h6>
        <small style="color:var(--gray-500);"><?php echo h($rm['age']); ?> Yrs, <?php echo h($rm['religion']); ?></small>
      </div>
    </a>
    <?php }} else { ?>
    <p style="color:var(--gray-400); text-align:center; padding:1rem;">No matches yet</p>
    <?php } ?>
  </div>
</div>
