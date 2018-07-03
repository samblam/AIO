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

require_once 'session.php';
include_once 'db.php';
include_once 'fileFunctions.php';

$baseEvidenceDir = "../evidence/";
$processSuccessful = true;

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

//Form A processing
if(isset($_POST['SaveFormA']) || isset($_POST['SubmitFormA'])){

  $userId = $_SESSION['userId'];//first column of the current user's role table in the database

  //Grabs all form data and sanatize it
  $prof = htmlspecialchars(trim(stripslashes($_POST['ProfessorName'])));
  $email = htmlspecialchars(trim(stripslashes($_POST['email'])));
  $phone = htmlspecialchars(trim(stripslashes($_POST['phoneNum'])));
  $faculty = htmlspecialchars(trim(stripslashes($_POST['faculty'])));
  $cname = htmlspecialchars(trim(stripslashes($_POST['class-name'])));
  $students = $_POST['Name']; //array of student names
  $boos = $_POST['B00']; //array of B00 numbers
  $stringDate = htmlspecialchars(trim(stripslashes($_POST['DateAlleged'])));
  $formatDate = strtotime($stringDate);
  $date = date('Y-m-d',$formatDate); // allegation date formatted for mysql database
  $comments = htmlspecialchars(trim(stripslashes($_POST['additionalComments'])));

  //Form A is submitted
  if(isset($_POST['SubmitFormA'])){
    $submitDate = date('Y-m-d', time());

    // If you are submitting a form A, it cant have multiple students with the same csid. So, return to the form.
    // The "multiIds" query string might be useful for displaying an error message once you return to the form page.
    if(count($boos) != count(array_unique($boos)) && isset($_POST['case_id'])){
      header("locaton: ../common/forma.php?multiIds=true&case_id={$_POST['case_id']}");
    }
    elseif(count($boos) != count(array_unique($boos)) && !isset($_POST['case_id'])){
      header("locaton: ../common/forma.php?multiIds=true");
    }

    $caseId;

    if(isset($_POST['case_id'])){
      // form was previously saved and is now being submitted
      $caseId = (int)$_POST['case_id'];
      $statement = $conn->prepare("UPDATE active_cases SET prof_id = ?, class_name_code = ?, date_aware = ?, description = ?, form_a_submit_date = ? WHERE case_id = ?");
      $statement->bind_param("issssd",$userId, $cname, $date, $comments, $submitDate, $caseId); //bind the values to be inserted to the query
      if(!$statement->execute()) {
        //might want to replace this with header("location: ../forma.php"); so that you aren't executing the script further if there is an error
        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
        $processSuccessful = false;
      }
    } 

    else {
      //Create new case entry
      $statement = $conn->prepare("INSERT INTO active_cases (prof_id, class_name_code, date_aware, description, form_a_submit_date) VALUES (?, ?, ?, ?, ?)");
      $statement->bind_param("issss",$userId, $cname, $date, $comments, $submitDate); //bind the values to be inserted to the query
      if(!$statement->execute()) {
        //might want to replace this with header("location: ../forma.php"); so that you aren't executing the script further if there is an error
        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
        $processSuccessful = false;
      }

      // Grabs case_id of the just inserted case and uses it to create the evidence directory name for this case in the database
      // This step might be unnecessary if the value is just the same as the case_id. If its a combo of values then it might be necessary.
      $caseId = $conn->insert_id;
      $updateEvidence = $conn->prepare("UPDATE active_cases SET evidence_fileDir = ? WHERE case_id = ".$caseId);
      $updateEvidence->bind_param("s", $caseId); //bind evidence folder name to the prepared statements
      if(!$updateEvidence->execute()) {
        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
        $processSuccessful = false;
      }

    }

    //Insert students into student table
    /**
     * For each set of students and b00s, check if both entries are not null.
     * If not prepare the insert statement, sanatize the current name and B00,
     * bind the values to the insert statement and execute;\.
     */
    for($i = 0; $i < sizeof($students); $i++) {
      if($students[$i] != NULL && $boos[$i] != NULL){
        $statement = $conn->prepare("INSERT INTO student (csid, case_id, fname) VALUES (?, ?, ?)");
        $currB00 = htmlspecialchars(trim(stripslashes($boos[$i])));
        $currStudent = htmlspecialchars(trim(stripslashes($students[$i])));
        $statement->bind_param("sis", $currB00, $caseId, $currStudent); //bind initial values to the prepared statements
        if (!$statement->execute()) {
           echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
           $processSuccessful = false;
        }
      }
    }

    // validate uploaded files
    $allFilesAreValid = validateUploadedFiles();

    // Creates the case directory for uploading evidence
    if(!is_dir($baseEvidenceDir . $caseId)){
        mkdir($baseEvidenceDir . $caseId);
    } 

    $zipFileLocation = $baseEvidenceDir . $caseId; 

    $uploadSuccessful = moveUploadedFilesToZip($allFilesAreValid, $zipFileLocation);

    if(!$uploadSuccessful){
      echo "Failed to upload the given files";
      $processSuccessful = false;
    }

    if($processSuccessful){
      sendUserHome();
    }
  }

  //The case where this Form A has already been saved before and needs to be just saved again
  if(isset($_POST['SaveFormA']) && !isset($_POST['case_id'])){
    //Create new case entry
    $statement = $conn->prepare("INSERT INTO active_cases (prof_id, class_name_code, date_aware, description) VALUES (?, ?, ?, ?)");
    $statement->bind_param("ssss",$userId, $cname, $date, $comments); //bind initial values to the prepared statements
    if (!$statement->execute()) {
       echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
    }

    // Grabs case_id of the just inserted case and uses it to create the evidence directory name for this case in the database
    // This step might be unnecessary if the value is just the same as the case_id. If its a combo of values then it might be necessary.
    $caseId = $conn->insert_id;
    $updateEvidence = $conn->prepare("UPDATE active_cases SET evidence_fileDir = ? WHERE case_id = ".$caseId);
    $updateEvidence->bind_param("s", $caseId); //bind evidence folder name to the prepared statements
    if (!$updateEvidence->execute()) {
       echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
    }

    //Insert students into student table
    /**
     * For each set of students and b00s, check if both entries are not null.
     * If not prepare the insert statement, sanatize the current name and B00,
     * bind the values to the insert statement and execute;\.
     */
    for($i = 0; $i < sizeof($students); $i++) {
      if($students[$i] != NULL && $boos[$i] != NULL){
        $statement = $conn->prepare("INSERT INTO student (csid, case_id, fname) VALUES (?, ?, ?)");
	      $currB00 = htmlspecialchars(trim(stripslashes($boos[$i])));
	      $currStudent = htmlspecialchars(trim(stripslashes($students[$i])));
        $statement->bind_param("sis", $currB00, $caseId, $currStudent); //bind initial values to the prepared statements
        if (!$statement->execute()) {
           echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
        }
      }
    }

    sendUserHome();
  }

  //The case where a new Form A is created but saved instead of submitted
  if(isset($_POST['SaveFormA']) && isset($_POST['case_id'])){
    //Create new case entry
    $statement = $conn->prepare("UPDATE active_cases SET prof_id = ?, class_name_code = ?, date_aware = ?, description = ? WHERE case_id = " . (int)$_POST['case_id']);
    $statement->bind_param("isss",$userId, $cname, $date, $comments); //bind initial values to the prepared statements
    if (!$statement->execute()) {
       echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
    }

    //Select students from this case
    $statement = $conn->prepare("SELECT fname, csid FROM student WHERE case_id = " . (int)$_POST['case_id']);
    if(!$statement->execute()){
      echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
    }
    $statement->bind_result($fname, $csid);

    //creates an associative array of csids and names of all students in the cases
    //to easily lookup all students from this case to see if a student needs to be added,
    //removed, or edited.
    $currStudents = array();
    while($statement->fetch()){
      $currStudents[$csid] = $fname;
    }

    for($i = 0; $i < sizeof($students); $i++) {
      if($students[$i] != NULL && $boos[$i] != NULL){
        //Update a student if the csid exists already
        if(array_key_exists("{$boos[$i]}", $currStudents) && $currStudents[$csid] != $students[$i]){
          $statement = $conn->prepare("UPDATE student SET fname = ?, WHERE case_id = {$_POST['case_id']} AND csid = {$boos[$i]}");
          $statement->bind_param("s", $student[$i]);
        }
        elseif(!array_key_exists("{$boos[$i]}", $currStudents)) {
          $statement = $conn->prepare("INSERT INTO student (csid, case_id, fname) VALUES (?, ?, ?)");
          $statement->bind_param("sss", $boos[$i], $caseId, $students[$i]); //bind initial values to the prepared statements
        }
        elseif(!in_array($currStudents[$boos[$i]], $students)){
          $statement = $conn->prepare("DELETE FROM student WHERE case_id = {$_POST['case_id']} AND csid = {$boos[$i]}");
        }

        if (!$statement->execute()) {
           echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
        }
      }
    }

    sendUserHome();
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


// add evidence to previously submitted case
if(isset($_POST['AddEvidence'])){

  if(!isset($_POST['EvidenceDirectory'])){
    echo "Error - there was no evidence directory in which to add the uploaded files.";
  } 

  else {
    $evidenceDir = $baseEvidenceDir . $_POST['EvidenceDirectory'];
    // Creates the case directory for uploading evidence
    if(!is_dir($evidenceDir)){
      mkdir($evidenceDir);
    } 

    $allFilesAreValid = validateUploadedFiles();
    $uploadSuccessful = moveUploadedFilesToZip($allFilesAreValid, $evidenceDir);

    if($uploadSuccessful){
      sendUserHome();
    } else {
      echo "Failed to upload the given files";
    }
  }
}

// deletes all students and active cases with the given case_id for admins
if(isset($_POST['deleteCase']) && isset($_POST['case_id']) && $_SESSION['role'] == "admin") {
  $id = htmlspecialchars(trim(stripslashes($_POST['case_id'])));
  $conn->query("DELETE FROM student WHERE case_id = \"$id\"");
  $conn->query("DELETE FROM active_cases WHERE case_id = \"$id\"");
}

// deletes all students and active cases with the given case_id for insufficient evidence
if(isset($_POST['insufficientEvidence']) && isset($_POST['case_id']) && $_SESSION['role'] == "aio") {
  $id = htmlspecialchars(trim(stripslashes($_POST['case_id'])));
  /*$sql = "SELECT fname FROM student WHERE case_id = '$id'";
  $name = mysql_query($sql);
  $sql2 = "SELECT email FROM professor WHERE professor_id = ;
  $email = mysql_query($sql2);
  $msg = "Insufficient ividence provided for academic integrity violation.";
  $msg = wordwrap($msg, 70);
  mail("sr.mart@hotmail.com", "Insufficient Evidence", $msg);*/
  $conn->query("DELETE FROM student WHERE case_id = \"$id\"");
  $conn->query("DELETE FROM active_cases WHERE case_id = \"$id\"");
}

// deletes all students and active cases with the given case_id for close case
if(isset($_POST['closeCaseNotGuilty']) && isset($_POST['case_id']) && $_SESSION['role'] == "aio") {
  $id = htmlspecialchars(trim(stripslashes($_POST['case_id'])));
  $conn->query("DELETE FROM student WHERE case_id = \"$id\"");
  $conn->query("DELETE FROM active_cases WHERE case_id = \"$id\"");
}

// moves all students and active cases with the given case_id to archive
if(isset($_POST['closeCaseGuilty']) && isset($_POST['case_id']) && $_SESSION['role'] == "aio") {
  $id = htmlspecialchars(trim(stripslashes($_POST['case_id'])));
  $conn->query("INSERT INTO history (class_name, verdict, date_allegation) SELECT class_name_code, case_verdict, date_aware FROM active_cases WHERE case_id = \"$id\"");
  $conn->query("DELETE FROM student WHERE case_id = \"$id\"");
  $conn->query("DELETE FROM active_cases WHERE case_id = \"$id\"");
}

?>
