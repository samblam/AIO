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
        <title>Form A</title>
        <!-- css for this page -->
        <link rel="stylesheet" href="../CSS/formA.css">
        <!-- bootstrap imports-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body style="margin:auto;">
        <form class="border"  action="../includes/processForm.php" method="post" id="forma" enctype="multipart/form-data">
            <div class="row">
                <h1 class="col-sm-11">Form A - Instructor Allegation of Academic Offence</h1>

                <p class="col-sm-11">This form may be used to report any alleged academic offence. Please refer to the Dalhousie University Faculty Discipline Procedures Concerning Allegations of Academic Offences.</p>
            </div>
            <br>

            <div class="form-group row">
                <label for="ProfessorName" class="col-sm-3 col-form-label">Professor:</label>
                <div class="col-sm-8">
                     <input type="text" class="form-control" placeholder="Professor Name" id="ProfessorName" name="ProfessorName" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-sm-3 col-form-label">Email Address:</label>
                <div class="col-sm-8">
                    <input type="email" class="form-control" placeholder="Email" id="email" name="email" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="phoneNum" class="col-sm-3">Phone Number:</label>
                <div class="col-sm-8">
                    <input type="tel" class="form-control" placeholder="Phone Number" id="phoneNum" name="phoneNum" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="faculty" class="col-sm-3">Faculty:</label>
                 <div class="col-sm-8">
                    <input type="text" class="form-control" placeholder="Computer Science" id="faculty" name="faculty" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="dept" class="col-sm-3">Department:</label>
                 <div class="col-sm-8">
                    <input type="text" class="form-control" placeholder="Department" id="dept" name="dept">
                </div>
            </div>
            <!-- course picker drop-down-->
            <div class="form-group row">
                <label for="coursePicker" class="col-sm-3">Course Name: </label>
                <div class="col-sm-4">
                    <select class="form-control" id="coursePicker" name="coursePicker" required>
                    <option selected>Choose..</option>
                        <!-- these options need to pull from list of courses (?) -->
                    <option>CSCI 2132 &nbsp;- Software Development</option>
                    <option>CSCI 1000 &nbsp;- Computer Science I</option>
                    </select>
                </div>
            </div>
            <!-- student name(s) and banner id(s) -->
            <div id="students" name="students">
                <div class="row">
                    <label class="col-sm-3">Student(s): </label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon">Student Name</span>
                            <input type="text" class="form-control" aria-label="Name" required>
                            <span class="input-group-addon">Banner Number</span>
                            <input type="text" class="form-control" aria-label="B00" required>
                        </div>
                    </div>
                </div>
            </div>
            <!-- this refreshes page. try to fix if time permits -->
            <div class="row">
                <label class="col-sm-3"></label>
                <button onClick="add_Student()" class="btn btn-success" id="addStudent" name="addStudent">+ Student</button>
            </div>

            <!--date of allegation -->
            <div class="form-group row">
                <label class="col-sm-3">Date of Alleged Offense:</label>
                <div class="col-sm-4">
                     <input type="date" class="form-control col-sm-4" id="date" name="date" required>
          <!--         <i class="glyphicon glyphicon-calendar form-control-feedback"></i> -->
                </div>
            </div>

            <div class="row">
                <br>
                <p class="col-sm-11">Please describe the incident below or attach a memo. Please attach the original piece of work in which the offence occurred, the class syllabus, and any supporting material. If there are comparisons to be noted between documents (e.g., sections of a paper assignment and Urkund results), instructors are asked to clearly mark relevant sections in ink or highlighter.</p>
            </div>

            <!-- file input for evidence. Needs to append to files selected, not replace. Should be able to remove files too.-->
            <br>
            <div class="form-group row">
                <label for="fileInput" class="col-sm-3">Evidence</label>
                <input type="file" id="fileInput" name="fileInput" multiple>
            </div>

            <!-- text input for additional comments-->
            <br>
            <div class="form-group row">
                <label for="additionalComments" class="col-sm-3">Description of Allegation & Comments:</label>
                <textarea class="form-control" class="col-sm-11" rows="5" placeholder="Write additional comments here" id="additionalComments" name="additionalComments"></textarea>
            </div>

            </form>
        <!--save button, submit button-->
        <div class="row">
            <div class="col-sm-4"></div>
            <button type="submit" class="btn btn-primary col-sm-2" form="forma" name="SaveFormA">Save</button>
            <div class="col-sm-1"></div>
            <button type="submit" class="btn btn-primary col-sm-2" form="forma" name="SubmitFormA">Submit</button>
        </div>
    </body>
    <!-- adds student form on click -->
    <script type="text/javascript">
        $("#addStudent").click(function () {
           $("#students").append('<div class="input-group"> <span class="input-group-addon">Student Name</span> <input type="text" class="form-control" aria-label="Name" required> <span class="input-group-addon">Banner Number</span> <input type="text" class="form-control" aria-label="B00" required> </div>');
        });
    </script>

</html>
