<?php
session_start();
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

        <script>
            function warning()
            {
                 confirm("this will delete the file permanently!");
            }
        </script>

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
                    $statement = $conn->prepare("SELECT active_cases.case_id, active_cases.class_name_code, professor.fname, professor.lname, aio.fname, aio.lname  FROM professor, active_cases, aio WHERE professor.professor_id = active_cases.prof_id AND aio.aio_id = active_cases.aio_id");
                    //$statement->bind_param("d", $id); //bind the csid to the prepared statements

                    //$id = (int)$_SESSION['userId'];

                    if(!$statement->execute()){
                      echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
                    }
                    $statement->bind_result($caseId, $className, $pfname, $plname, $afname, $alname);
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
                                  <ul class="dropdown-menu" onchange="warning()">

                                      <li><a href="CaseInformation.php?case_id={$caseId}">View</a></li>
                                      <li><a href="ChangeAIO.php">Change AIO</a></li>
                                      <li><button onclick="warning()" style="background-color: red" color="black" class="btn btn-link">Delete</button></li>
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
