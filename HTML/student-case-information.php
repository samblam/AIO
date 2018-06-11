<?php

//Open the db connection
include_once '../includes/db.php';
//Check if the form variables have been submitted, store them in the session variables
include '../includes/formProcess.php';
include_once 'page.php';

?>

<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Student Case</title>
        <link rel="stylesheet" href="../CSS/main.css">
        <script src="../JS/top-header-full.js"></script>
        <script src="../JS/aio-case-info.js"></script>
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
        <!-- TODO: Add verdict column to active cases table and pull the verdict for the case. If the verdict is null only show Insufficient evidence button, if the verdict is not null only show close case button and either delete or archive the case based on the verdict. -->
        
        <!-- CLose case and insufficient evidence buttons -->
        <div class="center-block text-center">
            <?php
                //Gets case id from URL
                $caseId = intval($_GET['case_id']);
                
                //Insufficient Evidence Button
                echo <<<ViewAllPost
                    <form class="delete_this_case" method="post" action="AioActiveCases.php" onclick="return confirm('Are you sure you want to remove this case for insufficient evidence? \\nClick OK to continue.')">
                        <input type="text" name="case_id" value="$caseId" hidden>
                        <a href="AioActiveCases.php"><button class="btn btn-danger" value="true" type="submit" name="insufficientEvidence">Insufficient Evidence</button></a>
                    </form>
                    
                    <form class="delete_this_case" method="post" action="AioActiveCases.php" onclick="return confirm('Are you sure you want to close this case? \\nClick OK to continue.')">
                        <input type="text" name="case_id" value="$caseId" hidden>
                        <a href="AioActiveCases.php"><button class="btn btn-danger" value="true" type="submit" name="closeCase">Close Case</button></a>
                    </form>
ViewAllPost;
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
                <div id="forma" class="tab-pane fade active in">
                    <?php include 'forma.php' ?>
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
