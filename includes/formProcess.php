<?php

include_once 'db.php';
//This makes it so no one can submit forms at the moment
//if(basename($_SERVER['PHP_SELF'])=="formProcess.php")
//header("location: ../index.php");

//Form A processing
if(isset($_POST['SaveFormA']) || isset($_POST['SubmitFormA'])){
  //This block of code gets the file ready to upload but doesnt upload it yet because we don't yet know where to put it
  $target_dir= "../evidence/";
  $target_file= $target_dir.basename($_FILES["fileInput"]["name"]);
  $uploadAllowed = false;
  if($target_file != $target_dir){
    $finfo= finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES["fileInput"]["tmp_name"]);
    switch ($mime) {
      case '':// Allowed mimes
      case '':// Allowed mimes
        $uploadAllowed = true;
        break;
      default:
        die("Unknown file type. Upload not permitted.");
    }
    if($_FILES["image"]["size"] >2097152) { // Check size of upload. 2097152 = 2MB. Will probs want to change
      die("File too large. Upload not permitted.");
    }
  }
  $evidence = "";
  if($target_file != $target_dir){
    $evidence = htmlspecialchars(trim(stripslashes($target_file)));
  }

  //Grabs all form data and sanatize it
  $prof = htmlspecialchars(trim(stripslashes($_POST['ProfessorName'])));
  $email = htmlspecialchars(trim(stripslashes($_POST['email'])));
  $phone = htmlspecialchars(trim(stripslashes($_POST['phoneNum'])));
  $faculty = htmlspecialchars(trim(stripslashes($_POST['faculty'])));
  $depart = htmlspecialchars(trim(stripslashes($_POST['dept'])));
  $cname = htmlspecialchars(trim(stripslashes($_POST['coursePicker'])));
  $students = htmlspecialchars(trim(stripslashes($_POST['students'])));
  $boos = htmlspecialchars(trim(stripslashes($_POST['boos'])));
  $date = htmlspecialchars(trim(stripslashes($_POST['date'])));
  $comments = htmlspecialchars(trim(stripslashes($_POST['additionalComments'])));

  //The case where a new Form A is created and is submitted
  if(isset($_POST['SubmitFormA'])){

    //If you are submitting a form A, it cant have multiple students with the same csid.
    //So, return to the form
    if(count($boos) != count(array_unique($boos)) && isset($_GET['case_id'])){
      header("locaton: ../forma.php?multiIds=true&case_id={$_GET['case_id']}");
    }
    elseif(count($boos) != count(array_unique($boos)) && !isset($_GET['case_id'])){
      header("locaton: ../forma.php?multiIds=true");
    }

    //Create new case entry
    $statement = $conn->prepare("INSERT INTO case (prof_id, class_name_code, date_aware, description, form_a_submit_date) VALUES (?, ?, ?, ?, ?, ?)");
    $statement->bind_param("sssss",$_SESSION['userId'], $cname, $date, $comments, date()); //bind initial values to the prepared statements
    if (!$statement->execute()) {
       echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    //Grabs case_id of the just inserted case and uses it to create the evidence folder location in the database
    $caseId = $conn->insert_id;
    $updateEvidence = $conn->prepare("UPDATE case SET evidence_fileDir = ? WHERE case_id = ".$caseId);
    $updateEvidence->bind_param("s", $caseId); //bind evidence folder name to the prepared statements
    if (!$updateEvidence->execute()) {
       echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    //Insert students into student table
    for($i = 0; $i < sizeof($students); $i++) {
      if($students[$i] != NULL && $boos[$i] != NULL){
        $statement = $conn->prepare("INSERT INTO student (csid, case_id, fname) VALUES (?, ?, ?)");
        $statement->bind_param("ssss",$boos[$i], $caseId, $student[$i]); //bind initial values to the prepared statements
        if (!$statement->execute()) {
           echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
      }
    }
  }

  //The case where this Form A has already been saved before and needs to be just saved again
  if(isset($_POST['SaveFormA']) && !isset($_GET['case_id'])){
    //Create new case entry
    $statement = $conn->prepare("INSERT INTO case (prof_id, class_name_code, date_aware, description) VALUES (?, ?, ?, ?)");
    $statement->bind_param("ssss",$_SESSION['userId'], $cname, $date, $comments); //bind initial values to the prepared statements
    if (!$statement->execute()) {
       echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    //Grabs case_id of the just inserted case and uses it to create the evidence folder location in the database
    $caseId = $conn->insert_id;
    $updateEvidence = $conn->prepare("UPDATE case SET evidence_fileDir = ? WHERE case_id = ".$caseId);
    $updateEvidence->bind_param("s", $caseId); //bind evidence folder name to the prepared statements
    if (!$updateEvidence->execute()) {
       echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    //Insert students into student table
    for($i = 0; $i < sizeof($students); $i++) {
      if($students[$i] != NULL && $boos[$i] != NULL){
        $statement = $conn->prepare("INSERT INTO student (csid, case_id, fname) VALUES (?, ?, ?)");
        $statement->bind_param("ssss",$boos[$i], $caseId, $student[$i]); //bind initial values to the prepared statements
        if (!$statement->execute()) {
           echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
      }
    }
  }

  //The case where a new Form A is created but saved instead of submitted
  if(isset($_POST['SaveFormA']) && isset($_GET['case_id'])){
    //Create new case entry
    $statement = $conn->prepare("UPDATE case SET prof_id = ?, class_name_code = ?, date_aware = ?, description = ? WHERE case_id = {$_GET['case_id']}");
    $statement->bind_param("ssss",$_SESSION['userId'], $cname, $date, $comments); //bind initial values to the prepared statements
    if (!$statement->execute()) {
       echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    //Select students from this case
    $statement = $conn->prepare("SELECT fname, csid FROM student WHERE case_id = ?");
    $statement->bind_param("d", (int)$_GET['case_id']);
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
          $statement->bind_param("sss", $boos[$i], $caseId, $student[$i]); //bind initial values to the prepared statements
        }
        elseif(!in_array($currStudents[$boos[$i]], $students)){
          $statement = $conn->prepare("DELETE FROM student WHERE case_id = {$_GET['case_id']} AND csid = {$boos[$i]}");
        }

        if (!$statement->execute()) {
           echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
      }
    }
  }

  //Gets the necessary directory location and then uploads the evidence to it
  $target_file = $target_dir.$caseId."/".$_FILES["file"]["name"];
  if ($uploadAllowed) {
    if (move_uploaded_file($_FILES["fileInput"]["tmp_name"], $target_file)) {
      echo"File uploaded successfully.";
    }
    else {
      echo"Error uploading file.";
    }
  }
  if($_SESSION['role'] == "professor"){
    header('location: ../HTML/ProfessorActiveCases.php');
  }
  elseif ($_SESSION['role'] == "admin") {
    header('location: ../HTML/AdminActiveCases.php');
  }
  else{
    header('location: ../HTML/AioActiveCases.php');
  }
}
if(isset($_POST['SaveFormB']) || isset($_POST['SubmitFormB'])){

}

if(isset($_POST['deleteCase']) && isset($_POST['case_id']) && basename($_SERVER['PHP_SELF']) == "AdminActiveCases.php"){
  $id = htmlspecialchars(trim(stripslashes($_POST['case_id'])));

  $conn->query("DELETE FROM student WHERE case_id = \"$id\"");
  $conn->query("DELETE FROM active_cases WHERE case_id = \"$id\"");
}




?>
