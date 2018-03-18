<?php

  function OpenCon()
  {
    $dbhost = "db.cs.dal.ca";
    $dbuser = "aio";
    $dbpass = "ge7ochooCae7";
    $db = "aio";
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed: %s\n". $conn -> error);
    return $conn;
  }

  function CloseCon($conn)
  {
    $conn -> close();
  }

  global $conn;
  $conn = OpenCon();
  
?>
