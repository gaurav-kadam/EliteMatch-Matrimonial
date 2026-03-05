<?php
// Sidebar widget for matching profiles
$conn = getConn();
?>
<div class="em-detail-card">
  <div class="card-header-custom"><i class="fas fa-star"></i> Top Matches</div>
  <div class="card-body-custom">
    <?php
    $sqlm = "SELECT c.*, p.pic1 FROM customer c LEFT JOIN photos p ON c.cust_id = p.cust_id ORDER BY RAND() LIMIT 5";
    $resm = mysqlexec($sqlm);
    if($resm && mysqli_num_rows($resm) > 0){
      while($rm = mysqli_fetch_assoc($resm)){
        $rmId = intval($rm['cust_id']);
        $rmPic = !empty($rm['pic1']) ? "profile/$rmId/".$rm['pic1'] : "https://ui-avatars.com/api/?name=".urlencode($rm['firstname'])."&background=8B5CF6&color=fff&size=50";
    ?>
    <a href="view_profile.php?id=<?php echo $rmId; ?>" style="display:flex; align-items:center; gap:12px; padding:10px; border-radius:8px; margin-bottom:6px; text-decoration:none; color:inherit; transition:all 0.3s ease;" onmouseover="this.style.background='var(--gray-50)'" onmouseout="this.style.background='transparent'">
      <img src="<?php echo $rmPic; ?>" alt="" style="width:45px;height:45px;border-radius:50%;object-fit:cover;">
      <div>
        <h6 style="margin:0;font-size:0.88rem;font-weight:600;color:var(--dark);"><?php echo h($rm['firstname']); ?></h6>
        <small style="color:var(--gray-500);"><?php echo h($rm['age']); ?> Yrs, <?php echo h($rm['religion']); ?></small>
      </div>
    </a>
    <?php }} else { ?>
    <p style="color:var(--gray-400); text-align:center; padding:1rem;">No matches yet</p>
    <?php } ?>
  </div>
</div>