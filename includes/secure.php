<?php
include 'session.php';
/**
 * This file is currently not included in any php page but should be when ready.
 * This deals with page security based the users role.
 *
 * A lot of the redirect urls in the header functions have query strings for errors.
 * The idea would be that on index.php you would have certain error messages that appear
 * when one of these query strings exists in the index.php url.
 */

// Session variables and the name of the current page without .php (e.g. index instead of index.php)
$role = $_SESSION['role'];
$csid = $_SESSION['csid'];
$pageName = basename($_SERVER['PHP_SELF'], ".php");

//if not logged in, return to index.php
if(!isset($_SESSION['username'])){
  header("location: /index.php?notLoggedIn=true");
}

//Role security
if($role == "professor"){
  if( $pageName != "ProfessorActiveCases" && $pageName != "forma"){
    header("location: /HTML/ProfessorActiveCases.php?unauthPage=true");
  }
}
elseif($role == "aio"){//NOT FINISHED inner if statement condition of allowed pages for this role
  if( $pageName != "AioActiveCases" && $pageName != "formb"){
    header("location: /HTML/AioActiveCases.php?unauthPage=true");
  }
}
elseif($role == "admin"){//NOT FINISHED inner if statement condition of allowed pages for this role
  if( $pageName == "AioActiveCases" && $pageName == "ProfessorActiveCases"){
    header("location: /HTML/AdminActiveCases.php?unauthPage=true");
  }
}
//For the eventual student role
elseif($role == "student"){
  header("location: /index.php?unauthPage=true");
}
//If someone tries to login with an alternate inputted role
else{
  header("location: /index.php?badRole=true");
}

?>
