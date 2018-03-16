<?php

$role = $_SESSION['role'];
$csid = $_SESSION['csid'];
$pageName = basename($_SERVER['PHP_SELF'], ".php");
//Uncomment once sessions work
if(!isset($_SESSION['username'])){
  header("location: /index.php?notLoggedIn=true");
}

//Role security
if($role == "professor"){
  if( $pageName != "ProfessorActiveCases" && $pageName != "forma"){
    header("location: /HTML/ProfessorActiveCases.php?unauthPage=true");
  }
}
elseif($role == "aio"){//NOT FINISHED inner if statement******************************************************************************
  if( $pageName != "AioActiveCases" && $pageName != "formb"){
    header("location: /HTML/AioActiveCases.php?unauthPage=true");
  }
}
elseif($role == "admin"){
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
