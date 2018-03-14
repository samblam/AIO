<?php

//This makes it so no one can submit forms at the moment
if(basename($_SERVER['PHP_SELF'])=="formProcess.php")
header("location: ../index.php");

//this needs the prepare statements for inserting the new case and getting
//the case id of the newly inserted case in order to upload the files.
//Also need to decide on max upload size
if(isset($_POST['SubmitFormA'])){
  $target_dir= "../evidence/";
  $target_file= $target_dir.basename($_FILES["file"]["name"]);
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

  $prof = htmlspecialchars(trim(stripslashes($_POST['ProfessorName'])));
  $email = htmlspecialchars(trim(stripslashes($_POST['email'])));
  $phone = htmlspecialchars(trim(stripslashes($_POST['phoneNum'])));
  $faculty = htmlspecialchars(trim(stripslashes($_POST['faculty'])));
  $depart = htmlspecialchars(trim(stripslashes($_POST['dept'])));
  $cname = htmlspecialchars(trim(stripslashes($_POST['coursePicker'])));
  
  //Need some mechanism to get multiple students
  $sname = htmlspecialchars(trim(stripslashes($_POST[''])));
  $banner = htmlspecialchars(trim(stripslashes($_POST[''])));
  
  $offenseDate = htmlspecialchars(trim(stripslashes($_POST['date'])));
  //get file upload working
  $description = htmlspecialchars(trim(stripslashes($_POST['additionalComments'])));    
  
//prepare the query to authenticate csid's
  /*$statement = $conn->prepare("INSERT INTO case (prof_id, class_name_code, date_aware, description, evidence_fileDir) VALUES (?, ?, ?, ?, ?)");
  $statement->bind_param("s",$csid); //bind the csid to the prepared statements
  if (!$statement->execute()) {
     echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  } */
  $target_file = $target_dir."CASEID/".basename($_FILES["file"]["name"]);

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



