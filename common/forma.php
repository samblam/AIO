<?php
require_once '../includes/session.php';
//Open the db connection
include_once '../includes/db.php';
//Check if the form variables have been submitted, store them in the session variables
include '../includes/formProcess.php';
include '../includes/formFill.php';
include_once '../includes/page.php';

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Portal</title>
        <link rel="stylesheet" href="../CSS/formA.css">
        <link rel="stylesheet" href="../CSS/main.css">
    </head>
    <body style="margin:auto;">

        <div class="form-container">
            <h2 class="form-a-title" style="text-align: left">Form A</h2>
            <p>Report of Academic Integrity Violation</p>
        </div>

        <div class="form-container">
            <form class="form-horizontal" method="post" action="../includes/formProcess.php" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="ProfessorName" class="col-sm-3 control-label">Professor:</label>
                    <div class="col-sm-9">
                         <input type="text" class="form-control" placeholder="Name" id="ProfessorName" name="ProfessorName" required value="<?php if (isset($prof_name)) { echo $prof_name;} ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-sm-3 control-label">Email:</label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control" placeholder="Email" id="email" name="email" required value="<?php if (isset($prof_email)) { echo $prof_email;} ?>">
                    </div>

                </div>

                <div class="form-group">
                    <label for="phoneNum" class="col-sm-3 control-label">Phone Number:</label>
                    <div class="col-sm-9">
                        <input type="tel" class="form-control" placeholder="Phone Number" id="phoneNum" name="phoneNum" required value="<?php if (isset($prof_phone)) { echo $prof_phone;} ?>">
                    </div>
                </div>

                <!-- faculty drop-down-->
                <div class="form-group">
                    <label class="col-sm-3 control-label">Faculty:</label>
                    <div class="dropdown col-sm-9">
                        <select class="selectpicker" id="faculty" name="faculty" data-show-subtext="true" data-live-search="true" required>
                            <option data-subtext="Faculty of Computer Science" value="FCS" >FCS</option>
                        </select>
                    </div>
                </div>

                <!-- course picker drop-down-->
                <div class="form-group">
                    <label class="col-sm-3 control-label">Class Name:</label>
                    <div class="dropdown col-sm-9">
                        <select class="selectpicker" id="class-name" name="class-name" data-show-subtext="true" data-live-search="true" required value="<?php if (isset($course_name)) { echo $course_name;} ?>">
                            <option data-subtext="Communication Skills: Oral and Written" value="CSCI 2100" >CSCI 2100</option>
                                <option data-subtext="Network Security" value="CSCI 4174">CSCI 4174</option>
                                <option data-subtext="Introduction to web site creation" value="INFX 1606">INFX 1606</option>
                                <option data-subtext="Etc" value="Etc XXXX" >Etc XXXX</option>
                        </select>
                    </div>
                </div>

                <!-- student name(s) and banner id(s) -->
                <div>
                    <div class="form-group">
                        <label class="col-sm-3">Student(s): </label>
                        <div class="col-sm-9" id="students_group" name="students_group">
                            <div class="input-group students" name="students">
                                <span class="input-group-addon">Student Name</span>
                                <input type="text" class="form-control" aria-label="Name" required name="Name[]">
                                <span class="input-group-addon">Banner Number</span>
                                <input type="text" class="form-control" aria-label="B00" required name="B00[]">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- this refreshes page. try to fix if time permits -->
                <div class="form-group">
                    <label class="col-sm-3 control-label"></label>
                    <div class="col-sm-9">
                        <button onClick="add_Student()" class="btn btn-success" id="addStudent" name="addStudent" type="button">+ Student</button>
                        <button onClick="remove_student()" class="btn btn-success" id="removeStudent" name="removeStudent" type="button">- Student</button>
                    </div>
                </div>

                <!--date of allegation -->
                <div class="form-group">
                    <label for="date" class="col-sm-3 control-label">Date of Alleged Offense:</label>
                    <div class="col-sm-9">
                        <input class="form-control" placeholder="MM/DD/YYYY" name="DateAlleged" id="date" value="<?php if (isset($date_alleg)) { echo $date_alleg;} ?>" >
                    </div>
                     </div>

                <div class="form-group">
                    <p class="col-sm-12">Please describe the incident below or attach a memo. Please attach the original piece of work in which the offence occurred, the class syllabus, and any supporting material. If there are comparisons to be noted between documents (e.g., sections of a paper assignment and Urkund results), instructors are asked to clearly mark relevant sections in ink or highlighter.</p>
                </div>

                <!-- file input for evidence. Needs to append to files selected, not replace. Should be able to remove files too.-->
                <div class="form-group">
                    <label for="fileInput" class="col-sm-3 control-label">Evidence:</label>
                    <div class="col-sm-9">
                        <input type="file" id="fileInput" name="fileInput" multiple>
                    </div>
                </div>

                <!-- text input for additional comments-->
                <div class="form-group">
                    <label for="additionalComments" class="col-sm-9 control-label">Description of Allegation and Comments:</label>
                    <div class="col-sm-12">
                        <textarea class="form-control" rows="5" placeholder="Write additional comments here" id="additionalComments" name="additionalComments"></textarea>
                    </div>
                </div>

                <!--save button, submit button-->
                <div class="form-group">
                    <div class="center-block text-center">
                        <button type="submit" class="btn btn-primary" name="SubmitFormC">Preview PDF</button>
                        <button type="submit" class="btn btn-primary" name="SaveFormA">Save</button>
			<?php
			  if($_SESSION['role']=="professor" /* add a condition here to check if the form has been submitted already(database check) */){
			    echo"<button type=\"submit\" class=\"btn btn-success\" name=\"SubmitFormA\">Submit</button>";
			  }
			?>
                    </div>
                </div>
            </form>
        </div>


    </body>

    <!-- adds student form on click -->
    <script type="text/javascript">

        $("#addStudent").click(function () {
            $("#students_group").append('<div class="input-group students" name="students"> <span class="input-group-addon">Student Name</span> <input type="text" class="form-control" aria-label="Name" required name="Name[]"> <span class="input-group-addon">Banner Number</span> <input type="text" class="form-control" aria-label="B00" required name="B00[]"> </div>');
        });

        $("#removeStudent").click(function () {
            var students = $(".students");
            if (students.length > 1) {
                students[students.length - 1].remove();
            }
        });

        $(document).ready(function () {
            "use strict";
            var date_input1 = $('input[id="date"]');
            var options = {
                format: 'mm/dd/yyyy',
                todayHighlight: true,
                autoclose: true
            };

            var datepicker = date_input1.datepicker(options)
            datepicker.on('show', function(e) {
                var rect = e.currentTarget.getBoundingClientRect();
                $(this).data('datepicker').picker.css('left', rect.left);
            });
        });
    </script>
</html>
