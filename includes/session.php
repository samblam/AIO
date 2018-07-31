<?php
  /**
   * The $_SESSION array should contain:
   *     csid          -> the CSID of the user
   *     roles         -> list of roles the user has
   *     default_role  -> the highest ranked of the user's roles
   *                      ( admin -> aio -> professor -> student )
   *     role          -> the user's active role, starts same as default_role
   *     fname         -> the user's first name
   *     lname         -> the user's last name
   *     phone         -> the user's phone number
   *     email         -> the user's email address
   *     loggedIn      -> TRUE if a user is logged in
   */
  session_start();

  $start_pages = array();
  // Default redirect for each role
  if( substr($_SERVER["HTTP_HOST"], 0, 9) == "localhost" ) {
    $start_pages = array( 'professor' => 
                            'http://' . $_SERVER["HTTP_HOST"] . '/Instructor/ActiveCases.php',
                          'aio'       => 
                            'http://' . $_SERVER["HTTP_HOST"] . '/AIO/ActiveCases.php',
                          'admin'     => 
                            'http://' . $_SERVER["HTTP_HOST"] . '/Admin/ActiveCases.php',
                          // Replace when student stuff is built
                          'student'   => 
                            'http://' . $_SERVER["HTTP_HOST"] . '/includes/logout.php'
                        ); 
  }
  else {
    $start_pages = array( 'professor' => 
                            'http://projects.cs.dal.ca/aio/Instructor/ActiveCases.php',
                          'aio'       => 
                            'http://projects.cs.dal.ca/aio/AIO/ActiveCases.php',
                          'admin'     => 
                            'http://projects.cs.dal.ca/aio/Admin/ActiveCases.php',
                          // Replace when student stuff is built
                          'student'   => 
                            'http://projects.cs.dal.ca/aio/includes/logout.php'
                        ); 
  }

  // Word to display instead of db name
  $display_names = array( 'professor' => 'Instructor',
                          'aio'       => 'AIO',
                          'admin'     => 'Admin',
                          'student'   => 'Student' );
?>