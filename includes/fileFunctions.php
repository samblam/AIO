<?php

	function validateUploadedFiles(){		 
		/* the file validation values should be based on the values set in the php.ini file
			important values for max file size:
				'post_max_size'
				'memory_limit'
				'upload_max_filesize'

			the smallest of these values should be used as the max filesize.
			these values can be retrieved using ini_get('var_name'), however they are reported as strings with letters 
			in them and would require complex parsing.
		*/

		/* max file size in bytes*/
		$maxFileSize = 2097152;

		$numUploadedFiles = count($_FILES['fileInput']['name']);

		if($numUploadedFiles > ini_get('max_file_uploads')){
			return false;
		}

		// assume all files are valid
		$allUploadedFilesAreValid = true;

		for($i = 0; $i < $numUploadedFiles; $i++){
		    // Check if there was an error uploading the file and check the file size 
		    if($_FILES["fileInput"]["error"][$i] == 1 || $_FILES["fileInput"]["size"][$i] > $maxFileSize){
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
		        	// sanitize the file name to remove unwanted characters. Replace non valid characters with an underscore
		            $zip->addFile($_FILES["fileInput"]["tmp_name"][$i], preg_replace('/[^A-Za-z0-9.]+/', '_', $_FILES["fileInput"]["name"][$i]));
		        }

		        $zip->close();
		       	return true;
		    }
		} 

		return false;
	}
?>