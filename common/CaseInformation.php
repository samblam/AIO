<?php
require_once '../includes/session.php';

require_once 'secure.php';
//Open the db connection
include_once '../includes/db.php';
//Check if the form variables have been submitted, store them in the session variables
include '../includes/formProcess.php';
include_once '../includes/page.php';

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
            <h2>Case Information</h2>

        </div>
        
        <!-- Table div -->
        <!-- TODO: Table will need to populate based on the entries in the DB(server side) -->
        <!-- TODO: I think to properly link the buttons, each row might have to be an input form(haven't looked it up) -->
        <div>
            
            <?php

                //Get all relevent feilds and bind them to php variables
                $caseIdValue = $_POST['caseId'];
                $statement = $conn->prepare("
                SELECT 
                    active_cases.evidence_fileDir,
                    active_cases.form_a_submit_date,
                    active_cases.stu_csid_list,
                    professor.fname, 
                    professor.lname, 
                    student.fname, 
                    student.lname, 
                    student.csid 
                FROM 
                    professor 
                    LEFT JOIN active_cases ON professor.professor_id = active_cases.prof_id 
                    LEFT JOIN student ON student.case_id = $caseIdValue
                WHERE 
                    active_cases.case_id = $caseIdValue
                    ");

                if(!$statement->execute()){
                    echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
                }
                $statement->bind_result($evidenceDir, $submissionDate, $studentList, $pfname, $plname, $sfname, $slname, $scsid);
                $statement->fetch();

            echo <<<DisplayInfo

            <table class="table table-bordered" style="font-size: 14px;">
                <tbody>
                    <tr>
                        <td>Banner number</td>
                        <td>$scsid</td>
                    </tr>
                    <tr>
                        <td>Student name</td>
                        <td class="studentName">$sfname $slname</td>
                    </tr>
                    <tr>
                        <td>Other Students</td>
                        <td>
                            <!-- Still needs to be done -->
                             <div class="dropdown">
                             <form method="post" action="student-case-information.php">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" style="font-size: 12px;" data-toggle="dropdown">Other Students
                                <span class="caret"></span></button>
                                    <ul class="dropdown-menu" onchange="warning()">
                                        <li><a href="../common/student-case-information.php">$sfname, $scsid</a></li>
DisplayInfo;
                                        while($statement->fetch()){
                                            echo"<input id='caseId' name='caseId' value='$caseIdValue' type='hidden'>";
                                            echo "<li><a type='submit'> $sfname, $scsid</a></li>";
                                        }
            echo <<<DisplayInfo
                                </ul>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Professor</td>
                        <td>$pfname $plname</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <!-- is this current date or case submitted date or allegation date? needs backend-->
                        <td>$submissionDate</td>
                    </tr>
                    <tr>
                        <td>Files</td>
                        <!-- this needs evidence files -->
                        <td><a href="$evidenceDir">Link.zip</a></td>
                    </tr>

                    <tr>
                        <td>Case status</td>
                        <!-- needs to come from backend -->
                        <td>Waiting for student to confirm meeting date</td>
                    </tr>

                </tbody>
            </table>
        </div>
DisplayInfo;
                ?>

            
        <!-- CLose case and insufficient evidence buttons -->
        <div class="center-block text-center">
            <?php
                //Gets case id from URL
                $caseId = $_POST['caseId'];
            
                //Get case verdict from db
                $statement = $conn->prepare("SELECT case_verdict FROM active_cases WHERE case_id = '$caseId' AND aio_id = ?"); 
                $statement->bind_param("d", $id); //bind the csid to the prepared statements

                $id = (int)$_SESSION['userId'];
                if(!$statement->execute()){
                    echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
                }
            
                $statement->bind_result($caseVerdict);
                
                while($statement->fetch()){
                    echo"<h1> case verdict: $caseVerdict";
                    if($caseVerdict == NULL){
                        // Insufficient Evidence Button
                        echo <<<ViewAllPost
                            <form class="delete_this_case" method="post" action="AioActiveCases.php" onclick="return confirm('Are you sure you want to remove this case for insufficient evidence? This will permanently delete the case.\\nClick OK to continue.')">
                                <input type="text" name="case_id" value="$caseId" hidden>
                                <button class="btn btn-danger" value="true" type="submit" name="insufficientEvidence">Insufficient Evidence</button>
                            </form>
ViewAllPost;
                    }
                    else if ($caseVerdict == "guilty"){
                        // Close case Button guilty
                        echo <<<ViewAllPost2
                            <form class="delete_this_case" method="post" action="AioActiveCases.php" onclick="return confirm('Are you sure you want to close this case? \\nIf the verdict is guilty the case gets archived in our system, and if the verdict is not guilty the case is permanently deleted. \\nClick OK to continue.')">
                                <input type="text" name="case_id" value="$caseId" hidden>
                                <button class="btn btn-danger" value="true" type="submit" name="closeCaseGuilty">Close Case</button>
                            </form>
ViewAllPost2;
                    }
                    else if ($caseVerdict == "not guilty"){
                        // Close case Button not guilty
                        echo <<<ViewAllPost3
                            <form class="delete_this_case" method="post" action="AioActiveCases.php" onclick="return confirm('Are you sure you want to close this case? \\nIf the verdict is guilty the case gets archived in our system, and if the verdict is not guilty the case is permanently deleted. \\nClick OK to continue.')">
                                <input type="text" name="case_id" value="$caseId" hidden>
                                <button class="btn btn-danger" value="true" type="submit" name="closeCaseNotGuilty">Close Case</button>
                            </form>
ViewAllPost3;
                    }
                }
            ?>
            
            
        </div>
            
        <!-- Form display div -->
        <div>
            <ul class="nav nav-tabs nav-justified">

                <li class="active"><a data-toggle="tab" href="#forma">Form A</a></li>
                <?php
                    if($_SESSION['role'] != "professor"){
                        echo<<<DisplayFormTabs
                        <li><a data-toggle="tab" href="#formb">Form B</a></li>
                        <li><a data-toggle="tab" href="#formc">Form C</a></li>
                        <li><a data-toggle="tab" href="#formd">Form D</a></li>
DisplayFormTabs;
                    }
                ?>
            </ul>

            <div class="tab-content">
                <!-- Not sure why form A is loaded here? Someone who knows should check... - Bjorn -->
                <div id="forma" class="tab-pane fade active in">
                    <?php include 'forma.php' ?>
                </div>
                
                
                <?php
                    //if it's a professor visiting, only display from A 
                    if($_SESSION['role'] != "professor"){
                        
                    echo <<<DisplayForms
                        <div id="formb" class="tab-pane fade">
                            <?php include '../AIO/formb.php' ?>
                        </div>

                        <div id="formc" class="tab-pane fade">
                            <?php include '../AIO/formc.php' ?>
                        </div>

                        <div id="formd" class="tab-pane fade">
                            <?php include '../AIO/formd.php' ?>
                        </div>
DisplayForms;
            
                    }
                ?>
            </div>
        </div>
        </div>
    </body>
</html>
