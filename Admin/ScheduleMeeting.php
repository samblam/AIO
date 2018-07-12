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
										P.alt_email,
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

			$getCaseInfo->bind_result($caseID, $description, $prof_id, $date_aware, $evidence_path, $prof_fname, $prof_lname, $prof_email_1, $prof_email_2, $aio);

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

				CloseCon($getAIO);

				$aio = ($aio_fname . " " . $aio_lname);
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

			//TODO: Figure out why <th>s are not in the correct places.
			echo <<<ContactTable1
				<table class="table table-bordered" style="font-size: 14px;">
					<caption>Send to</caption>
					<tbody>
						<tr>
							<th>Role<th>
							<th>Name<th>
							<th>Email<th>
						</tr>
						<tr>
							<td>Professor</td>
							<td>$prof_fname $prof_lname</td>
							<td>$prof_email_1</td>		<!--  What about their alt emails? -->
						</tr>
ContactTable1;

			$i = 0;
			while($getStudents->fetch()){
				$i++;
				echo <<<ContactTable2
					<tr>
						<td>Student #$i</td>
						<td>$student_fname $student_lname</td>
						<td>$student_email</td>
					</tr>
ContactTable2;
			}

			echo <<<ContactTable3
					</tbody>
				</table>
ContactTable3
		?>

    </body>
</html>