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
        <link rel="stylesheet" type="text/css" href="../CSS/main.css">
        <link rel="stylesheet" type="text/css" href="../CSS/formd.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
        <script src="../JS/formd.js"></script>
    </head>
    <body style="margin: auto;">
        <div class="container">
            <h2 class="form-d-title">Form D</h2>
            <p>AIO Assessment and Recommendations</p>
        </div>
        <div class="form-container">
            <form class="form-horizontal" action="../includes/processForm.php" method="post">
                <div class="form-group">
                    <label class="control-label col-sm-3">Student Name:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="Student Name" id="student_name" name="student_name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Student B00:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="B00 Number" id="b00_num" name="b00_num" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Professor Name:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="Professor Name" id="prof_name" name="prof_name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Class Name:</label>
                    <div class="dropdown col-sm-4">
                        <select class="selectpicker" id="class_name" name="class_name" data-show-subtext="true" data-live-search="true">
                            <option data-subtext="Communication Skills: Oral and Written">CSCI 2100</option>
                            <option data-subtext="Network Security">CSCI 4174</option>
                            <option data-subtext="Introduction to web site creation">INFX 1606</option>
                            <option data-subtext="Etc">Etc XXXX</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3">Allegation(s) named in AIO letter to student:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="Allegations" id="allegations" name="allegations" required>
                    </div>
                </div>

                <!-- TODO: Fix date select box -->
                <div class="form-group date">
                    <label class="col-sm-3" >Date:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="MM/DD/YYY" id="date" name="date" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3">Decision(s):</label>
                    <div class="col-sm-9">
                        <textarea class="form-control text-box" rows="3" id="decision" name="decision">
                        </textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Penalty:</label>
                    <div class="dropdown col-sm-4">
                        <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="penalty" name="penalty" multiple>
                            <option data-subtext="2 days">Suspension</option>
                            <option data-subtext="Penalty #2">Poop</option>
                            <option data-subtext="Poop">:)</option>
                        </select>
                    </div>
                </div>

                <!-- TODO: Date at bottom of form -->
                <div class="date-form">
                    <label type="text">Date: </label>
                    <text class="date-bottom"> [Date from other form]</text>
                </div>
                <button type="submit" class="btn btn-success center-block" name="SubmitFormD">Submit</button>
            </form>
        </div>
    </body>
</html>
