<?php

/**
 * This file should be included by any PHP page
 * that deals with individual cases, and requires that there be a case ID.
 *
 * Sets the $caseId variable after checking that it is present.
 * 
 */

	function getCaseID() {
		if(isset($_GET["case_id"]) && is_numeric($_GET["case_id"])){
			//$caseId = intval($_GET['case_id']);
			return intval($_GET['case_id']);
	
			echo <<<Debug
				<p>Case ID: $caseId</p>
Debug;
		} else {
			include_once 'page.php';	//Styling for button
			echo <<<NoIDError
				<link rel="stylesheet" href="../CSS/main.css">	<!-- Styling for error -->
				<p>Error: Case ID not set.</p>
				<a href="ActiveCases.php?" class="btn btn-primary">Return to Active Cases</a>
NoIDError;
			exit();
			return null;
		}
	}
?>