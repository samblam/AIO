<?php
	require_once '../includes/session.php';

	require_once 'secure.php';
	//Open the db connection
	include_once '../includes/db.php';
	//include_once '../includes/getCaseID.php';
	//Check if the form variables have been submitted, store them in the session variables
	include_once '../includes/page.php';




    // Deletes all students and active cases with the given case id from Admin/ACtiveCases.php
    if(isset($_POST['aio_email']) && isset($_POST['case_id']) && $_SESSION['role'] == "admin") {
        echo "Test1";
    }
	else {
		echo "Test fail";
	}




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
