<?php
  session_start();

  $start_pages = array();
  // Default redirect for each role
  if( $_SERVER["HTTP_HOST"] == "localhost" ) {
    $start_pages = array( 'professor' => 
                            'http://localhost/Instructor/ActiveCases.php',
                          'aio'       => 
                            'http://localhost/AIO/ActiveCases.php',
                          'admin'     => 
                            'http://localhost/Admin/ActiveCases.php',
                          // Replace when student stuff is built
                          'student'   => 
                            'http://localhost/includes/logout.php'
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