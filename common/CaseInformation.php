<?php
require_once '../includes/session.php';

require_once 'secure.php';
//Open the db connection
include_once '../includes/db.php';
//Check if the form variables have been submitted, store them in the session variables
include '../includes/formProcess.php';
include_once '../includes/page.php';
echo"<script>";
include '../JS/formLoader.js';
echo"</script>";

$role = $_SESSION["role"];
$userId = (int) $_SESSION["userId"];

// get information related to evidence files that have been submitted for this case
$path_to_evidence_dir = "";
$aio_id = "";
$prof_id = "";
$caseId = "";

if(isset($_POST['caseId'])){
    //Gets case id from URL
    $caseId = intval($_POST['caseId']);

    $statement = $conn->prepare("SELECT evidence_fileDir, aio_id, prof_id FROM active_cases WHERE case_id = " . $caseId);
    if(!$statement->execute()){
        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
    }

    $statement->bind_result($path_to_evidence_dir, $aio_id, $prof_id);
    $statement->fetch();

    CloseCon($conn);
}

?>

<!DOCTYPE html>
<html> 

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Student Case</title>
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
        
        <!-- Table div -->
        <!-- TODO: Table will need to populate based on the entries in the DB(server side) -->
        <!-- TODO: I think to properly link the buttons, each row might have to be an input form(haven't looked it up) -->
        <div>
            
            <?php
                //Get all relevant fields and bind them to php variables
                $conn = OpenCon();

                //Get all relevant fields and bind them to php variables
                $caseIdValue = $_POST['caseId'];
                $statement = $conn->prepare("
                SELECT
                    active_cases.form_a_submit_date,
                    active_cases.stu_csid_list,
                    professor.fname, 
                    professor.lname,
					student.student_id,
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

                $statement->bind_result($submissionDate, $studentList, $pfname, $plname, $studentID, $sfname, $slname, $scsid);
                $statement->fetch();		//Get first student
            ?>

            <table class="table table-bordered" style="font-size: 14px;">
                <tbody>
                    <tr>
                        <td>Banner number</td>
                        <td><?php echo $scsid ?></td>
                    </tr>
                    <tr>
                        <td>Student name</td>
                        <td class="studentName"><?php echo $sfname . ", " . $scsid ?></td>
                    </tr>
                    <tr>
                        <td>Other Students</td>
                        <td>
                            <!-- Still needs to be done -->
                            <div class="dropdown">
                                <form method="post" action="student-case-information.php">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" style="font-size: 12px;" data-toggle="dropdown">
                                        Other Students
                                        <span class="caret"></span>
                                    </button>

                                    <ul class="dropdown-menu" onchange="warning()">
                                        <input id='caseId' name='caseId' value='$caseIdValue' type='hidden'>
                                        <li><button class='btn' type='submit'><?php echo $sfname . ", " . $scsid ?></button></li>
                                        <?php
											$num_students = 1;
                                            while($statement->fetch()){
												$num_students++;
                                                echo"<input id='caseId' name='caseId' value='$caseIdValue' type='hidden'>";
                                                echo "<li><button class='btn' type='submit'>$sfname, $scsid</button></li>";
                                            }
                                        ?>
                                    </ul>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Professor</td>
                        <td><?php echo $pfname . " " . $plname ?></td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <!-- is this current date or case submitted date or allegation date? needs backend-->
                        <td><?php echo $submissionDate ?></td>
                    </tr>
                    <tr>
                        <td>Files</td>
                        <?php
                            // user has permission to view evidence files if:
                            // user is an AIO and the AIO id assigned to this case matches user's id
                            // OR user is a professor and the professor id for this case matches user's id
                            // OR user is an admin

                            if ( ($role == "aio" && $aio_id == $userId) || ($role == "professor" && $prof_id == $userId) || ($role == "admin") ){
                                if ($path_to_evidence_dir != "" && file_exists("../evidence/" . $path_to_evidence_dir . "/evidence.zip")) {
                                    // user should be shown the link to the evidence file
                                    $path_to_zip_file = "../evidence/" . $path_to_evidence_dir . "/evidence.zip";
                                    echo "<td>
                                            <form action=\"/downloadRequest.php\" method=\"post\">
                                                <input hidden name=\"caseId\" id=\"caseId\" value=\"$caseId\"/>
                                                <input type=\"submit\" class=\"submitLink\" name=\"evidenceLink\" value=\"evidence.zip\"/>
                                            </form>
                                        </td>";
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
            
        <!-- CLose case and insufficient evidence buttons -->
        <div class="center-block text-center">
            <?php

                if($caseId != "" && $role == "aio"){
                    //Get case verdict from db
                    $statement = $conn->prepare("SELECT case_verdict FROM active_cases WHERE case_id = '$caseId' AND aio_id = ?"); 
                    $statement->bind_param("d", $id); //bind the csid to the prepared statements

                    $id = (int)$_SESSION['userId'];
                    if(!$statement->execute()){
                        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
                    }
                
                    $statement->bind_result($caseVerdict);
				}

                $id = (int)$_SESSION['userId'];
                if(!$statement->execute()){
                    echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
                }
            
                $statement->bind_result($caseVerdict);
                  
                    while($statement->fetch()){
                        
                        if($caseVerdict == NULL){
                            // Insufficient Evidence Button
                            echo <<<ViewAllPost
                                <form class="delete_this_case" method="post" action="ActiveCases.php" onclick="return confirm('Are you sure you want to remove this case for insufficient evidence? This will permanently delete the case.\\nClick OK to continue.')">
                                    <input type="text" name="case_id" value="$caseId" hidden>
                                    <button class="btn btn-danger" value="true" type="submit" name="insufficientEvidence">Insufficient Evidence</button>
                                </form>
ViewAllPost;
                        }
                        else if ($caseVerdict == "guilty"){
                            // Close case Button guilty
                            echo <<<ViewAllPost2
                                <form class="delete_this_case" method="post" action="ActiveCases.php" onclick="return confirm('Are you sure you want to close this case? \\nIf the verdict is guilty the case gets archived in our system, and if the verdict is not guilty the case is permanently deleted. \\nClick OK to continue.')">
                                    <input type="text" name="case_id"   hidden>
                                    <button class="btn btn-danger" value="true" type="submit" name="closeCaseGuilty">Close Case</button>
                                </form>
ViewAllPost2;
                        }
                        else if ($caseVerdict == "not guilty"){
                            // Close case Button not guilty
                            echo <<<ViewAllPost3
                                <form class="delete_this_case" method="post" action="ActiveCases.php" onclick="return confirm('Are you sure you want to close this case? \\nIf the verdict is guilty the case gets archived in our system, and if the verdict is not guilty the case is permanently deleted. \\nClick OK to continue.')">
                                    <input type="text" name="case_id" value="$caseId" hidden>
                                    <button class="btn btn-danger" value="true" type="submit" name="closeCaseNotGuilty">Close Case</button>
                                </form>
ViewAllPost3;
                        }
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
                        echo <<<DisplayFormTabsB
							<li class=""><a data-toggle="tab" href="#formb">Form B</a></li>
DisplayFormTabsB;
					}

                    if($_SESSION['role'] == "admin"){
						echo <<<DisplayFormTabsC
							<li class=""><a data-toggle="tab" href="#formc">Form C: Meeting</a></li>
DisplayFormTabsC;
                    }

					if($_SESSION['role'] != "professor"){
						echo <<<DisplayFormTabsC
							<li class=""><a data-toggle="tab" href="#formd">Form D</a></li>
DisplayFormTabsC;
					}
                ?>
            </ul>

            <div class="tab-content">
                <!-- Not sure why form A is loaded here? Someone who knows should check... - Bjorn -->
                <div id="forma" class="tab-pane fade active in">
					<?php //BUG: Faculty & Class name render 5x if this php tag is absent. ?>
                </div>
                
                <?php
					//Pages are loaded using JS/formLoader.js

                    //if it's a professor visiting, only display from A 
                    if($_SESSION['role'] != "professor"){
                        echo"<div id=\"formb\" class='tab-pane fade'>";
                        echo"</div>";
					}

                    if($_SESSION['role'] == "admin"){
                        echo<<<LoadFormC
							<div id="formc" class='tab-pane fade'>";
							</div>
							<script>loadFormC($caseIdValue, $studentID, $num_students);</script>
LoadFormC;
					}

					if($_SESSION['role'] != "professor"){
                        echo"<div id=\"formd\" class='tab-pane fade'>";
                        echo"</div>";
                    }

                ?>
            </div>
        </div>
    </body>
</html>
