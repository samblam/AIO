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

	//Modify date to match format DB wants.
	$forattedDate = (
		$data['date'][4] . $data['date'][5] . $data['date'][6] . $data['date'][7] .	//Year
		"-" . $data['date'][0] . $data['date'][1] .									//Month
		"-" . $data['date'][2] . $data['date'][3]									//Day
	);

	$room = ($data['building'] . " " . $data['room_num']);

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
		$forattedDate,
		$room,
		$data['timepickerC'],
		$data['student_id']
	);	//Bind vars to '?' parameters in SQL.
    if (!$setMeeting->execute()) {
      echo "Execute failed: (" . $setMeeting->errno . ") " . $setMeeting->error;
    }

    CloseCon( $conn )

	//TODO: Send the email.
?>