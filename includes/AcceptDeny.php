<?php
    
  require_once 'session.php';
  include_once 'db.php';
  include_once 'fileFunctions.php';

  //The case where this Form A set aio_id 
  if(isset($_POST['AcceptFormA'])){
    $conn = OpenCon();
    
    // Grabs case_id of the just inserted case and uses it to set aio_id to current aio
    $csid = $_SESSION['csid'];
    $result = $conn->query( "SELECT aio_id FROM `aio` WHERE csid='$csid'" );
    if( !$result ) {
      echo "Database Error. Please contact admin.";
      echo $conn->error;
      exit();
    }
    $userId =(int)$result->fetch_assoc()['aio_id'];

    $CurrCaseId=(int)$_POST['CurrCaseId'];
    
    $CaseInfo = $conn->prepare("UPDATE active_cases SET aio_id = ?  WHERE case_id = ?");
    $CaseInfo->bind_param("dd", $userId,$CurrCaseId); //bind evidence folder name to the prepared statements
    if (!$CaseInfo->execute()) {
      echo "Execute failed: (" . $CaseInfo->errno . ") " . $CaseInfo->error;
    }

    CloseCon( $conn );
    header('location: ../AIO/ActiveCases.php');
  }

  //The case where this Form A set aio_id null
  if(isset($_POST['DenyFormA'])){
    $conn = OpenCon();

    // Grabs case_id of the just inserted case and uses it to set aio id to null
    $CurrCaseId=(int)$_POST['CurrCaseId'];
    
    $CaseInfo = $conn->prepare("UPDATE active_cases SET aio_id = NULL WHERE case_id = ?");
    $CaseInfo->bind_param("d",$CurrCaseId); //bind evidence folder name to the prepared statements
    
    if (!$CaseInfo->execute()) {
      echo "Execute failed: (" . $CaseInfo->errno . ") " . $CaseInfo->error;
    }

    CloseCon( $conn );
    header('location: ../AIO/ActiveCases.php');
    
  }
?>