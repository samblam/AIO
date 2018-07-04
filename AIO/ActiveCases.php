<?php
require_once '../includes/session.php';

require_once 'secure.php';
//Open the db connection
include '../includes/db.php';
//Check if the form variables have been submitted, store them in the session variables
include '../includes/formProcess.php';

?>

<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Portal</title>
        <link rel="stylesheet" href="../CSS/main.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
        <script src="../JS/top-header.js"></script>
    </head>
    <body style="margin: auto;">
        <!-- Headder div + Logout button -->
        <div class="top-header"></div>

        <div>
            <h2>Active Cases</h2>
        </div>
        
        <!-- Table div -->
        <div>
            <table class="table table-bordered" style="font-size: 12px;">
                <thead class="cases-table">
                    <tr>
                        <th>Student(s) Banner</th>
                        <th>Student(s) Name</th>
                        <th>Professor</th>
                        <th>Action required</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                      //Get all active cases assigned to this AIO and bind the returned database fields to php variables
            		  $statement = $conn->prepare("
                                                SELECT 
                                                    professor.fname, 
                                                    professor.lname, 
                                                    student.fname, 
                                                    student.lname, 
                                                    student.csid, 
                                                    active_cases.case_id 
                                                FROM 
                                                    professor 
                                                    LEFT JOIN active_cases ON professor.professor_id = active_cases.prof_id 
                                                    LEFT JOIN student ON student.case_id = active_cases.case_id 
                                                WHERE 
                                                    active_cases.aio_id = ?
                                                ");

            		  $statement->bind_param("d", $id); //bind the csid to the prepared statements

                      $id = (int)$_SESSION['userId'];//should be 'userId'
            		      if(!$statement->execute()){
                 		    echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
                      }
            		      $statement->bind_result($pfname, $plname, $sfname, $slname, $scsid, $caseId);

                      //Fetches each query result, one by one, and prints out a row for each case assigned to this AIO
                      while($statement->fetch()){
                        echo <<<ViewAllPost
                        <tr>
                          <td>$scsid</td>
                          <td>$sfname $slname</td>
                          <td>$pfname $plname</td>
                          <td><button class="custombtn btn btn-danger">Yes</button></td>
                          <td><a href="CaseInformation.php?case_id={$caseId}" class="btn btn-primary">View Case</a></td>
                        </tr>
ViewAllPost;
                      }
                      ?>
                </tbody>
                
                <br>
                
            </table>
        </div>
        
        <div>
            <h3>Unassigned cases</h3>
            
            <table class="table table-bordered" style="font-size: 12px;">
                
                <thead class="cases-table">
                    <tr>
                        <th>Class</th>
                        <th>Professor</th>
                        <th>View</th>
                    </tr>
                </thead>
                
                <tbody>
                    
                <?php
                    $query = $conn->prepare("
                                            SELECT 
                                                active_cases.case_id, 
                                                active_cases.class_name_code, 
                                                professor.fname, 
                                                professor.lname 
                                            FROM 
                                                professor 
                                                LEFT JOIN active_cases ON professor.professor_id = active_cases.prof_id 
                                                LEFT JOIN student ON student.case_id = active_cases.case_id 
                                            WHERE 
                                                active_cases.aio_id IS NULL
                                            ");
                    
                    if(!$query->execute()){
                 		 echo "Execute failed: (" . $query->errno . ") " . $query->error;
                    }
                    
                    $query->bind_result($uCaseId, $uClassName, $uProfessorFN, $uProfessorLN);
                    
                    while($query->fetch()){
                      echo <<<ViewAllPost
                      <tr>
                          <td>$uClassName</td>
                          <td>$uProfessorFN $uProfessorLN</td>
                          <td align="center"><a href="CaseInformation.php?case_id={$caseId}" class="btn btn-primary">View Case</a></td>
                      </tr>
                        
ViewAllPost;
                    }
                 ?>
                    
                </tbody> 
            </table>
        </div>
    </body>
</html>