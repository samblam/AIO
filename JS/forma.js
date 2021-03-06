function getFileInfo(){
    // These file requirement values are based on the values set in the php.ini file on the server.
    // Therefore, these requirement values should match those found in the php file validation methods in fileFunctions.php.
    maxNumberOfFiles = 50;
    maxFileSize = 104857600;

    addEvidenceButton = document.getElementById("AddEvidence");
    submitFormAButton = document.getElementById("SubmitFormA");
    saveFormButton = document.getElementById("SaveFormA");

    uploadedFiles = document.getElementById("fileInput").files;
    fileInfoElement = document.getElementById("fileInfo");
    allFilesValid = true;

    // clear the last file selection from the fileInfo element
    $('#fileInfo').empty();

    if(uploadedFiles.length > maxNumberOfFiles) {
        var fileList = document.createElement('ul');
        fileInfoElement.appendChild(fileList);

        var fileListItem = document.createElement('li');

        var p = document.createElement('p');
        p.textContent = "Too many files were selected. Maximum number of files is " + maxNumberOfFiles;
        p.style.color = "red";
        
        fileListItem.appendChild(p);
        fileList.appendChild(fileListItem);
        allFilesValid = false;

    } else if(uploadedFiles.length > 0){
        var fileList = document.createElement('ul');
        fileInfoElement.appendChild(fileList);

        // check if the total sum of the file sizes exceeds the php limit
        var fileSizeSum = 0;
        for (var i = 0; i < uploadedFiles.length; i++) {
            fileSizeSum += uploadedFiles[i].size;
            if (fileSizeSum >= maxFileSize){
                var fileListItem = document.createElement('li');

                var p = document.createElement('p');
                p.textContent = "The sum of the file sizes selected exceeded the maximum upload size (" + getFileSizeString(maxFileSize) + "). Please contact the administrator if you need assistance with a large case";
                p.style.color = "red";
                
                fileListItem.appendChild(p);
                fileList.appendChild(fileListItem);
                allFilesValid = false;
                break;
            }
        }

        if(allFilesValid){
            // check if any single file exceeds the max file size and inform the user which file is too big
            for (var i = 0; i < uploadedFiles.length; i++) {
                var fileListItem = document.createElement('li');
                var p = document.createElement('p');

                var cleanFileName = sanitizeFileName(uploadedFiles[i].name);

                if(uploadedFiles[i].size < maxFileSize){
                    p.textContent = cleanFileName + ' (' + getFileSizeString(uploadedFiles[i].size) + ') ';
                } else {
                    p.textContent = cleanFileName + ' (' + getFileSizeString(uploadedFiles[i].size) + ') - file exceeds maximum file size (' + getFileSizeString(maxFileSize) + ')';
                    p.style.color = "red";
                    allFilesValid = false;
                }

                fileListItem.appendChild(p);
                fileList.appendChild(fileListItem);
            }
        }

    } else if (addEvidenceButton) {
        // disable the add evidence button when no evidence is selected
        allFilesValid = false;
    }

    if (addEvidenceButton){
        addEvidenceButton.disabled = !allFilesValid;
    }

    if (submitFormAButton){
        submitFormAButton.disabled = !allFilesValid;
    }

    if (saveFormButton){
        saveFormButton.disabled = !allFilesValid;
    }
}


function getFileSizeString(fileSize) {
    if(fileSize < 1024) {
        return fileSize + ' bytes';
    } else if(fileSize >= 1024 && fileSize < 1048576) {
        return (fileSize/1024).toFixed(1) + ' KB';
    } else if(fileSize >= 1048576) {
        return (fileSize/1048576).toFixed(1) + ' MB';
    }
}

function sanitizeFileName(fileName){
    // replace invalid characters in the file name with an underscore
    return fileName.replace(/[^A-Za-z0-9.]+/g, '_');
}