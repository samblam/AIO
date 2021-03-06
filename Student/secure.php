<?php
  require_once '../includes/session.php';

  // checks if a user is logged in
  if( !$_SESSION['loggedIn'] ) {
    header( 'HTTP/1.0 403 Forbidden' );
    exit( 'You are not logged in.' );
  } // if a user is logged in, checks if legal user
  elseif( !in_array("student", $_SESSION['roles']) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    exit( 'Insufficient access level' );
}
?>
