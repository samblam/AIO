function getFileInfo(){
    // These file requirement values are based on the values set in the php.ini file on the server.
    // Therefore, these requirement values should match those found in the php file validation methods.
    maxNumberOfFiles = 20;
    maxFileSize = 2097152;

    addEvidenceButton = document.getElementById("AddEvidence");
    submitFormAButton = document.getElementById("SubmitFormA");

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

        for(var i = 0; i < uploadedFiles.length; i++) {
            var fileListItem = document.createElement('li');
            var p = document.createElement('p');

            if(uploadedFiles[i].size <= maxFileSize){
                p.textContent = uploadedFiles[i].name + ' (' + getFileSizeString(uploadedFiles[i].size) + ') ';
            } else {
                p.textContent = uploadedFiles[i].name + ' (' + getFileSizeString(uploadedFiles[i].size) + ') - file exceeds maximum file size (' + getFileSizeString(maxFileSize) + ')';
                p.style.color = "red";
                allFilesValid = false;
            }

            fileListItem.appendChild(p);
            fileList.appendChild(fileListItem);
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