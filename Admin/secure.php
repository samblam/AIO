<?php
  require_once '../includes/session.php';

  $login_link = "";
  if( substr($_SERVER["HTTP_HOST"], 0, 9) == 'localhost' ) {
    $login_link = 'http://' . $_SERVER["HTTP_HOST"] . '/login.php';

  }
  else {
    $login_link = 'http://projects.cs.dal.ca/aio/login.php';
  }

  // checks if a user is logged in
  if( !isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn'] ) {
    header( 'HTTP/1.0 403 Forbidden' );
    echo "<h3>You are not logged in</h3>";
    echo "<a href='$login_link'>Return to login page</a>";
    exit();
  } // if a user is logged in, checks if legal user
  elseif( !in_array("admin", $_SESSION['roles']) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    echo "<h3>Insufficient access level</h3>";
    echo "<p>Please contact admin.</p>";
    $link = $start_pages[$_SESSION['role']];
    echo "<a href='$link'>Return Home</a>";
    exit();
  }

  // overrides globalSecure.php
  $security_override_active = TRUE;
  require_once '../includes/globalSecure.php'
?>