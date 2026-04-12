<!-- EliteMatch Modern Navigation -->
<nav class="navbar navbar-expand-lg em-navbar">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <i class="fas fa-heart"></i> EliteMatch
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="index.php"><i class="fas fa-home me-1"></i> Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="about.php">About</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-search me-1"></i> Search
          </a>
          <ul class="dropdown-menu">
            <?php if(isloggedin()): ?>
            <li><a class="dropdown-item" href="matches.php"><i class="fas fa-heart-circle-check me-2"></i>Matches</a></li>
            <?php endif; ?>
            <li><a class="dropdown-item" href="search.php"><i class="fas fa-filter me-2"></i>Regular Search</a></li>
            <li><a class="dropdown-item" href="search-id.php"><i class="fas fa-id-badge me-2"></i>Search By ID</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="contact.php">Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="faq.php">FAQ</a>
        </li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <?php 
        if(isloggedin()){
          $id = $_SESSION['id'];
          $uname = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User';
          $pendingRequestCount = getPendingRequestCount($id);
          echo '<div class="dropdown">';
          echo '<a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">';
          echo '<div style="width:32px;height:32px;border-radius:50%;background:var(--gradient-primary);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.85rem;">' . strtoupper(substr($uname,0,1)) . '</div>';
          echo '<span class="d-none d-md-inline">' . $uname . '</span>';
          echo '</a>';
          echo '<ul class="dropdown-menu dropdown-menu-end">';
          echo '<li><a class="dropdown-item" href="userhome.php?id='.$id.'"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>';
          echo '<li><a class="dropdown-item" href="matches.php"><i class="fas fa-heart-circle-check me-2"></i>My Matches</a></li>';
          echo '<li><a class="dropdown-item" href="requests.php"><i class="fas fa-envelope-open-text me-2"></i>Requests';
          if($pendingRequestCount > 0){
            echo ' <span class="badge text-bg-danger rounded-pill ms-1">'.$pendingRequestCount.'</span>';
          }
          echo '</a></li>';
          echo '<li><a class="dropdown-item" href="requests.php#connections"><i class="fas fa-comments me-2"></i>My Chats</a></li>';
          echo '<li><a class="dropdown-item" href="view_profile.php?id='.$id.'"><i class="fas fa-user me-2"></i>My Profile</a></li>';
          echo '<li><a class="dropdown-item" href="create_profile.php?id='.$id.'"><i class="fas fa-edit me-2"></i>Edit Profile</a></li>';
          echo '<li><a class="dropdown-item" href="photouploader.php?id='.$id.'"><i class="fas fa-camera me-2"></i>Upload Photos</a></li>';
          echo '<li><hr class="dropdown-divider"></li>';
          echo '<li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>';
          echo '</ul>';
          echo '</div>';
        } else {
          echo '<a href="login.php" class="nav-link btn-login">Login</a>';
          echo '<a href="register.php" class="nav-link btn-register">Register Free</a>';
        }
        ?>
      </div>
    </div>
  </div>
</nav>
