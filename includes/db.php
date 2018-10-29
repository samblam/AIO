<?php
  /**
   * This file opens the connection to the database.
   * The first if statement allows you to connect to localhost database if working on a localhost.
   * This assumes that you local db is called aio, your local db is on localhost, and your username and password are root
   *
   * The else connects you to the live database for when you are using this live.
   * This only needs to be changed if you decide to change the database password.
   */
  function OpenCon() {
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

  function CloseCon($conn) {
    $conn -> close();
  }

  /**
   * Checks whether the provided csid can be found in the DB
   */
  function db_checkCSID($csid) {
    $conn = OpenCon();
    
    //$stmt->bind_param( "ss", $table_name, $csid );
    $tables = array( 'admin', 'aio', 'professor', 'student' );
    foreach( $tables as $table_name ) {

      $st = "SELECT * FROM `$table_name` WHERE csid='$csid'";

      $result = $conn->query( $st );
      if( !$result ){
        echo "Database Error. Please contact admin.\nError details: " . $conn->error;
        $result->close();
        break;
      }
      elseif( $result->num_rows > 0 ) {
        $result->close();
        return true;

      }
    }
    return false;
  }

  /**
   * Get's session data from DB
   *
   * Returns an array with elements:
   *     csid          -> the CSID of the user
   *     roles         -> list of roles the user has
   *     default_role  -> the highest ranked of the user's roles
   *                      ( admin -> aio -> professor -> student )
   *     role          -> the user's active role, starts same as default_role
   *     fname         -> the user's first name
   *     lname         -> the user's last name
   *     phone         -> the user's phone number
   *     email         -> the user's email address
   */
  function db_getUserData($csid) {
    $conn = OpenCon();
    $data = array( 'csid' => $csid );

    // Fetch user roles
    $roles = array();
    $tables = array( 'admin', 'aio', 'professor', 'student' );
    foreach ($tables as $table_name ) {
      $st = "SELECT * FROM `$table_name` WHERE csid='$csid'";

      $result = $conn->query( $st );
      if( !$result ){
        echo "Database Error. Please contact admin.\nError details: " . $conn->error;
        $result->close();
        exit;
      }
      elseif( $result->num_rows > 0 ) {
         $roles[] = $table_name;
        $result->close();
      }
    }
    $data['roles'] = $roles;

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
    $data['role'] = $data['default_role'];

    $def = $data['role'];
    // Fetch user data from table corresponding to default role
    $st = "SELECT fname, lname, phone, email FROM `$def` WHERE csid='$csid'";
    $result = $conn->query( $st );
    $arr = $result->fetch_assoc();
    $data['fname'] = $arr['fname'];
    $data['lname'] = $arr['lname'];
    $data['phone'] = $arr['phone'];
    $data['email'] = $arr['email'];

    $result->close();
    CloseCon( $conn );
    return $data;
  }
?>
