<?php
  // Includes
  require_once 'includes/session.php';
  require_once 'includes/db.php';
?>

<?php
  // If logged in already, redirects to current role's landing page
  if( isset($_SESSION['loggedIn']) &&
      $_SESSION['loggedIn'] /* == true */ &&
      isset($_SESSION['role']) ) {
    header( 'Location: ' . $start_pages[$_SESSION['role']], false, 303 );
    exit;
  }
?>


<?php
  // Testing from localhost
  if( $_SERVER["HTTP_HOST"] == 'localhost' ) {
    if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
      $csid = $_POST['csid'];
      $pass = $_POST['password'];
      if( db_checkCSID( $csid ) ) {
        $_SESSION = db_getUserData( $csid );
        $start_page = $start_pages[$_SESSION['default_role']];
        $_SESSION['loggedIn'] = TRUE;
        header( 'Location: ' . $start_page, false, 303 );
        exit;
      }
      else {
        $login_error = "CS ID not in AIO database. Contact the admin.";
      }
    }
  }
  // Accessing/testing on staging server
  else {
    // Login Script
    if( $_SERVER['REQUEST_METHOD'] == 'POST' ) { // Login Attempt made
      // Test connection
      if( $pass_serv = ldap_connect('ldap://fcsldap.cs.dal.ca') ) {
        // Set to proper protocol version
        if( ldap_set_option($pass_serv,LDAP_OPT_PROTOCOL_VERSION,3) ) {
          // Establish connection
          if( $pass_bind = ldap_bind( $pass_serv ) ) { 
            // Check credentials
            $cred_chck = ldap_compare( $pass_bind, "cn=". $_POST['csid'], "password", $_POST['password']);
            if( $cred_chck === -1 ) { // error in compare statement
              $login_error = "Internal error. Please notify the system administrator.";
            }
            elseif( $cred_chck === false ) { // Wrong CSID or password
              $login_error = "Incorrect CSID/password.";
            }
            else { // Correct CSID and password
              // Fill session data
              if( db_checkCSID( $_POST['csid'] ) ) {
                $_SESSION = db_getUserData( $_POST['csid'] );
                $start_page = $start_pages[$_SESSION['default_role']];
                // Mark session as loggged in
                $_SESSION['loggedIn'] = TRUE;
                // Redirect to default page
                header( 'Location: ' . $start_page, false, 303 );
                exit;
              }
              else {
                $login_error = "CS ID not in AIO database. Contact the admin.";
              }
            }
          }
          else { // Could not connect to server
            $login_error = "Could not connect to FCS password server. Please try again later.";
          }
        }
        else { // Could not set version number
          $login_error = "Could not connect to FCS password server. Please try again later.";
        }
      }
      else { // Test connection failed
        $login_error = "Could not connect to FCS password server. Please try again later.";
      }
    }
  }
?>

<!DOCTYPE html>
<html>    
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login - FCS AIO</title>
    <link rel="icon" href="https://cdn.dal.ca/etc/designs/dalhousie/clientlibs/global/default/images/favicon/favicon-96x96.png.lt_99f65cb862044f8ef23bdf522c69c6f1.res/favicon-96x96.png">
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>
  <body style="margin: auto;">
    <!-- Login Only Header Bar -->
    <img height="64" src="https://cdn.dal.ca/content/dam/dalhousie/images/dept/communicationsandmarketing/01%20DAL%20FullMark-Blk.jpg.lt_412f83be03abff99eefef11c3f1ec3a4.res/01%20DAL%20FullMark-Blk.jpg">


    <!-- Login Page Body -->
    <div>
      <h1>Academic Integrity Portal</h1>
      <h3>Faculty of Computer Science</h3>
      
      <h3>Login</h3>
      
      <!-- Login screen ready for back end php -->
      <form method="post">
        <div class="container" style="margin-bottom: 25px">
          <label for="uname"><b>Enter CS ID and password</b>
            <br>
            <input type="text" placeholder="CS ID" name="csid" required>
            <br>
            <input type="password" placeholder="Password" name="password" required>
          </label>
          <br>
          <button class="btn btn-info" type="submit" value="Submit" name="LoginSubmit">Submit</button>
        </div>
        <?php
          if( isset($login_error) ) {
            echo "        <div class='container alert alert-danger'>";
            echo "          " . $login_error;
            echo "        </div>";
          }
        ?>
        <div class="container">
        </div>
      </form>
    </div>
  </body>
</html>