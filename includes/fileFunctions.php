<?

	function validateUploadedFiles(){
		// DEBUG ****************************************************************************************************************************
		$myfile = fopen("DEBUG_LOG.txt", "w") or die("Unable to open file!");
		fwrite($myfile, "$_FILES:\n" . print_r($_FILES, true) . "\n");  
		// DEBUG ****************************************************************************************************************************

		 
		$num_uploaded_files = count($_FILES['fileInput']['name']);
		fwrite($myfile, "num uploaded files: " . $num_uploaded_files . "\n");

		// assume all files are valid. If one or more are not valid, return the user to the previous page
		$all_uploaded_files_are_valid = true;

		for($i = 0; $i < $num_uploaded_files; $i++){
		    $finfo = finfo_open(FILEINFO_MIME_TYPE);
		    $mime = finfo_file($finfo, $_FILES["fileInput"]["tmp_name"][$i]);

		    fwrite($myfile, "mime type: " . $mime . "\n\n");

		    // Check the type of the uploaded file
		    switch ($mime) { 
		      // Each case should be an non-allowed mime file type. Check with client to see which file types should be blocked.
		      /*case '':// non-allowed file type
		        case '':// non-allowed file type
		          $all_uploaded_files_are_valid = false;
		          $error_message = $error_message . "Files of type $mime are not allowed.\n"
		          break;*/
		    }

		    // Check size of upload. 2097152 = 2MB. Will probably want to change based on client's requirements
		    if($_FILES["fileInput"]["size"][$i] > 2097152) { 
		        $all_uploaded_files_are_valid = false;
		        //$error_message = $error_message . "File [name] exceeds maximum file size of ... .\n"
		    }

		    // Check if there was an error uploading the file
		    if($_FILES["fileInput"]["error"][$i] == 1){
		        $all_uploaded_files_are_valid = false;
		        //$error_message = $error_message . "File [name] failed to upload.\n"
		    }
		}

		fclose($myfile);

		return $all_uploaded_files_are_valid;
	}

	function moveUploadedFilesToZip($all_uploaded_files_are_valid, $zip_file_dir){
		$num_uploaded_files = count($_FILES['fileInput']['name']);

		if($all_uploaded_files_are_valid && $num_uploaded_files > 0){
			$evidence_zip_file_name = "evidence.zip";
		    $zip = new ZipArchive();
		    $zip_name = $zip_file_dir . '/' . $evidence_zip_file_name;

		    if(!$zip->open($zip_name, ZIPARCHIVE::CREATE)) { 
		        echo "ZIP creation failed. Could not upload the files.";
		    } 

		    else {
		        // move the uploaded files from the temporary directory to the evidence zip file
		        for($i = 0; $i < $num_uploaded_files; $i++){
		            $zip->addFile($_FILES["fileInput"]["tmp_name"][$i], $_FILES["fileInput"]["name"][$i]);
		        }

		        $zip->close();
		       	return true;
		    }
		} 

		return false;
	}
?>