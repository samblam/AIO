<?php
	require_once 'includes/session.php';
	include_once "includes/db.php";
	// This file is accessed when .zip files within the evidence directory are accessed directly via the URL.
	// The .htaccess file reroutes the direct request to this file instead. 
	// $_GET["file_name"] will contain the sub-URL that the user tried to access.

	function deny_access(){
		header('HTTP/1.0 403 Forbidden');
		die();
	}

	function does_not_exist(){
		echo "<html>
				<head>
					<title>404 Not Found</title>
				</head>
				<body>
					<h1>Not Found</h1>
					<p>The requested evidence files could not be found on the server.</p>
				</body>
			</html>";
		die();
	}

	if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] && 
			isset($_SESSION["csid"]) && isset($_SESSION["role"]) && isset($_POST["caseId"])){

  		$caseId = intval($_POST['caseId']);
  		$userId = intval($_SESSION["csid"]);
  		$role = $_SESSION["role"];

  		// get case information to check if the user has permission to view this file
  		$conn = OpenCon();

	    $statement = $conn->prepare("SELECT evidence_fileDir, aio_id, prof_id FROM active_cases WHERE case_id = " . $caseId);
	    if(!$statement->execute()){
	        echo "Execute failed: (" . $statement->errno . ") " . $statement->error . "<br>Please try again.";
	    }

	    $statement->bind_result($evidence_folder, $aio_id, $prof_id);
	    $statement->fetch();

	    CloseCon($conn);

	    if ($role == "aio" || $role == "professor" || $role == "admin"){
	    	$path_to_evidence_dir = "evidence/" . $evidence_folder . "/evidence.zip";
	    	$path_to_PDF_dir = "evidence/" . $caseId . "/{$caseId}.pdf";
            if (isset ($_POST['evidenceLink']) && $evidence_folder != "" && file_exists($path_to_evidence_dir)) {
                // user should be shown the link to the evidence file
                header("Cache-Control: public");
				header("Content-Description: File Transfer");
				header("Content-Disposition: attachment; filename=evidence.zip");
				header("Content-Type: application/zip");
				header("Content-Transfer-Encoding: binary");
				readfile($path_to_evidence_dir);
				exit();
            }

            else if (isset ($_POST['PDFLink']) && file_exists($path_to_PDF_dir)) {
            	 // user should be shown the link to the evidence file
                header("Cache-Control: public");
				header("Content-Description: File Transfer");
				header("Content-Disposition: attachment; filename={$caseId}.pdf");
				header("Content-Type: application/pdf");
				header("Content-Transfer-Encoding: binary");
				readfile($path_to_PDF_dir);
				exit();
            }
            else {
                // no evidence has been submitted
                does_not_exist();
            }
        }

        else{
            // viewer of the page does not meet the permission requirements to view the evidence
            deny_access();
        }
	} 

	else {
		deny_access();
	}

?>
