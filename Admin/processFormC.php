<?php
	require_once '../includes/session.php';

	require_once 'secure.php';
	//Open the db connection
	include_once '../includes/db.php';
	//Check if the form variables have been submitted, store them in the session variables
	include_once '../includes/page.php';

	//TODO: Also include evidence file in the email.
	$data = array("case_id", "student_name", "csid", "student_email_C", "prof_name", "date", "timepickerC", "room_num", "building", "aio_phone", "aio_email", "student_id");

	/**
	 * Checks all the required fields and sanitizes them.
	 *
	 * Sanitization method inspired by:	https://stackoverflow.com/questions/1885979/php-get-variable-array-injection
	*/
	foreach ($data as $field) {
		if (isset($_POST[$field])) {
			$data[$field] = preg_replace('/[^a-zA-Z0-9_\.\-&=]/', '', $_POST[$field]);
			echo ("<p>\n" . $data[$field] . "</p>");
		} else {
			echo ("<p>Error, field " . $field . " was not set.</p>");
			exit();
		}
	}

	//TODO: Modify date to match format DB wants.

	//TODO: Receive copy from the client and make it match this.
	$studentEmailText = ("Dear " . $data['student_name'] .
		",\n\nA meeting has been scheduled to discuss your academic integrity case. Please find the details below." .
		"Should you need to reschedule this meeting, please contact your assigned academic integrity officer at " .
		$data['aio_email']) . " or " . $data['aio_phone'] . "\n\nCase details: COMING SOON";

	//Check that the form submission is valid: Has all the required fields.
    if(isset($_POST['notify_prof']) && ($_POST['notify_prof'] == true)) {
        echo "\nWill send to prof";
    }
	else {
		echo "\nWill not send to prof";
	}

	echo ("<p>" . $studentEmailText . "</p>");

	//Edit database to record meeting
    $conn = OpenCon();

	$setMeeting = $conn->prepare("
		UPDATE student
		SET
			c_meetingDate = ?,
			c_meetingLocation = ?,
			c_meetingTime = ?
		WHERE student_id = ?
	");
    $setMeeting->bind_param("sssd",
		$data['date'],
		($data['building']// . " " . $data['room_num']
		),
		$data['timepickerC'],
		$data['student_id']
	);	//Bind vars to '?' parameters in SQL.
    if (!$setMeeting->execute()) {
      echo "Execute failed: (" . $setMeeting->errno . ") " . $setMeeting->error;
    }

    CloseCon( $conn )
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Portal</title>
        <link rel="stylesheet" href="../CSS/main.css">
        <link rel="stylesheet" href="../CSS/formc.css">
        <script src="../JS/formc.js"></script>
    </head>
    <body style="margin: auto;">
        						
		<button type="submit" class="btn btn-success" name="openCEmail" data-toggle="modal" data-target="#emailFormC" disabled>Forward Case</button>
		<!--button type="submit" class="btn btn-success" name="openCEmail" onclick="formCValid()">Create Email</button-->
		<form class="form-horizontal forward_case" id="schedule_meeting_C" method="post">
			<div class="form-container">
				<div id="emailFormC" class="modal fade" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h2 class="modal-title">Forward Case Email Form</h2>
							</div>
							<div class="modal-body">
								<p>Fill out the form below to forward this case to the senate via email. All of the evidence and case files will be attached and sent in this email. FORMC</p>
								<p>To send email to multiple addresses, enter email addresses in a comma separated list.</p>
								<div class="form-group">
									<label for="email-to" class="col-sm-3 control-label">To:</label>
									<div class="col-sm-9">
										<input type="email" class="form-control" placeholder="Email Address" id="email-to" name="email_to" required>
									</div>
								</div>
								<div class="form-group">
									<label for="email-cc" class="col-sm-3 control-label">Cc:</label>
									<div class="col-sm-9">
										<input type="email" class="form-control" placeholder="Email Address" id="email-cc" name="email_cc">
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
    </body>
</html>