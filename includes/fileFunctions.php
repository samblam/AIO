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

		// max file size (100MB) in bytes
		$maxFileSize = 104857600;

		$numUploadedFiles = count($_FILES['fileInput']['name']);

		if($numUploadedFiles > ini_get('max_file_uploads')){
			return false;
		}

		// assume all files are valid
		$allUploadedFilesAreValid = true;

		for($i = 0; $i < $numUploadedFiles; $i++){
		    // Check if there was an error uploading the file or if the file size exceeds the max size allowed 
		    if($_FILES["fileInput"]["error"][$i] == 1 || $_FILES["fileInput"]["size"][$i] > $maxFileSize){
		        $allUploadedFilesAreValid = false;
		        break;
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

	function PDFFormA ($prof, $email, $phone, $faculty, $cname, $students, $boos, $date, $comments, $case_id){
	$numberStudents = count($students);
	$fileText = <<<_END
{$prof}
{$phone}
{$email}
{$faculty}
{$faculty}
{$cname}
(anotherEmail@dal.ca)
{$students[0]}
{$boos[0]}
{$date}
NO
N/A
N/A
_END;

	$fileName = "../LaTeX/info.txt";
	$fileHandle = fopen($fileName, "w") or die("Sorry! Unable to open file!");
	fwrite($fileHandle, $fileText);
	fclose($fileHandle);
	//Change to run
	$dir = "C:\Users\stuam\Google Drive\CSCI 3190\AIO Curr\aio-summer-2018\LaTeX";
	//Maybe a security issue
	$redir = chdir("{$dir}");
	$console = shell_exec("xelatex FormA.TeX");
	$rename = "{$case_id}.pdf";
	//To change name and file location of PDF
	rename ("FormA.pdf", "../evidence/{$case_id}/{$rename}");
}
?>