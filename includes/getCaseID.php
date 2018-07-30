<?php

/**
 * This function can be included on any page that uses the $_GET method to get the CaseID
 * Does not check that the case ID is a valid one, only that it exists and is a number
 *
 * Returns the case_id variable after checking that it is present.
 *
 * Usage:
 * 		$caseIdValue = getCaseID();
 */

	function getCaseID() {
		if(isset($_GET["case_id"]) && is_numeric($_GET["case_id"])){
			//$caseId = intval($_GET['case_id']);
			return intval($_GET['case_id']);

		} else {
			include_once '../includes/page.php';	//Styling for button
			echo <<<NoIDError
				<link rel="stylesheet" href="../CSS/main.css">	<!-- Styling for error -->
				<p>Error: Case ID not set.</p>
				<a href="ActiveCases.php?" class="btn btn-primary">Return to Active Cases</a>
NoIDError;
			exit();		//Given the error, we stop the rest of the page from rendering.
			return 0;
		}
	}
?>