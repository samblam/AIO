<?php
require_once '../includes/session.php';

require_once 'secure.php';

include '../functions/getCaseID.php';

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
        <script src="../JS/top-header-full.js"></script>

    </head>
    <body style="margin: auto;">
        <!-- Header div + Logout button -->
        <div class="top-header-full"></div>

		<!-- Remove this after we have an actual back button -->
		<a href="ActiveCases.php">Active Cases</a>
		<br>


        <div style="display: inline-block;">
            <h2>Schedule Meeting</h2>
        </div>

		<?php
			$caseId = getCaseID();

			//Will warn user if something is wrong before they send the email.
			$caseErrors = false;

			$getCaseInfo = $conn->prepare("
									SELECT
										A.case_id,
										A.description,
										A.prof_id,
										A.date_aware,
										A.evidence_fileDir,
										P.fname,
										P.lname,
										P.email,
										A.aio_id
									FROM
										active_cases as A
									INNER JOIN
										professor as P ON A.prof_id = P.professor_id
									WHERE
										case_id = $caseId
									");

			if(!$getCaseInfo->execute()){
                echo "Execute failed: (" . $query->errno . ") " . $query->error;
            }

			$getCaseInfo->bind_result($caseID, $description, $prof_id, $date_aware, $evidence_path, $prof_fname, $prof_lname, $prof_email_1, $aio);

			$getCaseInfo->fetch();	//Pull just one row.

			CloseCon($getCaseInfo);

			if (!$description){
				$description = "N/A";
			}

			/**
			* Use the AIO ID to either show error or show name.
			* Not done in above due to an INNER JOIN with the aio table
			* not working when there is no AIO.
			*/
			if (!$aio){
				$aio = "No AIO assigned<br><a href='ChangeAIO.php?case_id=" . $caseId . "'>Assign AIO</a>";
			} else {
				$getAIO = $conn->prepare("
									SELECT
										fname,
										lname
									FROM
										aio
									WHERE
										aio_id = $aio
									");

				if(!$getAIO->execute()){
					echo "Execute failed: (" . $query->errno . ") " . $query->error;
				}

				$getAIO->bind_result($aio_fname, $aio_lname);
				$getAIO->fetch();	//Pull just one row.

				$aio = ($aio_fname . " " . $aio_lname);
				CloseCon($getAIO);
			}

			//Start building the list of people to send emails to
			$sendTo_Emails = array();
			$sendTo_Name = array();

			if (!$prof_email_1){
				$prof_email_1 = "<font color='red'>Error - no email found.</font>";
				$caseErrors = true;
			} else{
				//Adds to the arrays.
				$sendTo_Emails[] = $prof_email_1;
				$sendTo_Name[] = ($prof_fname . " " . $prof_lname);
			}

			//Make the main information table.
			echo <<<ViewCaseInfo
				<table class="table table-bordered" style="font-size: 14px;">
					<caption>Case Information</caption>
					<tbody>
						<tr>
							<td>Case description</td>
							<td>$description</td>
						</tr>
						<tr>
							<td>Professor</td>
							<td>$prof_fname $prof_lname</td>
						<tr>
							<td>Date</td>
							<td class="date">$date_aware</td>
						</tr>
						<tr>
							<td>Files</td>
							<!-- What will we want to show here? -->
							<td><a href="$evidence_path">$evidence_path</a></td>
						</tr>
						<tr>
							<td>AIO</td>
							<td>$aio</td>
						</tr>
					</tbody>
				</table>
ViewCaseInfo;
			?>

			<!--Meeting date. Uses PHP to set the $date_meeting variable. -->
			<div class="form-group">
				<label for="date" class="col-sm-3 control-label">Meeting date:</label>
				<div class="col-sm-9">
					<input class="form-control" placeholder="MM/DD/YYYY" name="DateAlleged" id="date" value="<?php if (isset($date_meeting)) { echo $date_meeting;} ?>" autocomplete="off">
				</div>
			</div>

		    <script type="text/javascript">
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

			<?php
			/**
			* Get information on the 1 or more students involved in the case.
			*/
			$getStudents = $conn->prepare("
									SELECT
										fname,
										lname,
										csid,
										email
									FROM
										student
									WHERE
										case_id = $caseId
									");

			if(!$getStudents->execute()){
                echo "Execute failed: (" . $query->errno . ") " . $query->error;
            }

			$getStudents->bind_result($student_fname, $student_lname, $student_id, $student_email);

			//Built instructor part of the table.
			echo <<<ContactTable1
				<table class="table table-bordered" style="font-size: 14px;">
					<caption>Send to</caption>
					<tr>
						<th>Role</th>
						<th>Name</th>
						<th>Email</th>
					</tr>
					<tr>
						<td>Professor</td>
						<td>$prof_fname $prof_lname</td>
						<td>$prof_email_1</td>
					</tr>
ContactTable1;

			//Add 1 row for every student involved in the case.
			$i = 0;
			while($getStudents->fetch()){
				$i++;
				if (!$student_email){
					$student_email = "<font color='red'>Error - no email found.</font>";
					$caseErrors = true;
				} else{
					$sendTo_Emails[] = $student_email;
					$sendTo_Name[] = ($student_fname . " " . $student_lname);
				}

				echo <<<ContactTable2
					<tr>
						<td>Student #$i</td>
						<td>$student_fname $student_lname</td>
						<td>$student_email</td>
					</tr>
ContactTable2;
			}

			CloseCon($getStudents);

			echo <<<ContactTable3
				</table>
				<br>
				<br>
				<button id="Send">Schedule and Send</button>
ContactTable3;

			//Temp values while I hook up the date and time pickers.
			$meeting_day = "2018-6-24";
			$meeting_location = "AIO";
			$meeting_time = "9:30:00"

		?>

    </body>
</html>