<?php
require_once '../includes/session.php'; 
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
        <!-- TODO: Table will need to populate based on the entries in the DB(server side) -->
        <!-- TODO: I think to properly link the buttons, each row might have to be an input form(haven't looked it up) -->
        <div>
            <span class="pull-right" style="display: inline-block;">
                <button class="btn btn-success" onclick="location.href='forma.php'" style="font-size: 16px; vertical-align: bottom;">Submit new case</button>
            </span>

            <table class="table table-bordered" style="font-size: 12px;">
                <thead class="cases-table">
                    <tr>
                        <th>Student Banners</th>
                        <th>Student Names</th>
                        <th>AIO</th>
                        <th>Action required</th>
                        <th>Status</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                  <?php
                    //Get all active cases created by this professor and bind the returned database fields to php variables
                    $statement = $conn->prepare("SELECT aio.fname, aio.lname, student.fname, student.lname, student.csid, active_cases.case_id, active_cases.form_a_submit_date FROM active_cases LEFT JOIN student ON student.case_id = active_cases.case_id LEFT JOIN aio ON aio.aio_id = active_cases.aio_id WHERE active_cases.prof_id = ?");
                    $statement->bind_param("d", $id); //bind the csid to the prepared statements
                    $id = (int)$_SESSION['userId'];
                    if(!$statement->execute()){
                      echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
                    }
                    $statement->bind_result($afname, $alname, $sfname, $slname, $scsid, $caseId, $subDate);

                    //Fetches each query result, one by one, and prints out a row for each active case created by this professor
                    while($statement->fetch()){
                      $submitted = "Submitted";
                      if($subDate == NULL){
                        $submitted = "Not Submitted";
                      }
                      echo <<<ViewAllPost
                      <tr>
                        <td>$scsid</td>
                        <td>$sfname $slname</td>
                        <td>$afname $alname</td>
                        <td><button class="custombtn btn btn-danger">Yes</button></td>
                        <td>$submitted</td>
                        <td><a href="forma.php?case_id={$caseId}" class="btn btn-primary">View Case</a></td>
                      </tr>
ViewAllPost;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </body>
</html>
