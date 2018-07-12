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
                        <td>Banner number</td>
                        <td>B00000000</td>
                    </tr>
                    <tr>
                        <td>Student(s) name</td>
                        <td>Mark Auto</td>
                    </tr>
                    <tr>
                        <td>Professor</td>
                        <td>Fred</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>Jan 31st 2017</td>
                    </tr>
                    <tr>
                        <td>Files</td>
                        <td><a href="#">Link.zip</a></td>
                    </tr>            
                    
                    <tr>
                        <td>Case status</td>
                        <td>Waiting for student to confirm meeting date</td>
                    </tr>
                    
                </tbody>
            </table>
        </div>

        <div>
            <!-- Displays the current AIO, or that there isn't one. -->

        </div>
    </body>
</html>
