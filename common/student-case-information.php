<?php
require_once '../includes/session.php';
//Open the db connection
include_once '../includes/db.php';
//Check if the form variables have been submitted, store them in the session variables
include '../includes/formProcess.php';
include_once '../includes/page.php';

$path_to_evidence_dir = "";
$aio_id = "";
$prof_id = "";
$caseId = "";

$role = $_SESSION["role"];
$userId = (int) $_SESSION["userId"];


if(isset($_GET['case_id'])){
    //Gets case id from URL
    $caseId = intval($_GET['case_id']);

    $statement = $conn->prepare("SELECT evidence_fileDir, aio_id, prof_id FROM active_cases WHERE case_id = " . $caseId);
    if(!$statement->execute()){
        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
    }

    $statement->bind_result($path_to_evidence_dir, $aio_id, $prof_id);
    $statement->fetch();

    CloseCon($conn);
    $conn = OpenCon();
}

?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Portal</title>
        <link rel="stylesheet" href="../CSS/main.css">
        <link rel="stylesheet" href="../CSS/caseInformation.css">
        <script src="../JS/top-header-full.js"></script>
    </head>
    <body style="margin: auto;">
        <!-- Headder div + Logout button -->
        <div class="top-header-full"></div>

        <div style="display: inline-block;">
            <h2>Case Information</h2>

        </div>

        <!-- Newcase button div -->
        <!-- TODO: Should link to from A? -->

        <!-- Table div -->
        <!-- TODO: Table will need to populate based on the entries in the DB(server side) -->
        <!-- TODO: I think to properly link the buttons, each row might have to be an input form(haven't looked it up) -->
        <div>
            <table class="table table-bordered" style="font-size: 14px;">
                <tbody>
                    <tr>
                        <td>Case Number</td>
                        <!-- needs to pull from database -->
                        <td>000000</td>
                    </tr>
                    <tr>
                        <td>Student(s) name</td>
                        <!-- needs to pull all students names from backend -->
                        <td>

                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" style="font-size: 12px;" data-toggle="dropdown">Students
                                <span class="caret"></span></button>
                                <ul class="dropdown-menu" onchange="warning()">
                                    <!-- needs to add an <li> tage for each student in the case upon loading page; BACKEND -->
                                    <li><a href="CaseInformation.php"> TestStudent Name</a></li>

                                </ul>
                            </div>
                        </td>

                    </tr>
                    <tr>
                        <td>Professor</td>
                        <td>Fred</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>Jan 31st 2017</td>
                    </tr>
                    <tr>
                        <td>Files</td>
                        <?php
                            // user has permission to view evidence files if:
                            // user is AIO and AIO id for this case matches user's id
                            // OR user is professor and professor id for this case matches user's id
                            // OR user is an admin

                            if ($role == "aio" && $aio_id == $userId || $role == "professor" && $prof_id == $userId || $role == "admin"){
                                if ($path_to_evidence_dir != "" && file_exists("../evidence/" . $path_to_evidence_dir . "/evidence.zip")) {
                                    // user should be shown the link to the evidence file
                                    $path_to_zip_file = "../evidence/" . $path_to_evidence_dir . "/evidence.zip";
                                    echo "<td><form action=\"/downloadRequest.php\" method=\"post\">";
                                    echo "<input hidden name=\"caseId\" id=\"caseId\" value=\"$caseId\"/>";
                                    echo "<input type=\"submit\" class=\"submitLink\" name=\"evidenceLink\" value=\"evidence.zip\"/>";
                                    echo "</form></td>";
                                }

                                else {
                                    // no evidence has been submitted
                                    echo "<td>No evidence submitted</td>";
                                }
                            }

                            else{
                                // viewer of the page does not meet the permission requirements to view the evidence
                                echo "<td>Insufficient permission to view evidence</td>";
                            }
                        ?>
                    </tr>
                    <tr>
                        <td>Case status</td>
                        <td>Ongoing</td>
                    </tr>
                </tbody>
            </table>   
        </div>
        <!-- TODO: Add verdict column to active cases table and pull the verdict for the case. If the verdict is null only show Insufficient evidence button, if the verdict is not null only show close case button and either delete or archive the case based on the verdict. -->

        <!-- Form display div -->
        
        <!-- This should be displaying the form of the selected student -->
        <div>
            <ul class="nav nav-tabs nav-justified">
                <li class="active"><a data-toggle="tab" href="#forma">Form A</a></li>
            </ul>
            <div class="tab-content">
                <div id="forma" class="tab-pane fade active in">
                    <?php include '../common/forma.php' ?>
                </div>
            </div>
        </div>
    </body>
</html>
