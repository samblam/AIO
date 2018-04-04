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
        <script src="../JS/top-header.js"></script>

    </head>
    <body style="margin: auto;">
        <!-- Headder div + Logout button -->
        <div class="top-header"></div>

        <div>
            <h2>Active Cases</h2>
        </div>

        <!-- Table div -->
        <!-- TODO: Table will need to populate based on the entries in the DB(server side) -->
        <!-- TODO: I think to properly link the buttons, each row might have to be an input form(haven't looked it up) -->
        <div>
            <span class="pull-right" style="display: inline-block;">
                <button class="btn btn-success" onclick="location.href='forma.php'" style="font-size: 16px; vertical-align: bottom;">Submit new case</button>
            </span>

            <table class="table table-bordered" style="font-size: 12px;">
                <thead class="cases-table">
                    <tr>
                        <th>Student Banners</th>
                        <th>Student Names</th>
                        <th>AIO</th>
                        <th>Action required</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>B00000001</td>
                        <td>Mark Otto</td>
                        <td>Fred</td>
                        <td><button class="custombtn btn btn-danger">Yes</button></td>
                        <td><button class="btn btn-primary">View Case</button></td>
                    </tr>
                    <tr>
                        <td>B00000002</td>
                        <td>Moe</td>
                        <td>Fred</td>
                        <td>No</td>
                        <td><button class="btn btn-primary">View Case</button></td>
                    </tr>
                    <tr>
                        <td>B00000003</td>
                        <td>Dooley</td>
                        <td>Matt</td>
                        <td>No</td>
                        <td><button class="btn btn-primary">View Case</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>