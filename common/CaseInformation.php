<?php
require_once '../includes/session.php';

require_once 'secure.php';
//Open the db connection
include_once '../includes/db.php';
//Check if the form variables have been submitted, store them in the session variables
include '../includes/formProcess.php';
include_once '../includes/page.php';

//$case_id = "";
//$aio_id="";
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

        
        <div>
    
        <?php
        if($_SESSION['role']=="professor" && $formSubmissionDate==""){
        echo"<button type=\"submit\" class=\"btn btn-success\" id=\"SubmitFormA\" name=\"SubmitFormA\">Submit</button>";
        } 

        elseif ($_SESSION['role']=="professor" && $formSubmissionDate!="") {
        // add submit button for adding more evidence to a previously submitted case
        echo "<button type=\"submit\" class=\"btn btn-success\" id=\"AddEvidence\" name=\"AddEvidence\" disabled>Upload Selected Evidence</button>";

        if($evidenceFileDir!=""){
        // add a hidden field that passes on the file directory in which to add the files
        echo "<input type=\"hidden\" name=\"EvidenceDirectory\" value=\"$evidenceFileDir\">";
            }
        }
        ?>
        </div>
    

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
                        <!-- this needs evidence files -->
                        <td><a href="#">Link.zip</a></td>
                    </tr>

                    <tr>
                        <td>Case status</td>
                        <!-- needs to come from backend -->
                        <td>Waiting for student to confirm meeting date</td>
                    </tr>

                </tbody>
            </table>
        </div>

        <div class="center-block text-center">
        <?php 
        //setting original AIO id to null for bind parameter
        $aio_id=NULL;
        // check if URL contains the case_id variable
        if(isset($_GET["case_id"])){
        $statement = $conn->prepare("SELECT aio_id FROM active_cases WHERE case_id = ?");
        // get the case_id from the URL

        //binding current cases aio id to variable
        $case_id = (int)$_GET["case_id"];
        $statement->bind_param("i", $case_id);
        if(!$statement->execute()){
        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
        }

        // get the case information from the database
        $statement->bind_result($aio_id);
        $statement->fetch();

        //echoing button actions to Accept Deny php file 
        if($_SESSION['role']=="aio" && $aio_id==NULL){
        echo"<form action= \"../includes/AcceptDeny.php\" method=\"post\">";
        echo"<button type=\"submit\" class=\"btn btn-success\" name=\"AcceptFormA\">Accept</button>";
        echo"<button type=\"submit\" class=\"btn btn-danger\" name=\"DenyFormA\">Deny</button>";
        echo"<input type=\"hidden\" name=\"CurrCaseId\" value=\"$case_id\"></input>";
        echo"</form>";
        }



    }
        CloseCon($conn);
        $conn=OpenCon();
    ?>


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
