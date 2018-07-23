<?php
require_once '../includes/session.php';

require_once 'secure.php';
//Open the db connection
include '../includes/db.php';
//Check if the form variables have been submitted, store them in the session variables
include '../includes/formProcess.php';
?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Admin</title>
        <link rel="stylesheet" href="../CSS/formA.css">

        <link rel="stylesheet" href="../CSS/main.css">

      <!-- bootstrap imports -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


        <!-- the header; logout and back buttons -->
        <script src="../JS/top-header.js"></script>
    </head>

     <body style="margin: auto;">
        <!-- Headder div + Logout button -->
        <div class="top-header"></div>
        <div>
            <h2>Active Cases</h2>
        </div>
         
         <div>
            <span class="pull-right" style="display: inline-block;"> 
                <button class="btn btn-success" onclick="location.href='forma.php?ProfRequired=true'" style="font-size: 16px; vertical-align: bottom;">Submit new case</button>
            </span>
         </div>
         
        <div>
            <table class="table table-bordered" style="font-size: 12px;">
                <thead class="cases-table">
                    <tr>
                        <!--should we include a section to indicate the case has been rejected or accepted by the senate? Or rejected by the AIO (case closed), so the case can be deleted. Also, the cases should be ordered by newest cases first, or by whichever field they choose.-->
                        <th>Case ID</th>
                        <th>Course</th>
                        <th>Professor Name</th>
                        <th>AIO Name</th>
                        <th>Action Required</th>
                        <th>Action Needed By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                  <?php
                    //Get all active cases and bind the returned database fields to php variables
                    $statement = $conn->prepare("SELECT active_cases.case_id, 
                                                        active_cases.class_name_code, 
                                                        professor.fname, 
                                                        professor.lname, 
                                                        aio.fname, 
                                                        aio.lname  
                                                    FROM active_cases 
                                                    LEFT JOIN professor 
                                                    ON professor.professor_id = active_cases.prof_id 
                                                    LEFT JOIN aio 
                                                    ON aio.aio_id = active_cases.aio_id");
                    if(!$statement->execute()){
                      echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
                    }
                    $statement->bind_result($caseId, $className, $pfname, $plname, $afname, $alname);

                    /**
                     * Fetches each query result, one by one, and prints out a row for each case.
                     *
                     * Delete button uses the onclick attribute to create a confirm popup.
                     * If you click yes, the form will continue and it will delete the case.
                     * If you click no, nothing will happen.
                     */
                    while($statement->fetch()){
                      echo <<<ViewAllPost
                      <tr>
                          <td>$caseId</td>
                          <td>$className</td>
                          <td>$pfname $plname</td>
                          <td>$afname $alname</td>
                          <td>No</td>
                          <td>N/A</td>
                          <!-- drop-down action choices -->
                          <td>
                              <div class="dropdown">
                                  <button class="btn btn-default dropdown-toggle" type="button" style="font-size: 12px;" data-toggle="dropdown">Actions
                                  <span class="caret"></span></button>
                                  <ul class="dropdown-menu">

                                    <form method="post" action="CaseInformation.php">
                                        <input type="hidden" id="caseId" name="caseId" value="$caseId"/>
                                        <button class='btn' type='submit'>ViewCase</button>
                                    </form>
                                      <li>
                                        <form method="post" action="ChangeAIO.php">
                                            <input type="hidden" id="caseId" name="caseId" value="$caseId"/>
                                            <button class='btn' type='submit'>Change AIO</button>
                                        </form>
                                      <li>

                                        <form class="delete_this_case" method="post" action="ActiveCases.php" onclick="return confirm('Are you sure you want to remove this case? This will permanently delete this case.\\nClick OK to continue.')">
                                            <input type="text" name="case_id" value="$caseId" hidden>
                                            <button value="true" type="submit" class='btn border' style="background-color: red" name="deleteCase">Delete</button>
                                        </form>
                                      </li>
                                  </ul>
                              </div>
                          </td>
                      </tr>
ViewAllPost;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </body>
</html>
