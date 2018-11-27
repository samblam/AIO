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
        
        <div>
            <!-- Displays the current AIO, or that there isn't one. -->
            <?php
                //Gets case id from URL
                $conn = OpenCon();
                $caseId = $_POST['caseId'];
                $statement = getCurrentAIO($caseId,$conn);
                //Get aio_id and aio name.

             //   $statement = $conn->prepare("SELECT active_cases.aio_id, aio.fname, aio.lname FROM active_cases LEFT JOIN aio ON aio.aio_id = active_cases.aio_id WHERE active_cases.case_id = '$caseId' ");
                if(!$statement->execute()){
                    echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
                }
                $statement->bind_result($aioId, $aioFName, $aioLName);
                while($statement->fetch()){
                    if($aioId == NULL){
                        echo <<<NoAIO
                            No AIO is assigned to this case. <br><br>
NoAIO;
                    }
                    else{
                        echo <<<CurrentAIO
                            Current AIO: $aioFName $aioLName <br><br>
CurrentAIO;
                    }
                }
                CloseCon( $conn );

            ?>
            
            <!-- Dropdown for selecting AIO-->    
            <?php
                //Get aio_id and aio name.

                $conn = OpenCon();
                $statement = selectNameAIO($conn);
               // $statement = $conn->prepare("SELECT fname, lname FROM aio");
                if(!$statement->execute()){
                    echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
                }
                // Start of form, and drop down select
                echo <<<SelectNew
                    New AIO: 
                    <form class="submit_new_aio" method="post" action="ActiveCases.php">
                        <select class="border border-dark" name="selectedAIO">
                            <option selected >Select New</option>
SelectNew;
                $statement->bind_result($aioFName, $aioLName);
                while($statement->fetch()){
                    // Drop down options for each AIO in the DB
                    echo <<<OptionAIO
                        <option>$aioFName $aioLName</option>      
OptionAIO;
                }
                //Gets case id from URL
                $caseId = $_POST['caseId'];
                // Submit button, and End of form
                echo <<<Button
                    </select>
                    <br><br>
                        <input type="text" name="case_id" value="$caseId" hidden>
                        <button class="btn btn-success" value="true" type="submit" name="submitChangeAIO">Submit</button>
                    </form>
Button;
                CloseCon( $conn );

            ?>
        </div>
    </body>
</html>
