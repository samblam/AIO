<?php
require_once 'session.php';
include "db.php";

if(isset($_POST['LoginSubmit'])){
  //gets the role and csid of the user
  $role=htmlspecialchars(trim(stripslashes($_POST['role'])));
  $csid=htmlspecialchars(trim(stripslashes($_POST['uname'])));
  $profExtraFields="";

  //check if submitted role exists and redirect to login page if it doesn't
  if(!($role=="professor" || $role=="aio" || $role=="admin" || $role=="student")){
    header("location: ../index.php");
  }

  //If the role is professor, the below query will also get faculty and department
  if($role=="professor"){
    $profExtraFields=", faculty, department";
  }
  $statement = $conn->prepare("SELECT {$role}_id, fname, lname{$profExtraFields} FROM $role WHERE csid = ?");
  $statement->bind_param("s",$csid); //bind the csid to the prepared statement
  if(!$statement->execute()){ //execute the query
     echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  }
  $statement->store_result(); //store result to access the number of rows in the result
  $num_rows = $statement->num_rows; //get number of rows in the result

  /**
   * If the csid exists start the session, set session variables depending on role,
   * and redirect to the role's active case page.
   *
   * If the csid doesn't exist redirect to index.php with a error query string
   * that will allow an error to be displayed on the page.
   */
  if($num_rows == 1){ //If there is only one row in the result, the user exists.
    $_SESSION['csid'] = $user;
    $_SESSION['role'] = $role;

    if($role == "professor"){
      $statement->bind_result($userId, $fname, $lname, $faculty, $department); //bind the query results to these variables
      while ($statement->fetch()) {
      	$_SESSION['userId'] = $userId;
      	$_SESSION['fname'] = $fname;
      	$_SESSION['lname'] = $lname;
      	$_SESSION['faculty'] = $faculty;
      	$_SESSION['department'] = $department;
      }
      header("location: ../HTML/ProfessorActiveCases.php");
    }
    elseif($role == "aio" || $role == "admin"){
      $statement->bind_result($userId, $fname, $lname); //bind the variables to the statement
      while ($statement->fetch()) {
        $_SESSION['userId'] = $userId;
      	$_SESSION['fname'] = $fname;
      	$_SESSION['lname'] = $lname;
      }
      if($role == "aio"){
        header("location: ../HTML/AioActiveCases.php");
      }
      else{
        header("location: ../HTML/AdminActiveCases.php");
      }
    }
    else{
      //Student section
    }
  }
  else{ // The notFound=true is there in case the future team wants to add a condition in index.php that displays an error message if this variable is set
    header("location: ../index.php?notFound=true");
  }

}
?>
