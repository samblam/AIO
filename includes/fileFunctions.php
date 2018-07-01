<?

	function validateUploadedFiles(){		 
		$numUploadedFiles = count($_FILES['fileInput']['name']);

		// assume all files are valid
		$allUploadedFilesAreValid = true;

		for($i = 0; $i < $numUploadedFiles; $i++){
		    // Check if there was an error uploading the file
		    if($_FILES["fileInput"]["error"][$i] == 0){
		        $allUploadedFilesAreValid = false;
		    }
		}

		return $allUploadedFilesAreValid;
	}

	function moveUploadedFilesToZip($allUploadedFilesAreValid, $zipFileDir){
		$numUploadedFiles = count($_FILES['fileInput']['name']);

		if($allUploadedFilesAreValid && $numUploadedFiles > 0){
			$evidenceZipFileName = "evidence.zip";
		    $zip = new ZipArchive();
		    $zipPath = $zipFileDir . '/' . $evidenceZipFileName;

		    if(!$zip->open($zipPath, ZIPARCHIVE::CREATE)) { 
		        echo "ZIP creation failed. Could not upload the files.";
		    } 

		    else {
		        // move the uploaded files from the temporary directory to the evidence zip file
		        for($i = 0; $i < $numUploadedFiles; $i++){
		            $zip->addFile($_FILES["fileInput"]["tmp_name"][$i], $_FILES["fileInput"]["name"][$i]);
		        }

		        $zip->close();
		       	return true;
		    }
		} 

		return false;
	}
?>