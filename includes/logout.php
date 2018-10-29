<?php
  require_once 'session.php';
  // Basic script to logout
  if( ini_get("session.use_cookies") ) {
    $params=session_get_cookie_params();
    setcookie(session_name(),'',time()-42000,
      $params["path"],
      $params["domain"],$params["secure"],
      $params["httponly"]
    );
  }

  $_SESSION = [];
  session_unset(); 
  session_destroy();
  header("location: ../index.php"); //redirect to index.php

  ?>
