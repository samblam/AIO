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

//Form A processing
if(isset($_POST['SaveFormA']) || isset($_POST['SubmitFormA'])){
  //This block of code gets the file ready to upload but doesnt upload it yet because we don't yet know where to put it
  $target_dir= "../evidence/";
  $target_file= $target_dir.basename($_FILES["fileInput"]["name"]);
  $evidence = htmlspecialchars(trim(stripslashes($target_file)));
  $uploadAllowed = false; //boolean to determine if file should be uploaded
  if($target_file != $target_dir){ //if they are equal then nothing uploaded
    $finfo= finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES["fileInput"]["tmp_name"]);
    switch ($mime) { // Each case should be an allowed mime type. Check with Client to see which file types should be allowed.
      /*case '':// Allowed mimes
      case '':// Allowed mimes
        $uploadAllowed = true;
        break;*/
      default:
        $uploadAllowed = true;// Schange to false and uncomment above code block if there are file type restrictions
    }
    if($_FILES["fileInput"]["size"] >2097152) { // Check size of upload. 2097152 = 2MB. Will probably want to change based on Client's requirements
      // code to go back to form
    }
  }

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
    if(count($boos) != count(array_unique($boos)) && isset($_GET['case_id'])){
      header("locaton: ../forma.php?multiIds=true&case_id={$_GET['case_id']}");
    }
    elseif(count($boos) != count(array_unique($boos)) && !isset($_GET['case_id'])){
      header("locaton: ../forma.php?multiIds=true");
    }

    //Create new case entry
    $statement = $conn->prepare("INSERT INTO active_cases (prof_id, class_name_code, date_aware, description, form_a_submit_date) VALUES (?, ?, ?, ?, ?)");
    $statement->bind_param("issss",$userId, $cname, $date, $comments, $submitDate); //bind the values to be inserted to the query
    if(!$statement->execute()) {
      //might want to replace this with header("location: ../forma.php"); so that you aren't executing the script further if there is an error
      echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
    }

    // Grabs case_id of the just inserted case and uses it to create the evidence directory name for this case in the database
    // This step might be unnecessary if the value is just the same as the case_id. If its a combo of values then it might be necessary.
    $caseId = $conn->insert_id;
    $updateEvidence = $conn->prepare("UPDATE active_cases SET evidence_fileDir = ? WHERE case_id = ".$caseId);
    $updateEvidence->bind_param("s", $caseId); //bind evidence folder name to the prepared statements
    if(!$updateEvidence->execute()) {
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
  }



  //The case where a new Form A is created but saved instead of submitted
  if(isset($_POST['SaveFormA']) && isset($_POST['case_id'])){
    //Create new case entry
    $statement = $conn->prepare("UPDATE active_cases SET prof_id = ?, class_name_code = ?, date_aware = ?, description = ? WHERE case_id = {$_GET['case_id']}");
    $statement->bind_param("ssss",$userId, $cname, $date, $comments); //bind initial values to the prepared statements
    if (!$statement->execute()) {
       echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
    }

    //Select students from this case
    $statement = $conn->prepare("SELECT fname, csid FROM student WHERE case_id = ?");
    $statement->bind_param("d", (int)$_POST['case_id']);
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
          $statement = $conn->prepare("UPDATE student SET fname = ?, WHERE case_id = {$_GET['case_id']} AND csid = {$boos[$i]}");
          $statement->bind_param("s", $student[$i]);
        }
        elseif(!array_key_exists("{$boos[$i]}", $currStudents)) {
          $statement = $conn->prepare("INSERT INTO student (csid, case_id, fname) VALUES (?, ?, ?)");
          $statement->bind_param("sss", $boos[$i], $caseId, $students[$i]); //bind initial values to the prepared statements
        }
        elseif(!in_array($currStudents[$boos[$i]], $students)){
          $statement = $conn->prepare("DELETE FROM student WHERE case_id = {$_GET['case_id']} AND csid = {$boos[$i]}");
        }

        if (!$statement->execute()) {
           echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
        }
      }
    }
  }

  // Creates the case directory and then uploads the evidence to it if allowed
  // Future team might want add directory creation logic within if ($uploadAllowed) {}
  $target_file = $target_dir.$caseId."/".$_FILES["fileInput"]["name"];
  if(!is_dir("../evidence/".$caseId)){
    mkdir("../evidence/".$caseId);
  }
  if ($uploadAllowed) {
    if (move_uploaded_file($_FILES["fileInput"]["tmp_name"], $target_file)) {
      echo"File uploaded successfully.";
    }
    else {
      echo"Error uploading file.";
    }
  }



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
  else{
    header('location: ../AIO/ActiveCases.php');
  }
}

//The case where this Form A set aio_id 
  if(isset($_POST['AcceptFormA'])){
    
    //header('location: ../AIO/ActiveCases.php');

    // Grabs case_id of the just inserted case and uses it to set aio_id to current aio
    $userId =(int)$_SESSION['userId'];

    $CurrCaseId=(int)$_POST['CurrCaseId'];
    
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

    // Grabs case_id of the just inserted case and uses it to set aio id to null! 
    $userId =(int)$_SESSION['userId'];

    $CurrCaseId=(int)$_POST['CurrCaseId'];
    
    $AcceptCase = $conn->prepare("UPDATE active_cases SET aio_id = NULL  WHERE case_id = ?");
    $AcceptCase->bind_param("d",$CurrCaseId); //bind evidence folder name to the prepared statements
    if (!$AcceptCase->execute()) {
       echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
    }

    header('location: ../AIO/ActiveCases.php');
    
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
