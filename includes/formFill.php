<?php
  require_once "globalSecure.php";
  
  // Creates the variables for professor info to be automatically inputed into form A
  if(basename($_SERVER['PHP_SELF']) == "forma.php" && $_SESSION['role'] == "professor"){
    $query = $conn->prepare("SELECT fname, lname, phone, email, department FROM professor WHERE professor_id = ?");
    $query->bind_param("i", $_SESSION['userId']);
    $query->execute();
    $query->store_result();
    $num_of_rows = $query->num_rows;
    if($num_of_rows > 0) {
      $query->bind_result($fname, $lname, $prof_phone, $prof_email, $prof_dept);
      $query->fetch();
      $prof_name = ($fname . " " . $lname);
    }
  }

  //check the query string to see if there is saved data
  if(isset($_GET['saved']) && $_GET['saved'] == "true") {
    $id = (int)$_GET['case_id'];
    $query = null;
    if(isset($_GET['case_id'])){
      $query = $conn->prepare("SELECT prof_id, class_name_code, date_aware FROM active_cases WHERE case_id = ?");
      $query->bind_param("i", $id);
      $query->execute();
    }

    //All the variables we will need to fill in the from values
    $prof_name;
    $prof_email;
    $prof_phone;
    $prof_dept;

    $course_name;
    $prof_id;
    $date_alleg;
    $query->store_result();
    $num_of_rows = $query->num_rows;

    if ($num_of_rows > 0) {//if there is any data in the case
      $query->bind_result($prof_id, $course_name, $date_alleg);
      $query->fetch();
      if ( ($prof_id != NULL) || (!empty($prof_id))) { //This will fetch all the information needed from the professor table
        $query = $conn->prepare("SELECT fname, lname, phone, email, department FROM professor WHERE professor_id = ?");
        $query->bind_param("i", $prof_id);
        $query->execute();
        $query->store_result();
        $num_of_rows = $query->num_rows;
        if ($num_of_rows > 0) {
                $query->bind_result($fname, $lname, $prof_phone, $prof_email, $prof_dept);
                $query->fetch();
                $prof_name = ($fname . " " . $lname);
        }
      }
    }
          //form A data is rettieved at this point.
  }
?>
