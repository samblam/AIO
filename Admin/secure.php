<?php
  require_once '../includes/session.php';

  // checks if a user is logged in
  if( !isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn'] ) {
    header( 'HTTP/1.0 403 Forbidden' );
    exit( 'You are not logged in.' );
  } // if a user is logged in, checks if legal user
  elseif( !in_array("admin", $_SESSION['access_roles']) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    exit( 'Insufficient access level' );
  }

  // overrides globalSecure.php
  $security_override_active = TRUE;
  require_once '../includes/globalSecure.php'
?>