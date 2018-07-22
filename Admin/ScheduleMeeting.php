<?php
require_once '../includes/session.php';

require_once 'secure.php';

include '../functions/getCaseID.php';
include_once '../includes/page.php';

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

			//Make the main information table.
			echo <<<ViewCaseInfo
				<table class="table table-bordered" style="font-size: 14px;">
					<caption>Case Information</caption>
					<tbody>
						<tr>
							<td>Case description</td>
							<td>$description</td>
						</tr>
							<td>Date</td>
							<td class="date">$date_aware</td>
						</tr>
						<tr>
							<td>Professor</td>
							<td>$prof_fname $prof_lname ($prof_email_1)</td>
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

				/**
				* Get information on the 1 or more students involved in the case.
				*/
				//Start building the list of people to send emails to
				$student_emails = array();
				$studentNames = array();
				$meetingDates = array();
				$meetingTimes = array();

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
										email,
										c_meetingDate,
										c_meetingTime
									FROM
										student
									WHERE
										case_id = $caseId
									");

			if(!$getStudents->execute()){
                echo "Execute failed: (" . $query->errno . ") " . $query->error;
            }

			$getStudents->bind_result($student_fname, $student_lname, $student_id, $student_email, $meetingDate, $meetingTime);

			//Built instructor part of the table.
			echo <<<ContactTable1
				<table class="table table-bordered" style="font-size: 14px;">
					<caption>Student(s)</caption>
					<tr>
						<th>Role</th>
						<th>Name</th>
						<th>Email</th>
						<th>Meeting date</th>
						<th>Meeting time</th>
						<th></th>
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

					//TEMP
					//$meetingDate = "2018-02-03";
					//Adds to the arrays.
					$studentEmails[] = $student_email;
					$studentNames[] = ($student_fname . " " . $student_lname);
					$meetingDates[] = $meetingDate;
					$meetingTimes[] = $meetingTime;
				}
				/**
				echo <<<ContactTable2
					<tr>
						<td>Student #$i</td>
						<td>$student_fname $student_lname</td>
						<td>$student_email</td>
						<td>
							<div class="col-sm-9">
								<input class="form-control" placeholder="MM/DD/YYYY" name="DateAlleged" id="date$i" value="$meetingDate" autocomplete="off">
							</div>
						</td>
						<td>**INSET TIME PICKER HERE**</td>
						<td>
							<button id="Send$i">Schedule and Send</button>
						</td>
					</tr>
					
					<script type="text/javascript">
						console.log("Yup, stuff is happening $i");
						$(document).ready(function () {
							"use strict";
							var date_input = $('input[id="date$i"]');
							//var date_input = document.getElementById("date$i");
							console.log(date_input);
							var options = {
								format: 'mm/dd/yyyy',
								todayHighlight: true,
								autoclose: true
							};

							var datepicker = date_input.datepicker(options)
							datepicker.on('show', function(e) {
								var rect = e.currentTarget.getBoundingClientRect();
								$(this).data('datepicker').picker.css('left', rect.left);
							});
						});
					</script>
					
					
ContactTable2;
			*/

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

		?>

		<!--Meeting date. Uses PHP to set the $date_meeting variable. --
		<div class="form-group">
			<label for="date" class="col-sm-3 control-label">Meeting date:</label>
			<div class="col-sm-9">
				<input class="form-control" placeholder="MM/DD/YYYY" name="DateAlleged" id="date" autocomplete="off">
			</div>
		</div>
		
		                <!--date of allegation -->
		<div class="form-group">
			<label for="date" class="col-sm-3 control-label">Date of Alleged Offense:</label>
			<div class="col-sm-9">
				<input class="form-control" placeholder="MM/DD/YYYY" name="DateAlleged" id="date" value="" autocomplete="off">
			</div>
		</div>

		<script type="text/javascript">
			$(document).ready(function () {
				"use strict";
				var date_input1 = $('input[id="date"]');
				console.log(date_input1);
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
    </body>
</html>