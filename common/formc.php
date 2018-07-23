<?php
require_once '../includes/session.php';

require_once 'secure.php';
//Open the db connection
include_once '../includes/db.php';
include_once '../includes/getCaseID.php';
//Check if the form variables have been submitted, store them in the session variables
include '../includes/formProcess.php';
include_once '../includes/page.php';

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
        <div class="form-container">
            <h2 class="form-d-title">Form C</h2>
            <p>AIO Allegation Letter</p>

			<?php
				$caseId = getCaseID();

				//Create the button set
				if(isset($_GET["num_students"]) && is_numeric($_GET["num_students"])){
					$num_students = intval($_GET['num_students']);
				} else {
					$num_students = 1;
				}

				//Get the IDs of students involved in the case and make a button for each one.
				//TODO: Pass in a num_students in the GET so this step can be skipped 90% of the time.
				if (true) {
					$getStudentList = $conn->prepare("
									SELECT
										student_id,
										fname,
										lname
									FROM
										student
									WHERE
										case_id = $caseId
									");

					if(!$getStudentList->execute()){
						echo "Execute failed: (" . $getCaseInfo->errno . ") " . $getCaseInfo->error;
					}

					$getStudentList->bind_result($student_id, $stu_fname, $stu_lname);

					while($getStudentList->fetch()) {
						echo<<<AltStudentButton
							<button class="btn btn-primary" type="button" onclick="loadFormC($caseId, $student_id)">
								$stu_fname $stu_lname
							</button>
AltStudentButton;
					}

					CloseCon($getStudentList);
				}

				if(isset($_GET["student_id"]) && is_numeric($_GET["student_id"])){
					$student_id = intval($_GET['student_id']);
				} else {
					echo <<<NoStuIDError
						<p>Error: Student ID not set.</p>
						<a href="ActiveCases.php?" class="btn btn-primary">Return to Active Cases</a>
NoStuIDError;
					exit();
				}

				//Get info about the student.
				$studentInfo = $conn->prepare("
									SELECT
										S.fname,
										S.lname,
										S.email,
										S.csid
									FROM
										student as S
									WHERE
										student_id = $student_id
									");

				if(!$studentInfo->execute()){
					echo "Execute failed: (" . $studentInfo->errno . ") " . $studentInfo->error;
				}

				$studentInfo->bind_result($stu_fname, $stu_lname, $stu_email, $stu_csid);
				$studentInfo->fetch();	//Pull just one row.
				CloseCon($studentInfo);


				//Get additional information about the case.
				$caseInfo = $conn->prepare("
									SELECT
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

				if(!$caseInfo->execute()){
					echo "Execute failed: (" . $caseInfo->errno . ") " . $caseInfo->error;
				}

				$caseInfo->bind_result($evidence_path, $prof_fname, $prof_lname, $prof_email, $aio_id);
				$caseInfo->fetch();	//Pull just one row.
				CloseCon($caseInfo);

				//Add another DB visit for getting AIO info, if they exist.
				if ($aio_id == "") {
					$aio_id = -1;
					$aio_phone = "N/A";
					$aio_email = "N/A";
				} else {
					$AIOInfo = $conn->prepare("
									SELECT
										phone,
										email
									FROM
										aio
									WHERE
										aio_id = $aio_id
									");

					if(!$AIOInfo->execute()){
						echo "Execute failed: (" . $AIOInfo->errno . ") " . $AIOInfo->error;
					}

					$AIOInfo->bind_result($aio_phone, $aio_email);
					$AIOInfo->fetch();	//Pull just one row.
					CloseCon($AIOInfo);
				}
			?>
        </div>

        <div class="form-container">
            <form class="form-horizontal" action="../includes/processForm.php" method="post">
                <div class="form-group">
                    <label class="control-label col-sm-3">Student Name:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="Student Name"
						id="student_name" name="student_name" required readonly
						value=<?php echo ('"' . $stu_fname . ' ' . $stu_lname . '"'); ?>
						>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Student B00:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="B00 Number"
						id="b00_num" name="b00_num" required readonly
						value=<?php echo ('"' . $stu_csid . '"'); ?>
						>
                    </div>
                </div>
				<div class="form-group">
                    <label class="control-label col-sm-3">Student Email:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="student@dal.ca"
						id="student_email_C" name="student_email_C" required
						value=<?php echo ('"' . $stu_email . '"'); ?>
						>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Professor Name:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="Professor Name"
						id="prof_name" name="prof_name"
						value=<?php echo ('"' . $prof_fname . ' ' . $prof_lname . '"') ?>
						required>
                    </div>
                </div>

                <!-- TODO: Fix date select box -->
                <div class="form-group date">
                    <label class="col-sm-3" >Meeting Date/Time:</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" placeholder="MM/DD/YYY" id="date" name="date" required>
                    </div>
                    <div class="col-sm-6">
                        <input class="timepicker form-control" id="time" name="time" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Meeting Location:</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" placeholder="Room #" id="room_num" name="room_num" required>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="Building" id="building" name="building" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">AIO Phone Number:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="(XXX) XXX-XXXX" id="aio_phone" name="aio_phone" required
						value=<?php echo ('"' . $aio_phone . '"'); ?>
						>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">AIO Email:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="AIO Email" id="aio_email" name="aio_email" required
						value=<?php echo ('"' . $aio_email . '"'); ?>
						>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3"><input type="checkbox"> Notify Professor</label>
                </div>

                <!--save button, submit button-->
                <div class="form-group">
                    <div class="center-block text-center">
						<!--
                        <button type="submit" class="btn btn-primary" name="SubmitFormC">Preview PDF</button>
                        <button type="submit" class="btn btn-primary" name="SaveFormA">Save</button>
						-->
                        <button type="submit" class="btn btn-success" name="SubmitFormA">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
