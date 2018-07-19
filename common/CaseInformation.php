<?php
require_once '../includes/session.php';

require_once 'secure.php';
//Open the db connection
include_once '../includes/db.php';
//Check if the form variables have been submitted, store them in the session variables
include '../includes/formProcess.php';
include_once '../includes/page.php';

$path_to_evidence_dir = "";
$aio_id = "";
$prof_id = "";

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
        <title>Student Case</title>
        <link rel="stylesheet" href="../CSS/main.css">
        <script src="../JS/top-header-full.js"></script>
    </head>
    <body style="margin: auto;">
        <!-- Headder div + Logout button -->
        <div class="top-header-full"></div>

        <div style="display: inline-block;">
            <h2>Case Information - <p class="studentName"></p></h2>

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
                        <td>Banner number</td>
                        <td>B00000000</td>
                    </tr>
                    <tr>
                        <td>Student name</td>
                        <!-- needs to grab from backend -->
                        <td class="studentName">Mark Auto</td>
                    </tr>
                    <tr>
                        <td>Other Students</td>
                        <td>
                             <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" style="font-size: 12px;" data-toggle="dropdown">Other Students
                                <span class="caret"></span></button>
                                <ul class="dropdown-menu" onchange="warning()">
                                    <!-- needs to add an <li> tage for other students in the case upon loading page; BACKEND -->
                                    <li><a href="student-case-information.html"> TestStudent Name</a></li>
                                    <li><a href="#"> TestStudent Name</a></li>

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
                        <!-- is this current date or case submitted date or allegation date? needs backend-->
                        <td class="date">Jan 31st 2017</td>
                    </tr>
                    <tr>
                        <td>Files</td>
                        <?php
                            // user has permission to view evidence files if:
                            // user is AIO and AIO id for this case matches user's id
                            // OR user is professor and professor id for this case matches user's id
                            // OR user is an admin

                            $role = $_SESSION["role"];

                            if ($role == "aio" && $aio_id == $_SESSION["userId"] || $role == "professor" && $prof_id == $_SESSION["userId"] || $role == "admin"){
                                if ($path_to_evidence_dir != "" && file_exists("../evidence/" . $path_to_evidence_dir . "/evidence.zip")) {
                                    $path_to_zip_file = "../evidence/" . $path_to_evidence_dir . "/evidence.zip";
                                    echo "<td><a href=\"" . $path_to_zip_file . "\" download>evidence.zip</a></td>";
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
                        <!-- needs to come from backend -->
                        <td>Waiting for student to confirm meeting date</td>
                    </tr>

                </tbody>
            </table>
        </div>


        <!-- Form display div -->
        <div>
            <ul class="nav nav-tabs nav-justified">

                <li class="active"><a data-toggle="tab" href="#forma">Form A</a></li>
                <li><a data-toggle="tab" href="#formb">Form B</a></li>
                <li><a data-toggle="tab" href="#formc">Form C</a></li>
                <li><a data-toggle="tab" href="#formd">Form D</a></li>
            </ul>

            <div class="tab-content">
                <!-- Not sure why form A is loaded here? Someone who knows should check... - Bjorn -->
                <div id="forma" class="tab-pane fade active in">
                    <?php include '../common/forma.php' ?>
                </div>

                <div id="formb" class="tab-pane fade">
                    <?php include 'formb.php' ?>
                </div>

                <div id="formc" class="tab-pane fade">
                    <?php include 'formc.php' ?>
                </div>

                <div id="formd" class="tab-pane fade">
                    <?php include 'formd.php' ?>
                </div>

            </div>
        </div>
    </body>
</html>
