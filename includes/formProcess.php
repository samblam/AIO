<?php
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

// uncomment the next line once security is working with this file
// require_once "globalSecure.php";
require_once 'session.php';
include_once 'db.php';

function sendUserHome(){
  // This returns you back to the role's active case page.
  // Might want to change admin and aio conditions and locations (elseif and else respectively)
  // as the professors is pretty obvious but admin and aio might want to return
  // to the CaseInformation page rather than activescases (Ask the client)
  if($_SESSION['role'] == "professor"){
    header('location: ../Instructor/ActiveCases.php');
  }
  elseif ($_SESSION['role'] == "admin") {
    header('location: ../Admin/ActiveCases.php');
  }
  elseif ($_SESSION['role'] == "aio"){
    header('location: ../AIO/ActiveCases.php');
  }
  else{
    header('location: ../index.php');
  }
}

// form B processing
if(isset($_POST['SaveFormB']) || isset($_POST['SubmitFormB'])){

}

// form C processing
if(isset($_POST['SaveFormC']) || isset($_POST['SubmitFormC'])){

}

// form D processing
if(isset($_POST['SaveFormD']) || isset($_POST['SubmitFormD'])){

}


/*
 All code below this point is used by either ActiveCases.php or CaseInformation.php to perform actions like
 changing the AIO of a case, closing a case, etc.
*/


// Deletes all students and active cases with the given case id from Admin/ACtiveCases.php
if(isset($_POST['deleteCase']) && isset($_POST['case_id']) && $_SESSION['role'] == "admin") {
  $id = htmlspecialchars(trim(stripslashes($_POST['case_id'])));
  $conn->query("DELETE FROM student WHERE case_id = \"$id\"");
  $conn->query("DELETE FROM active_cases WHERE case_id = \"$id\"");
}

// Allows the admin to change the AIO of a case from ChangeAIO.php
if(isset($_POST['submitChangeAIO']) && isset($_POST['case_id']) && $_SESSION['role'] == "admin") {
  $id = htmlspecialchars(trim(stripslashes($_POST['case_id'])));
  // Gets selected AIO from dropdown
  $newAIO = htmlspecialchars(trim(stripslashes($_POST['selectedAIO']))); 
  // Gets AIO id from db
  if($newAIO != "Select New"){
    $statement = $conn->prepare("SELECT aio_id FROM aio WHERE CONCAT(TRIM(fname), ' ', TRIM(lname)) LIKE '$newAIO'"); 
    if(!$statement->execute()){
        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
    }
    $statement->bind_result($aioId);
    while($statement->fetch()){ }
    // Updates AIO in active cases table
    $conn->query("UPDATE active_cases SET aio_id = '$aioId' WHERE case_id = '$id'"); 
  }
}

// Deletes all students and active cases with the given case id for insufficient evidence from CaseInformation.php
if(isset($_POST['insufficientEvidence']) && isset($_POST['case_id']) && $_SESSION['role'] == "aio") {
  $caseId = htmlspecialchars(trim(stripslashes($_POST['case_id'])));
  // Get student name from db
  $statement = $conn->prepare("SELECT fname, lname, csid FROM student WHERE case_id = '$caseId'"); 
  if(!$statement->execute()){
    echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
  }
  $statement->bind_result($fname, $lname, $id);
  // Creates email message
  while($statement->fetch()){ 
    $msg = "Insufficient evidence provided for academic integrity case involving " . $fname . " " . $lname . " (" . $id . "). The case has been closed.";
    $msg = wordwrap($msg, 70);
  }
  // Get prof email from db
  $statement = $conn->prepare("SELECT professor.email FROM active_cases LEFT JOIN professor ON active_cases.prof_id = professor.professor_id WHERE active_cases.case_id = '$caseId'"); 
  if(!$statement->execute()){
    echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
  }
  $statement->bind_result($email);
  // Sends email
  while($statement->fetch()){
    mail($email, "Insufficient evidence provided for academic integrity case.", $msg);
  }
  $conn->query("DELETE FROM student WHERE case_id = \"$caseId\"");
  $conn->query("DELETE FROM active_cases WHERE case_id = \"$caseId\"");
  // Redircets to the ActiveCases.php page once the email is sent
  header("Location: ../AIO/ActiveCases.php");
}

// Deletes all students and active cases with the given case id from CaseInformation.php
if(isset($_POST['closeCaseNotGuilty']) && isset($_POST['case_id']) && $_SESSION['role'] == "aio") {
  $id = htmlspecialchars(trim(stripslashes($_POST['case_id'])));
  $conn->query("DELETE FROM student WHERE case_id = \"$id\"");
  $conn->query("DELETE FROM active_cases WHERE case_id = \"$id\"");
}

// Moves all students and active cases with the given case id to archive from CaseInformation.php
if(isset($_POST['closeCaseGuilty']) && isset($_POST['case_id']) && $_SESSION['role'] == "aio") {
  $id = htmlspecialchars(trim(stripslashes($_POST['case_id'])));
  $conn->query("INSERT INTO history (class_name, verdict, date_allegation) SELECT class_name_code, case_verdict, date_aware FROM active_cases WHERE case_id = \"$id\"");
  $conn->query("DELETE FROM student WHERE case_id = \"$id\"");
  $conn->query("DELETE FROM active_cases WHERE case_id = \"$id\"");
}

// TODO: Add functionality so a zip folder with all of the case files is sent with the email
// sends email to forward case to senate from CaseInformation.php
if(isset($_POST['forwardCase']) && isset($_POST['case_id']) && $_SESSION['role'] == "aio") {
  $caseId = htmlspecialchars(trim(stripslashes($_POST['case_id'])));
  $email = htmlspecialchars(trim(stripslashes($_POST['email_to'])));
  $cc = htmlspecialchars(trim(stripslashes($_POST['email_cc'])));
  $subject = htmlspecialchars(trim(stripslashes($_POST['email_subject'])));
  $message = htmlspecialchars(trim(stripslashes($_POST['email_message'])));
  echo "<script>console.log( 'Debug Objects: " . $message . "' );</script>";
  $message = wordwrap($message,70);
  $header = "CC:" . $cc . "\r\n";
  mail($email, $subject, $message, $header); 
  // Redircets to the CaseINformation.php page once the email is sent
  header("Location: ../AIO/CaseInformation.php");
}

?>
