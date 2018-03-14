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
        <link rel="stylesheet" href="../CSS/formc.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="../JS/top-header.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
        <script src="../JS/formc.js"></script>
        <script>

        </script>
    </head>
    <body style="margin: auto;">
        <div class="top-header">
	    <button class="btn btn-default" type="button">Back</button><button class="btn btn-default pull-right" type="button">Logout</button>
	</div>
        <div class="container">
            <h2 class="form-d-title">Form C</h2>
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
                <div class="form-group">
                    <div class="center-block text-center">
                        <button type="submit" class="btn btn-success" name="SaveFormC">Save</button>
                        <button type="submit" class="btn btn-primary" name="SubmitFormC">Create PDF</button>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
