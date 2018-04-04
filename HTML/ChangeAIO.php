<?php

session_start();
//Open the db connection
include '../includes/db.php';
//Check if the form variables have been submitted, store them in the session variables
include '../includes/formProcess.php';

?>
<!DOCTYPE html>
<html>
    
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Portal</title>
        <link rel="stylesheet" href="../CSS/main.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
        <script src="../JS/top-header-full.js"></script>

    </head>
    <body style="margin: auto;">
        <!-- Headder div + Logout button -->
        <div class="top-header-full"></div>

        <div style="display: inline-block;">
            <h2>Case Information</h2>

        </div>
            
        <!-- Newcase button div -->
        <!-- TODO: Should link to from A? -->

        <!-- Table div -->
        <!-- TODO: Table will need to populate based on the entries in the DB(server side) -->
        <!-- TODO: I think to properly link the buttons, each row might have to be an input form(haven't looked it up) -->
        <div>   
        
            <table class="table table-bordered" style="font-size: 14px;">
                <tbody>
                    <tr>
                        <td>Banner number</td>
                        <td>B00000000</td>
                    </tr>
                    <tr>
                        <td>Student(s) name</td>
                        <td>Mark Auto</td>
                    </tr>
                    <tr>
                        <td>Professor</td>
                        <td>Fred</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>Jan 31st 2017</td>
                    </tr>
                    <tr>
                        <td>Files</td>
                        <td><a href="#">Link.zip</a></td>
                    </tr>            
                    
                    <tr>
                        <td>Case status</td>
                        <td>Waiting for student to confirm meeting date</td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
        
        <div>
            Current AIO: Fred <br><br>
            New AIO: 
                    <select class="border border-dark">
                        <option selected>Select New</option>
                        <option>AIO 1</option>
                        <option>AIO 2</option>
                    </select>
            <button class="btn btn-success">Submit</button>
        </div>
        
    </body>
</html>