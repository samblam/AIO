<?php
    //require_once "globalSecure.php";
/**
 * This file is included in each page. It is where the form processing logic is located.
 * The future group that is developing this might want to think about whether they
 * include this file on each page or just have it only be accessible through
 * the action attribute of the html forms instead of both as it is now.
 *
 * Another thing to note is that all of the data that's inputed in Form A automatically
 * from the professor table is not actually processed. In other words, the professor name,
 * email, phone number, etc., never change from cases to case for a professor even though
 * there are fields in the form that would suggest they could be user inputted. This is because
 * the database is setup so that when a professor is logged in and submits a case, the professor's
 * professor_id of the professor table is put in the active case table to reference the professor,
 * rather than having each of those individual professor fields in the active_case table. You
 * will want to discuss with the Client whether you should keep the current professor fields in form A
 * and change the active_case table to have each individual professor field, or if you should keep
 * the current active_case table structure as is and either get rid of those professor specific fields
 * or have those fields uneditable. 
 */

//start the session and include the database connection file

require_once 'session.php';
include_once 'db.php';
include_once 'fileFunctions.php';

//The case where this Form A set aio_id 
  if(isset($_POST['AcceptFormA'])){
    
    //header('location: ../AIO/ActiveCases.php');
    // Grabs case_id of the just inserted case and uses it to set aio_id to current aio
    $userId =(int)$_SESSION['userId'];

    $CurrCaseId=(int)$_POST['CurrCaseId'];
    $statement = $conn->prepare("SELECT aio_id FROM active_cases WHERE case_id = ?");

    
    $AcceptCase = $conn->prepare("UPDATE active_cases SET aio_id = ?  WHERE case_id = ?");
    $AcceptCase->bind_param("dd", $userId,$CurrCaseId); //bind evidence folder name to the prepared statements
    if (!$AcceptCase->execute()) {
       echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
    }

    header('location: ../AIO/ActiveCases.php');
    
  }

//The case where this Form A set aio_id null
  if(isset($_POST['DenyFormA'])){
    
    //header('location: ../AIO/ActiveCases.php');

    // Grabs case_id of the just inserted case and uses it to set aio id to null
    $userId =(int)$_SESSION['userId'];

    $CurrCaseId=(int)$_POST['CurrCaseId'];
    
    $AcceptCase = $conn->prepare("UPDATE active_cases SET aio_id = NULL WHERE case_id = ?");
    $AcceptCase->bind_param("d",$CurrCaseId); //bind evidence folder name to the prepared statements
    if (!$AcceptCase->execute()) {
       echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
    }

    header('location: ../AIO/ActiveCases.php');
    
  }
?>