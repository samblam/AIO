<?php
  /**
   * This file opens the connection to the database.
   * The first if statement allows you to connect to localhost database if working on a localhost.
   * This assumes that you local db is called aio, your local db is on localhost, and your username and password are root
   *
   * The else connects you to the live database for when you are using this live.
   * This only needs to be changed if you decide to change the database password.
   */
  function OpenCon()
  {
    $dbhost = "";
    $dbuser = "";
    $dbpass = "";
    if($_SERVER['SERVER_NAME'] == "localhost"){
      $dbhost = "localhost";
      $dbuser = "root";
      $dbpass = "root";
    }
    else{
      $dbhost = "db.cs.dal.ca";
      $dbuser = "aio";
      $dbpass = "ge7ochooCae7";
    }
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
