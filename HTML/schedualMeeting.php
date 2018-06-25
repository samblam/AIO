<?php
require_once '../includes/session.php';
//Open the db connection
include_once '../includes/db.php';
//Check if the form variables have been submitted, store them in the session variables
//include '../includes/formProcess.php';
include_once 'page.php';

?>

<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Schedule Meeting</title>
        <link rel="stylesheet" href="../CSS/main.css">
        <script src="../JS/top-header-full.js"></script>
    </head>
    <body style="margin: auto;">

        <div class="top-header-full"></div>

		<!-- Remove this after we have an actual back button -->
		<a href="AioActiveCases.php">Back</a>
		<br>

        <div style="display: inline-block;">
			<!-- TODO: Show student's name -->
            <h2>Schedule Meeting - <p class="studentName"></p></h2>

        </div>
    
		<!-- TODO: If no case_ID is specified in URL, show them an error message and stop loading the page. -->
	
		<?php

			$statement = $conn->prepare("
									SELECT 
										A.case_id,
										A.description,
										A.prof_id,
										A.date_aware,
										A.evidence_fileDir
									FROM 
										active_cases as A
									WHERE 
										case_id = 2
									");
			
			if(!$statement->execute()){
                echo "Execute failed: (" . $query->errno . ") " . $query->error;
            }
									
			$statement->bind_result($caseID, $description, $prof_id, $date_aware, $evidence_path);
			
			$statement->fetch();	//Pull just one row.

			print("\nHello World" . $caseID . "\n" . $description . "\n" . $prof_id . "\n" . $date_aware . "\n" . $evidence_path);
			
        ?>
	
        <div>

            <table class="table table-bordered" style="font-size: 14px;">
                <tbody>
                    <tr>
                        <!-- needs to grab from backend -->
                        <td>Banner number</td>
                        <td>B00000000</td>
                    </tr>
                    <tr>
                        <td>Student name</td>
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

    </body>
</html>
