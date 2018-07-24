<?php	
	require_once "formProcess.php";
	include_once 'fileFunctions.php';
	require_once 'session.php';
	include_once 'db.php';

	$baseEvidenceDir = "../evidence/";
	$processSuccessful = true;

	if(empty($_POST) && empty($_FILES)){
		// upload has failed (likely because the files were too big)
		echo "Uploading evidence files failed. Please reduce the size of the upload and try again.";
	}

	//Form A processing
	if(isset($_POST['SaveFormA']) || isset($_POST['SubmitFormA'])){

		$userId = $_SESSION['userId'];//first column of the current user's role table in the database

		//Grabs all form data and sanatize it
		$prof = htmlspecialchars(trim(stripslashes($_POST['ProfessorName'])));
		$email = htmlspecialchars(trim(stripslashes($_POST['email'])));
		$phone = htmlspecialchars(trim(stripslashes($_POST['phoneNum'])));
		$faculty = htmlspecialchars(trim(stripslashes($_POST['faculty'])));
		$cname = htmlspecialchars(trim(stripslashes($_POST['class-name'])));
		$students = $_POST['Name']; //array of student names
		$boos = $_POST['B00']; //array of B00 numbers
		$stringDate = htmlspecialchars(trim(stripslashes($_POST['DateAlleged'])));
		$formatDate = strtotime($stringDate);
		$date = date('Y-m-d',$formatDate); // allegation date formatted for mysql database
		$comments = htmlspecialchars(trim(stripslashes($_POST['additionalComments'])));
		//Form A is submitted
		if(isset($_POST['SubmitFormA'])){
			$submitDate = date('Y-m-d', time());
			$caseId;

			// If you are submitting a form A, it cant have multiple students with the same csid. So, return to the form.
			// The "multiIds" query string might be useful for displaying an error message once you return to the form page.
			if(count($boos) != count(array_unique($boos)) && isset($_POST['case_id'])){
				header("locaton: ../common/forma.php?multiIds=true&case_id={$_POST['case_id']}");
			} elseif(count($boos) != count(array_unique($boos)) && !isset($_POST['case_id'])){
				header("locaton: ../common/forma.php?multiIds=true");
			}

			if(isset($_POST['case_id'])){
				// form was previously saved and is now being submitted
				$caseId = (int)$_POST['case_id'];
				$statement = $conn->prepare("UPDATE active_cases SET prof_id = ?, class_name_code = ?, date_aware = ?, description = ?, form_a_submit_date = ? WHERE case_id = ?");
				$statement->bind_param("issssd",$userId, $cname, $date, $comments, $submitDate, $caseId); //bind the values to be inserted to the query

				if(!$statement->execute()) {
					//might want to replace this with header("location: ../forma.php"); so that you aren't executing the script further if there is an error
					echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
					$processSuccessful = false;
				}
			} 

			else {
				//Create new case entry
				$statement = $conn->prepare("INSERT INTO active_cases (prof_id, class_name_code, date_aware, description, form_a_submit_date) VALUES (?, ?, ?, ?, ?)");
				$statement->bind_param("issss",$userId, $cname, $date, $comments, $submitDate); //bind the values to be inserted to the query

				if(!$statement->execute()) {
					//might want to replace this with header("location: ../forma.php"); so that you aren't executing the script further if there is an error
					echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
					$processSuccessful = false;
				}

				// Grabs case_id of the just inserted case and uses it to create the evidence directory name for this case in the database
				// This step might be unnecessary if the value is just the same as the case_id. If its a combo of values then it might be necessary.
				$caseId = $conn->insert_id;
				$updateEvidence = $conn->prepare("UPDATE active_cases SET evidence_fileDir = ? WHERE case_id = ".$caseId);
				$updateEvidence->bind_param("s", $caseId); //bind evidence folder name to the prepared statements
				
				if(!$updateEvidence->execute()) {
					echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
					$processSuccessful = false;
				}

			}
			//Insert students into student table
			/**
			 * For each set of students and b00s, check if both entries are not null.
			 * If not prepare the insert statement, sanatize the current name and B00,
			 * bind the values to the insert statement and execute;\.
			 */
			for($i = 0; $i < sizeof($students); $i++) {
				if($students[$i] != NULL && $boos[$i] != NULL){
					$statement = $conn->prepare("INSERT INTO student (csid, case_id, fname) VALUES (?, ?, ?)");
					$currB00 = htmlspecialchars(trim(stripslashes($boos[$i])));
					$currStudent = htmlspecialchars(trim(stripslashes($students[$i])));
					$statement->bind_param("sis", $currB00, $caseId, $currStudent); //bind initial values to the prepared statements

					if (!$statement->execute()) {
						echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
						$processSuccessful = false;
					}
				}
			}

			// validate uploaded files
			$allFilesAreValid = validateUploadedFiles();

			// Creates the case directory for uploading evidence
			if(!is_dir($baseEvidenceDir . $caseId)){
			    mkdir($baseEvidenceDir . $caseId);
			} 

			$zipFileLocation = $baseEvidenceDir . $caseId; 

			$uploadSuccessful = moveUploadedFilesToZip($allFilesAreValid, $zipFileLocation);

			if(!$uploadSuccessful){
				echo "Failed to upload the given files";
				$processSuccessful = false;
			}

			if($processSuccessful){
				sendUserHome();
			}
		}

		//The case where this Form A has already been saved before and needs to be just saved again
		if(isset($_POST['SaveFormA']) && !isset($_POST['case_id'])){
			//Create new case entry
			$statement = $conn->prepare("INSERT INTO active_cases (prof_id, class_name_code, date_aware, description) VALUES (?, ?, ?, ?)");
			$statement->bind_param("ssss",$userId, $cname, $date, $comments); //bind initial values to the prepared statements

			if (!$statement->execute()) {
				echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
			}

			// Grabs case_id of the just inserted case and uses it to create the evidence directory name for this case in the database
			// This step might be unnecessary if the value is just the same as the case_id. If its a combo of values then it might be necessary.
			$caseId = $conn->insert_id;
			$updateEvidence = $conn->prepare("UPDATE active_cases SET evidence_fileDir = ? WHERE case_id = ".$caseId);
			$updateEvidence->bind_param("s", $caseId); //bind evidence folder name to the prepared statements

			if (!$updateEvidence->execute()) {
				echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
			}

			//Insert students into student table
			/**
			 * For each set of students and b00s, check if both entries are not null.
			 * If not prepare the insert statement, sanatize the current name and B00,
			 * bind the values to the insert statement and execute;\.
			 */
			for($i = 0; $i < sizeof($students); $i++) {
				if($students[$i] != NULL && $boos[$i] != NULL){
					$statement = $conn->prepare("INSERT INTO student (csid, case_id, fname) VALUES (?, ?, ?)");
					$currB00 = htmlspecialchars(trim(stripslashes($boos[$i])));
					$currStudent = htmlspecialchars(trim(stripslashes($students[$i])));
					$statement->bind_param("sis", $currB00, $caseId, $currStudent); //bind initial values to the prepared statements
					
					if (!$statement->execute()) {
						echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
					}
				}
			}

			$statement = $conn->prepare("INSERT INTO saved_info (professor, email, course, faculty, student_name, student_bannerid, date, comments, case_id, phone) VALUES ('{$prof}', '{$email}', '{$cname}', '{$faculty}', '{$students[0]}', '{$boos[0]}', '{$date}', '{$comments}', '{$caseId}', '{$phone}')");

    		if (!$statement->execute()) {
       			echo "Execute failed";
    		}

			sendUserHome();
		}
		//The case where a new Form A is created but saved instead of submitted
		if(isset($_POST['SaveFormA']) && isset($_POST['case_id'])){
			//Create new case entry
			$statement = $conn->prepare("UPDATE active_cases SET prof_id = ?, class_name_code = ?, date_aware = ?, description = ? WHERE case_id = " . (int)$_POST['case_id']);
			$statement->bind_param("isss",$userId, $cname, $date, $comments); //bind initial values to the prepared statements
			
			if (!$statement->execute()) {
			   echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
			}

			//Select students from this case
			$statement = $conn->prepare("SELECT fname, csid FROM student WHERE case_id = " . (int)$_POST['case_id']);
			
			if(!$statement->execute()){
				echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
			}

			$statement->bind_result($fname, $csid);

			$statement = $conn->prepare("INSERT INTO saved_info (professor, email, course, faculty, student_name, student_bannerid, date, comments, case_id, phone) VALUES ('{$prof}', '{$email}', '{$cname}', '{$faculty}', '{$students[0]}', '{$boos[0]}', '{$date}', '{$comments}', '{$caseId}', '{$phone}')");

    		if (!$statement->execute()) {
       			echo "Execute failed";
    		}
    		else{
      			echo "Execute successful";
   			}

			//creates an associative array of csids and names of all students in the cases
			//to easily lookup all students from this case to see if a student needs to be added,
			//removed, or edited.
			$currStudents = array();
			while($statement->fetch()){
				$currStudents[$csid] = $fname;
			}

			for($i = 0; $i < sizeof($students); $i++) {
				if($students[$i] != NULL && $boos[$i] != NULL){
					//Update a student if the csid exists already
					if(array_key_exists("{$boos[$i]}", $currStudents) && $currStudents[$csid] != $students[$i]){
						$statement = $conn->prepare("UPDATE student SET fname = ?, WHERE case_id = {$_POST['case_id']} AND csid = {$boos[$i]}");
						$statement->bind_param("s", $student[$i]);
					}

					elseif(!array_key_exists("{$boos[$i]}", $currStudents)) {
						$statement = $conn->prepare("INSERT INTO student (csid, case_id, fname) VALUES (?, ?, ?)");
						$statement->bind_param("sss", $boos[$i], $caseId, $students[$i]); //bind initial values to the prepared statements
					}

					elseif(!in_array($currStudents[$boos[$i]], $students)){
						$statement = $conn->prepare("DELETE FROM student WHERE case_id = {$_POST['case_id']} AND csid = {$boos[$i]}");
					}

					if (!$statement->execute()) {
						echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
					}
				}
			}

			sendUserHome();
		}
	}

	// add evidence to previously submitted case
	if(isset($_POST['AddEvidence'])){

		if(!isset($_POST['EvidenceDirectory'])){
			echo "Error - there was no evidence directory in which to add the uploaded files.";
		} 

		else {
			$evidenceDir = $baseEvidenceDir . $_POST['EvidenceDirectory'];
			// Creates the case directory for uploading evidence
			if(!is_dir($evidenceDir)){
				mkdir($evidenceDir);
			} 

			$allFilesAreValid = validateUploadedFiles();
			$uploadSuccessful = moveUploadedFilesToZip($allFilesAreValid, $evidenceDir);

			if($uploadSuccessful){
				sendUserHome();
			} else {
				echo "Failed to upload the given files";
			}
		}
	}

	sendUserHome();

?>