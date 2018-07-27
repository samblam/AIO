<?php
require_once '../includes/session.php';

require_once 'secure.php';
//Open the db connection
include_once '../includes/db.php';
//Check if the form variables have been submitted, store them in the session variables
include '../includes/formProcess.php';
include_once '../includes/page.php';

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Portal</title>
        <link rel="stylesheet" href="../CSS/main.css">
        <link rel="stylesheet" href="../CSS/formc.css">
        <script src="../JS/formc.js"></script>
    </head>
    <body style="margin: auto;">
        <?php include_once '../includes/navbar.php'; ?>
        <div class="form-container">
            <h2 class="form-d-title">Form C</h2>
            <p>AIO Allegation Letter</p>
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

                <!-- TODO: Fix date select box -->
                <div class="form-group date">
                    <label class="col-sm-3" >Meeting Date/Time:</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" placeholder="MM/DD/YYY" id="date" name="date" required>
                    </div>
                    <div class="col-sm-6">
                        <input class="timepicker form-control" id="time" name="time" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Meeting Location:</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" placeholder="Room #" id="room_num" name="room_num" required>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="Building" id="building" name="building" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">AIO Phone Number:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="(XXX) XXX-XXXX" id="aio_phone" name="aio_phone" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">AIO Email:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="AIO Email" id="aio_email" name="aio_email" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3"><input type="checkbox"> Notify Professor</label>
                </div>

                <!--save button, submit button-->
                <div class="form-group">
                    <div class="center-block text-center">
                        <button type="submit" class="btn btn-primary" name="SubmitFormC">Preview PDF</button>
                        <button type="submit" class="btn btn-primary" name="SaveFormA">Save</button>
                        <button type="submit" class="btn btn-success" name="SubmitFormA">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
