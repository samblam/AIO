<?php

/**
 * This function should be included by any PHP page
 * that deals with individual cases, and requires that there be a case ID.
 * Does not check that the case ID is a valid one, only that it exists and is a number
 *
 * Returns the case_id variable after checking that it is present.
 *
 * Usage:
 * 		$caseId = getCaseID();
 */

	function getCaseID() {
		if(isset($_GET["case_id"]) && is_numeric($_GET["case_id"])){
			//$caseId = intval($_GET['case_id']);
			return intval($_GET['case_id']);

		} else {
			include_once '../includes/page.php';	//Styling for button
			//$errro = $_GET["case_id"];
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