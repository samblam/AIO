<?php
  require_once "./session.php";
?>

<?php
  $newRole = $_POST["newRole"];
  $redirect = $start_pages[$newRole];
  $_SESSION['role'] = $newRole;
  header( "Location: $redirect" );
?>