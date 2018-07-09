<?php
require_once '../includes/session.php';

require_once 'secure.php';

//Open the db connection
include_once '../includes/db.php';
//Check if the form variables have been submitted, store them in the session variables
//include '../includes/formProcess.php';
include_once '../includes/page.php';

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
		<a href="ActiveCases.php">Back</a>
		<br>

        <div style="display: inline-block;">
			<!-- TODO: Show student's name -->
            <h2>Schedule Meeting - <p class="studentName"></p></h2>

        </div>
    
		<!-- TODO: If no case_ID is specified in URL, show them an error message and stop loading the page. -->
	
		<?php
			//Gets case id from URL
			if(isset($_GET["case_id"])){
				$caseId = intval($_GET['case_id']);
			} else {
				echo <<<NoIDError
					<p>Error: Case ID not set.</p>
					<a href="ActiveCases.php?" class="btn btn-primary">Active Cases</a>
NoIDError;
				exit();
			}

			//Get Case information; TODO: Test this.
			$statement = $conn->prepare("
									SELECT
										A.case_id,
										A.description,
										A.prof_id,
										A.date_aware,
										A.evidence_fileDir,
										P.fname,
										P.lname,
										P.email,
										P.alt_email
									FROM
										active_cases as A
									INNER JOIN
										professor as P ON A.prof_id = P.professor_id
									WHERE
										case_id = $caseId
									");
			
			if(!$statement->execute()){
                echo "Execute failed: (" . $query->errno . ") " . $query->error;
            }
									
			$statement->bind_result($caseID, $description, $prof_id, $date_aware, $evidence_path, $prof_fname, $prof_lname, $prof_email_1, $prof_email_2);
			
			$statement->fetch();	//Pull just one row.

			if (!$description){
				$description = "N/A";
			}

			//Get Student information
			/*
			$statement = $conn->prepare("
									SELECT
										A.case_id
									FROM
										active_cases as A
									WHERE
										case_id = $caseId
									");

			if(!$statement->execute()){
                echo "Execute failed: (" . $query->errno . ") " . $query->error;
            }

			$statement->bind_result($caseID);

			$statement->fetch();	//TODO: Loop to pull the rows of all relevant students.
			*/
			//Make the table.
			echo <<<ViewCaseInfo

			<div>
            <table class="table table-bordered" style="font-size: 14px;">
                <tbody>
                    <tr>
                        <td>Case description</td>
                        <td>$description</td>
                    </tr>
                    <tr>
                        <td>Student name</td>
                        <td class="studentName">Case ID: $caseID</td>
                    </tr>
                    <tr>
                        <td>Professor</td>
                        <td>$prof_fname $prof_lname</td>
                    </tr>
                        <td>Professor email 1</td>
                        <td>$prof_email_1</td>
                    </tr>
                        <td>Professor email 2</td>
                        <td>$prof_email_2</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td class="date">$date_aware</td>
                    </tr>
                    <tr>
                        <td>Files</td>
						<!-- What will we want to show here? -->
                        <td><a href="$evidence_path">$evidence_path</a></td>
                    </tr>
                </tbody>
            </table>
ViewCaseInfo;

        ?>

		<div class="form-group">
			<label for="date" class="col-sm-3 control-label">Meeting date:</label>
			<div class="col-sm-9">
				<input class="form-control" placeholder="MM/DD/YYYY" name="MeetingDate" id="date">
            </div>
        </div>

    </body>

	<script type="text/javascript">
		//TODO: Test this.
		$(document).ready(function () {
            "use strict";
            var date_input1 = $('input[id="date"]');
            var options = {
                format: 'mm/dd/yyyy',
                todayHighlight: true,
                autoclose: true
            };

            var datepicker = date_input1.datepicker(options)
            datepicker.on('show', function(e) {
                var rect = e.currentTarget.getBoundingClientRect();
                $(this).data('datepicker').picker.css('left', rect.left);
            });
        });
	</script>
</html>
