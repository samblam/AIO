<?php
	require_once '../includes/session.php';

	require_once 'secure.php';
	//Open the db connection
	include_once '../includes/db.php';

	//TODO: Also include evidence file in the email.
	$data = array("case_id", "student_name", "csid", "student_email_C", "prof_name", "date", "timepickerC", "room_num", "building", "aio_phone", "aio_email", "student_id", "prof_email_C");

	/**
	 * Checks all the required fields and sanitizes them.
	 *
	 * Sanitization method inspired by:	https://stackoverflow.com/questions/1885979/php-get-variable-array-injection
	 **/
	foreach ($data as $field) {
		if (isset($_POST[$field])) {
			$data[$field] = preg_replace('/[^a-zA-Z0-9_\.\-&=]/', '', $_POST[$field]);
		} else {
			include_once '../includes/page.php';	//Styling for button
			echo <<<MissingDataError
				<link rel="stylesheet" href="../CSS/main.css">	<!-- Styling for error -->
				<p>Error: $field not set.</p>
				<a href="ActiveCases.php?" class="btn btn-primary">Return to Active Cases</a>
MissingDataError;
			exit();
		}
	}

	//Modify date to match format DB wants: YYYY-MM-DD
	$forattedDate = (
		$data['date'][4] . $data['date'][5] . $data['date'][6] . $data['date'][7] .
		"-" . $data['date'][0] . $data['date'][1] .
		"-" . $data['date'][2] . $data['date'][3]
	);

	$room = ($data['building'] . " " . $data['room_num']);

	//Edit database to record meeting
    $conn = OpenCon();
    $setMeeting="";
    $setMeeting = setMeeting($conn);
/* $setMeeting = $conn->prepare("
		UPDATE student
		SET
			c_meetingDate = ?,
			c_meetingLocation = ?,
			c_meetingTime = ?
		WHERE student_id = ?
	");
*/
	//Bind vars to '?' parameters in SQL.
    $setMeeting->bind_param("sssd", $forattedDate, $room, $data['timepickerC'], $data['student_id']);
    if (!$setMeeting->execute()) {
      echo "Execute failed: (" . $setMeeting->errno . ") " . $setMeeting->error;
    }

    CloseCon( $conn );

	//TODO: Receive copy from the client and make it match this.
	$studentEmailText = ("Dear " . $data['student_name'] .
		",\n\nA meeting has been scheduled to discuss your academic integrity case. Please find the details below." .
		"Should you need to reschedule this meeting, please contact your assigned academic integrity officer at " .
		$data['aio_email'] . " or " . $data['aio_phone'] . "\n\nCase details: COMING SOON");

	$EmailSubject = "Meeting to discuss academic integrity";

	$errorSending = false;

	//Try to send email to student; '@' suppresses warnings.
	if (!@mail($data['student_email_C'], $EmailSubject, $studentEmailText)) {
		echo ('<p>Error: Could not send email to student.</p>');
		echo ('<a href="mailto:' . $data['student_email_C'] . '?Subject=' . $EmailSubject .
			'&body=' . $studentEmailText . '">Send Mail Manually</a><br><br>');

		$errorSending = true;
	}

	//If user has checked "Notify Professor", send email to the professor.
    if(isset($_POST['notify_prof']) && ($_POST['notify_prof'] == true)) {
        $profEmailText = ("Dear " . $data['prof_name'] .
		",\n\nA meeting has been scheduled to discuss an academic integrity case you filed, relating to " .
		$data['student_name'] . " Please find the details below." .
		"\n\nCase details: COMING SOON");

		if (!@mail($data['prof_email_C'], $EmailSubject, $profEmailText)) {
			echo ('<p>Error: Could not send email to professor.</p>');
			echo ('<a href="mailto:' . $data['prof_email_C'] . '?Subject=' . $EmailSubject .
			'&body=' . $profEmailText . '">Send Mail Manually</a><br><br>');

			$errorSending = true;
		}
    }

	if ($errorSending) {
		include_once '../includes/page.php';	//Styling for button
		echo ('<link rel="stylesheet" href="../CSS/main.css">');	//Styling for error
		echo ('<a href="ActiveCases.php?" class="btn btn-primary">Return to Active Cases</a>');
	} else {
		header("Location: ../Admin/ActiveCases.php");
	}
?>