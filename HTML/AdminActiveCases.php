<?php

session_start();
//Open the db connection
include '../includes/db.php';
//Check if the form variables have been submitted, store them in the session variables
include '../includes/formProcess.php';

?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Admin</title>
        <link rel="stylesheet" href="../CSS/formA.css">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!-- the header; logout and back buttons -->
        <script src="../JS/top-header.js"></script>
    </head>
    <header class="container">
                <!-- logout button -->

    </header>
    <body>
        <!-- header (logout and back buttons) -->
        <div class="top-header"></div>

   <!--     <div class="row">
            <div class="col-sm-10"></div>
            <button id= "logout" class="btn btn-default spacing col-sm-1">Logout</button>
        </div> -->

        <div class="row">
            <h1 class="col-sm-3">Admin Page</h1>
            <button class="btn btn-success spacing col-sm-1">+ Case</button>
            <div class="col-sm-6"></div>
            <button id= "logout" class="btn btn-default spacing col-sm-1">Logout</button>
        </div>

        <div class="container">
            <table class="table table-hover ">
                <thead class="cases-table">
                    <tr>
                        <!--should we include a section to indicate the case has been rejected or accepted by the senate? Or rejected by the AIO (case closed), so the case can be deleted. Also, the cases should be ordered by newest cases first, or by whichever field they choose.-->
                        <th>Case ID</th>
                        <th>Course</th>
                        <th>Professor Name</th>
                        <th>AIO Name</th>
                        <th>Action Required</th>
                        <th>Action Needed By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- cases need to be taken from database -->
                    <tr>
                        <td>123</td>
                        <td>CSCI 2132</td>
                        <td>Bob Parr</td>
                        <td>Mr. Incredible</td>
                        <td>No</td>
                        <td>N/A</td>
                        <!-- make functional! -->
                        <td>
                            <select class="btn">
                                <optgroup>
                                    <option selected>Action</option>
                                    <option>View</option>
                                    <option>Assign AIO</option>
                                </optgroup>
                                <optgroup>
                                    <option>Delete</option>
                                </optgroup>
                            </select>
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>
    </body>
</html>
