<?php
    require_once '../includes/session.php';
    require_once 'secure.php';
    //Open the db connection
    include_once '../includes/db.php';
    //Check if the form variables have been submitted, store them in the session variables
    include '../includes/formProcess.php';
    include_once '../includes/page.php';

    $conn = OpenCon();

    $role = $_SESSION["role"];
    $userId = (int) $_SESSION["csid"];

    // get information related to evidence files that have been submitted for this case
    $path_to_evidence_dir = "";
    $aio_id = "";
    $prof_id = "";
    $caseId = "";
    if(isset($_POST['caseId'])){
        //Gets case id from URL
        $caseId = intval($_POST['caseId']);
       $statement = getCaseInfo($caseId,$conn);

      // $statement = $conn->prepare("SELECT evidence_fileDir, aio_id, prof_id FROM active_cases WHERE case_id ='$caseId' ");
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
    </head>
    
    <body style="margin: auto;">
        <?php include_once '../includes/navbar.php'; ?>

        <div style="display: inline-block;">
            <h2>Case Information</h2>
        </div>
        
        <!-- Table div -->
        <!-- TODO: Table will need to populate based on the entries in the DB(server side) -->
        <!-- TODO: I think to properly link the buttons, each row might have to be an input form(haven't looked it up) -->
        <div>
            <?php
                $conn = OpenCon();
                //This fixes an issues with going back, or reloading the page as the caseId is lost
                if(!isset($_POST['caseId'])){
                    if(!isset($_SESSION['lastCaseId'])){
                        header('ActiveCases.php');
                    }
                    else{
                        $caseIdValue = $_SESSION['lastCaseId'];
                    }
                }
                else{
                    $caseIdValue = $_POST['caseId'];
                    $_SESSION['lastCaseId'] = $_POST['caseId'];
                }

                //Get all relevant fields and bind them to php variables
            $statement = getMoreCaseInfo($caseIdValue,$conn);
/*                $statement = $conn->prepare("
                    SELECT
                        active_cases.form_a_submit_date,
                        active_cases.stu_csid_list,
                        professor.fname, 
                        professor.lname, 
                        student.fname, 
                        student.lname, 
                        student.csid,
                        student.student_id
                    FROM 
                        professor 
                        LEFT JOIN active_cases ON professor.professor_id = active_cases.prof_id 
                        LEFT JOIN student ON student.case_id = '$caseIdValue'
                    WHERE 
                        active_cases.case_id = '$caseIdValue'
                        ");
*/
                if(!$statement->execute()){
                    echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
                }
                $statement->bind_result($submissionDate, $studentList, $pfname, $plname, $sfname, $slname, $scsid, $studentID);
                $statement->fetch();
            ?>

            <table class="table table-bordered" style="font-size: 14px;">
                <tbody>
                    <tr>
                        <td>Banner number</td>
                        <td><?php echo $scsid ?></td>
                    </tr>
                    <tr>
                        <td>Student name</td>
                        <td class="studentName"><?php echo $sfname; ?></td>
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
                            echo "<td>";
                            if ($path_to_evidence_dir != "" && file_exists("../evidence/" . $path_to_evidence_dir . "/evidence.zip")) {
                                // user should be shown the link to the evidence file
                                $path_to_zip_file = "../evidence/" . $path_to_evidence_dir . "/evidence.zip";
                                echo "<form action=\"../downloadRequest.php\" method=\"post\">
                                            <input hidden name=\"caseId\" id=\"caseId\" value=\"$caseId\"/>
                                            <input type=\"submit\" class=\"submitLink\" name=\"evidenceLink\" value=\"evidence.zip\"/>
                                        </form><br />";
                            }
                            
                            else {
                                // no evidence has been submitted
                                echo "No evidence submitted<br />";
                            }
                            $path_to_PDF_dir = $caseId;
                            if ($path_to_PDF_dir != "" && file_exists("../evidence/" . $caseId . "/FormA.pdf")){
                                // user should be shown the link to the pdf
                                echo "<form action=\"../downloadRequest.php\" method=\"post\">
                                                <input hidden name=\"caseId\" id=\"caseId\" value=\"$caseId\">
                                                <input type='submit' class='submitLink' name='PDFLink' value='FormA.pdf'/>
                                           </form>";
                            }
    

                            else{
                                //no PDF generated
                                echo "No PDF submitted";
                            }
                            echo "</td>";
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
        
        <!-- Accept/deny buttons div -->
        <div class="center-block text-center">
            <?php 
                //setting original AIO id to null for bind parameter
                $aio_id=NULL;
                                // check if URL contains the case_id variable
                if(isset($_SESSION["lastCaseId"])){
                    $case_id = (int)$_SESSION["lastCaseId"];
                    $conn = OpenCon();
                   $statement = selectCaseID($case_id,$conn);
            //  $statement = $conn->prepare("SELECT aio_id FROM active_cases WHERE case_id = '$caseId'");
                    //binding current cases aio id to variable

                //    $statement->bind_param("i", $case_id);
                    if(!$statement->execute()){
                        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
                    }
                    // get the case information from the database
                    $statement->bind_result($aio_id);
                    $statement->fetch();
                    //echoing button actions to Accept Deny php file 
                    if($_SESSION['role']=="aio" && $aio_id==NULL){
                        echo <<<AcceptButtons
                            <form action="../includes/AcceptDeny.php" method="post">
                                <button type="submit" class="btn btn-success" name="AcceptFormA">Accept Case</button>
                                <input type="hidden" name="CurrCaseId" value="$case_id"></input>
                            </form>
AcceptButtons;
                    }
                    //echoing button actions to Accept Deny php file 
                    if($_SESSION['role']=="aio" && $aio_id!=NULL){
                        echo <<<DenyButtons
                            <form action="../includes/AcceptDeny.php" method="post">
                                <button type="submit" class="btn btn-danger" name="DenyFormA">Decline Case</button>
                                <input type="hidden" name="CurrCaseId" value="$case_id"></input>
                            </form>
DenyButtons;
                    }
                }
                CloseCon($conn);

            ?>
        </div>

        <!-- Close case and insufficient evidence buttons -->
        <div class="center-block text-center">
            <?php
            $conn = OpenCon();
                if(!isset($_POST['caseId'])){
                    if(!isset($_SESSION['lastCaseId'])){
                        header('ActiveCases.php');
                    }
                    else{
                        $caseIdValue = $_SESSION['lastCaseId'];
                    }
                }
                else{
                    $caseIdValue = $_POST['caseId'];
                    $_SESSION['lastCaseId'] = $_POST['caseId'];
                }

                //Get case verdict from db
                    $id = $_SESSION['csid'];
            $statement = getCaseVerdict($caseIdValue,$id,$conn);
                // $statement = $conn->prepare("SELECT case_verdict FROM active_cases WHERE case_id = '$caseIdValue' AND aio_id = ?");

          //      $statement->bind_param("d", $id); //bind the csid to the prepared statements

               // $res = $conn->query( "SELECT aio_id FROM `aio` WHERE csid='$id'" );
            $res = getAIOId($id,$conn);
                $id = $res->fetch_array()[0];
                if(!$statement->execute()){
                    echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
                }
                $statement->bind_result($caseVerdict);
                while($statement->fetch()){
                    if($caseVerdict == NULL){
                        // Insufficient Evidence Button
                        echo <<<InsufficientEvidence

                            <form class="delete_this_case" method="post" onclick="return confirm('Are you sure you want to remove this case for insufficient evidence? This will permanently delete the case.\\nClick OK to continue.')">
                                <input type="text" name="case_id" value="$caseIdValue" hidden>
                                <button class="btn btn-danger" value="true" type="submit" name="insufficientEvidence">Insufficient Evidence</button>
                            </form>
InsufficientEvidence;
                    }
                    else if ($caseVerdict == "guilty" && $_SESSION['role'] != "professor"){
                        // Close case Button guilty
                        echo <<<GuiltyClose
                            <form class="delete_this_case" method="post" action="ActiveCases.php" onclick="return confirm('Are you sure you want to close this case? \\nIf the verdict is guilty the case gets archived in our system, and if the verdict is not guilty the case is permanently deleted. \\nClick OK to continue.')">
                                <input type="text" name="case_id" value="$caseIdValue" hidden>
                                <button class="btn btn-danger" value="true" type="submit" name="closeCaseGuilty">Close Case</button>
                            </form>
GuiltyClose;
                    }
                    else if ($caseVerdict == "not guilty" && $_SESSION['role'] != "professor"){
                        // Close case Button not guilty
                        echo <<<NotGuiltyClose
                            <form class="delete_this_case" method="post" action="ActiveCases.php" onclick="return confirm('Are you sure you want to close this case? \\nIf the verdict is guilty the case gets archived in our system, and if the verdict is not guilty the case is permanently deleted. \\nClick OK to continue.')">
                                <input type="text" name="case_id" value="$caseIdValue" hidden>
                                <button class="btn btn-danger" value="true" type="submit" name="closeCaseNotGuilty">Close Case</button>
                            </form>
NotGuiltyClose;
                    }
                    // TODO: Add functionality so a zip folder with all of the case files is sent with the email
                    // Forward case button
                    echo <<<ForwardCase
                        <button class="btn btn-success" name="forwardCaseButton" data-toggle="modal" data-target="#emailForm">Forward Case</button>
                        <form class="form-horizontal forward_case" id="forward_case" method="post">
                            <div class="form-container">
                                <div id="emailForm" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h2 class="modal-title">Forward Case Email Form</h2>
                                            </div>
                                            <div class="modal-body">
                                                <p>Fill out the form below to forward this case to the senate via email. All of the evidence and case files will be attached and sent in this email.</p>
                                                <p>To send email to multiple addresses, enter email addresses in a comma seperated list.</p>
                                                <div class="form-group">
                                                    <label for="email-to" class="col-sm-3 control-label">To:</label>
                                                    <div class="col-sm-9">
                                                        <input type="email" class="form-control" placeholder="Email Address" id="email-to" name="email_to" required multiple>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email-cc" class="col-sm-3 control-label">Cc:</label>
                                                    <div class="col-sm-9">
                                                        <input type="email" class="form-control" placeholder="Email Address" id="email-cc" name="email_cc" multiple>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="subject" class="col-sm-3 control-label">Subject:</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" placeholder="Subject" id="email-subject" name="email_subject" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email-message" class="col-sm-3 control-label">Message:</label>
                                                    <div class="col-sm-9">
                                                        <textarea type="text" class="form-control" rows="5" placeholder="Message" name="email_message"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="text" name="case_id" value="$caseIdValue" hidden>
                                                <input type="text" name="caseId" value="$caseIdValue" hidden>
                                                <button class="btn btn-success pull-left" name="forwardCase" value="true" type="submit">Send Email</button>
                                                <button class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>    
ForwardCase;
                }
                CloseCon( $conn );
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
							<script>
								$(document).ready( function() {
									$("#formc").load(
										("formc.php?case_id=" + $caseIdValue + "&student_id=" + $studentID + "&num_students=" + $num_students),
										{"internal": "true"}
									);
								});
							</script>
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
    
    <!-- script for loading forms -->
    <script type="text/javascript">
        $(document).ready( function() {
            // pass the case id on to form A via POST
             $("#forma").load("forma.php", {"caseId": <?php echo $caseIdValue; ?>, "internal": "true" });
         });

         $(document).ready( function() {
             $("#formb").load("formb.php", {"internal": "true"});
         });

         $(document).ready( function() {
             $("#formd").load("formd.php", {"internal": "true"});
         });
    </script>
</html>
