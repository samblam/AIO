<?php

//Open the db connection
include_once '../includes/db.php';
//Check if the form variables have been submitted, store them in the session variables
include '../includes/formProcess.php';
include_once 'page.php';

?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Portal</title>
        <link rel="stylesheet" href="../CSS/main.css">
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
                        <td>Case Number</td>
                        <!-- needs to pull from database -->
                        <td>000000</td>
                    </tr>
                    <tr>
                        <td>Student(s) name</td>
                        <!-- needs to pull all students names from backend -->
                        <td>

                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" style="font-size: 12px;" data-toggle="dropdown">Students
                                <span class="caret"></span></button>
                                <ul class="dropdown-menu" onchange="warning()">
                                    <!-- needs to add an <li> tage for each student in the case upon loading page; BACKEND -->
                                    <li><a href="student-case-information.php"> TestStudent Name</a></li>

                                </ul>
                            </div>

                        </td>


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
                        <td>Ongoing</td>
                    </tr>

                </tbody>
            </table>
        </div>


        <!-- Form display div -->
        <div>
            <ul class="nav nav-tabs nav-justified">
                <li class="active"><a data-toggle="tab" href="#forma">Form A</a></li>
            </ul>
            <div class="tab-content">
                <div id="forma" class="tab-pane fade active in">
                    <?php include 'forma.php' ?>
                </div>
            </div>
        </div>
    </body>
</html>
