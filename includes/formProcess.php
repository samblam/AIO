<?php

include 'db.php';
//This makes it so no one can submit forms at the moment
//if(basename($_SERVER['PHP_SELF'])=="formProcess.php")
//header("location: ../index.php");

//Also need to decide on max upload size
if(isset($_POST['SubmitFormA'])){

  //This section gets the file ready to upload but doesnt upload it yet because we don't yet know where to put it
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
  $offenseDate = htmlspecialchars(trim(stripslashes($_POST['date'])));
  $description = htmlspecialchars(trim(stripslashes($_POST['additionalComments'])));

  //Create new case entry
  $statement = $conn->prepare("INSERT INTO case (prof_id, class_name_code, date_aware, description,"/* evidence_fileDir*/.") VALUES (?, ?, ?, ?, ?)");
  $statement->bind_param("sssss",$_SESSION['userId'], $cname, $date, $comments); //bind initial values to the prepared statements
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

}

?>
