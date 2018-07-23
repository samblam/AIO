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

  /**
   * Checks whether the provided csid can be found in the DB
   */
  function db_checkCSID($csid)
  {
    $stmt = $conn->prepare( "SELECT count(csid) FROM ? WHERE csid LIKE ?" );
    $stmt->bind_param( "ss", $table_name, $csid );
    $tables = array( 'admin', 'aio', 'professor', 'student' );
    foreach ($tables as $table_name ) {
      $result = $stmt->execute();
      if( $result->num_rows > 0 ) {
        return true;
      }
    }
    return false;
  }

  /**
   * Get's session data from DB
   */
  function db_getUserData($csid)
  {
    $data = array( 'csid' => $csid );
    // Fetch user roles
    $roles = array();
    $stmt = $conn->prepare( "SELECT count(csid) FROM ? WHERE csid LIKE ?" );
    $stmt->bind_param( "ss", $table_name, $csid );
    $tables = array( 'admin', 'aio', 'professor', 'student' );
    foreach ($tables as $table_name ) {
      $result = $stmt->execute();
      if( $result->num_rows > 0 ) {
        array_push( $roles, $table_name );
      }
    }
    $data['user_roles'] = $roles;
    // Determine default user role
    if( in_array( 'admin', $roles ) ) {
      $data['default_role'] = 'admin';
    }
    elseif( in_array( 'aio', $roles ) ) {
      $data['default_role'] = 'aio';
    }
    elseif( in_array( 'professor', $roles) ) {
      $data['default_role'] = 'professor';
    }
    else {
      $data['default_role'] = 'student';
    }
    // Fetch user data from table corresponding to default role
  }

  global $conn;
  $conn = OpenCon();

?>
