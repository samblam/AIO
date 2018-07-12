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

    </body>
</html>