<?php
    
require_once 'session.php';
include_once 'db.php';
include_once 'fileFunctions.php';

//The case where this Form A set aio_id 
  if(isset($_POST['AcceptFormA'])){
    
    
    // Grabs case_id of the just inserted case and uses it to set aio_id to current aio
    $userId =(int)$_SESSION['csid'];

    $CurrCaseId=(int)$_POST['CurrCaseId'];
    
    $CaseInfo = $conn->prepare("UPDATE active_cases SET aio_id = ?  WHERE case_id = ?");
    $CaseInfo->bind_param("dd", $userId,$CurrCaseId); //bind evidence folder name to the prepared statements
      if (!$CaseInfo->execute()) {
        echo "Execute failed: (" . $CaseInfo->errno . ") " . $CaseInfo->error;
      }

        header('location: ../AIO/ActiveCases.php');
    
  }

//The case where this Form A set aio_id null
  if(isset($_POST['DenyFormA'])){

    // Grabs case_id of the just inserted case and uses it to set aio id to null
    $userId =(int)$_SESSION['csid'];

    $CurrCaseId=(int)$_POST['CurrCaseId'];
    
    $CaseInfo = $conn->prepare("UPDATE active_cases SET aio_id = NULL WHERE case_id = ?");
    $CaseInfo->bind_param("d",$CurrCaseId); //bind evidence folder name to the prepared statements
    
      if (!$CaseInfo->execute()) {
        echo "Execute failed: (" . $CaseInfo->errno . ") " . $CaseInfo->error;
      }

        header('location: ../AIO/ActiveCases.php');
    
  }
?>