<!-- HTML file for Form B -->
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
        <link rel="stylesheet" href="../CSS/formb.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
        <script src="../JS/top-header.js"></script>
        <script src="../JS/formb.js"></script>
    </head>
    
    <!-- Form B -->
    
    <body style="margin: auto;">
        <div class="top-header">
	    <button class="btn btn-default" type="button">Back</button><button class="btn btn-default pull-right" type="button">Logout</button>
	</div>
        <h2 style="text-align: left">Form B</h2>
        <br>
        
        <!-- Division for general information info. -->
        <form method="post" action="../includes/formProcess.php">
        <div class="form-horizontal">    
            <div class="form-group">
                <label for="aioName" class="col-sm-3 control-label">AIO Name:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" style="margin-bottom: 3px" placeholder="Name" id="aioName" name="aioName" required>
                </div>
            </div>

            <div class="form-group">
                <label for="profName" class="col-sm-3 control-label">Instructor Name:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" style="margin-bottom: 3px" placeholder="Name" id="profName" name="profName" required>
                </div>
            </div>
                
            <div class="form-group">
                <label for="profEmail" class="col-sm-3 control-label">Instructor Email:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" style="margin-bottom: 3px" placeholder="Email" id="profEmail" name="profEmail" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 control-label">Faculty:</label>
                <div class="dropdown col-sm-8">
                    <select class="selectpicker" id="faculty" name="faculty" data-show-subtext="true" data-live-search="true" required> 
                        <option data-subtext="Faculty of Computer Science">FCS</option>
                    </select>
                </div>
            </div>
            
            <br>
            
            <div class="form-group">
                <label for="studentName" class="col-sm-3 control-label">Student Name:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" style="margin-bottom: 3px" placeholder="Name" id="studentName" name="studentName" required>
                </div>
            </div>
            <div class="form-group">
                <label for="studentNumber" class="col-sm-3 control-label">Student ID:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" style="margin-bottom: 3px" placeholder="B00" id="studentNumber" name="studentNumber" required>
                </div>
            </div> 
            
            <div class="form-group">
                <label class="col-sm-3 control-label">Class Name:</label>
                <div class="dropdown col-sm-8">
                    <select class="selectpicker" id="class-name" name="class-name" data-show-subtext="true" data-live-search="true" required> 
                        <option data-subtext="Communication Skills: Oral and Written">CSCI 2100</option>
                            <option data-subtext="Network Security">CSCI 4174</option>
                            <option data-subtext="Introduction to web site creation">INFX 1606</option>
                            <option data-subtext="Etc">Etc XXXX</option>
                    </select>
                </div>
            </div>
            
            <br>
            
            <label>Name and Email of instructor authorized to submit grades, if different from above:</label>
            <div class="form-group">
                <label for="profName"  class="col-sm-3 control-label">Instructor Name:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" style="margin-bottom: 3px" placeholder="Name" id="profName" name="profName">
                </div>
            </div>
            
            <div class="form-group">
                <label for="profEmail" class="col-sm-3 control-label">Instructor Email:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" style="margin-bottom: 3px" placeholder="Email" id="profEmail" name="profEmail">
                </div>
            </div>  
        </div>
        
        <br>
        
        <!-- Division for section A. -->
        <div class="form-horizontal">
            
            <!-- Section A checkbox -->
            <div class="form-group">
                <div class="checkbox col-sm-offset-3 col-sm-8">
                    <label>
                        <input type="checkbox" onchange="sectionA()">A. Transfer of Ratification.
                    </label>
                </div>
            </div>
            
            <!-- Section A content -->
            <div class="form-group">
                <div class="section-a-content" id="section-a-content" style="display: none">
                    
                    <label for="allegationDate" class="col-sm-offset-3 col-sm-5 control-label">Date of allegation by Instructor:</label>
                    <div class="col-sm-3">
                        <input class="form-control" placeholder="MM/DD/YYYY" style="margin-bottom: 3px" id="allegationDate" name="allegationDate">
                    </div>
                    
                    <label for="allegationReceived" class="col-sm-offset-3 col-sm-5 control-label">Date of allegation and supporting documents received by AIO:</label>
                    <div class="col-sm-3">
                        <input class="form-control" placeholder="MM/DD/YYYY" style="margin-bottom: 3px" id="allegationReceived" name="allegationReceived">
                    </div>
                    
                    <label for="allegationStudent" class="col-sm-offset-3 col-sm-5 control-label">Date AIO allegation letter sent to student:</label>
                    <div class="col-sm-3">
                        <input class="form-control" placeholder="MM/DD/YYYY" style="margin-bottom: 3px" id="allegationStudent" name="allegationStudent">
                    </div>
                    
                    <label for="allegationMeeting" class="col-sm-offset-3 col-sm-5 control-label">Date of AIO Meeting with Sudent:</label>
                    <div class="col-sm-3">
                        <input class="form-control" placeholder="MM/DD/YYYY" style="margin-bottom: 3px" id="allegationMeeting" name="allegationMeeting">
                    </div>
                    
                    <label class="col-sm-offset-3 col-sm-5 control-label">Did instructor attend meeting:</label>
                    <div class="col-sm-3">
                        <label class="radio-inline">
                            <input type="radio" name="inlineRadioOptions" style="margin-bottom: 3px" id="inlineRadio1" value="option1"> Yes.
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="inlineRadioOptions" style="margin-bottom: 3px" id="inlineRadio2" value="option2"> No.
                        </label>
                    </div>
                    
                    <label for="organization" class="col-sm-offset-3 col-sm-5 control-label">Name and organization of advocate (enter n/a in none):</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" style="margin-bottom: 3px" placeholder="Name" id="organization" name="organization">
                    </div>
                    
                    <label class="col-sm-offset-3 col-sm-5 control-label">Is this a common allegation:</label>
                    <div class="col-sm-3">
                        <label class="radio-inline">
                            <input type="radio" name="inlineRadioOptions" style="margin-bottom: 3px" id="inlineRadio1" value="option1"> Yes.
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="inlineRadioOptions" style="margin-bottom: 3px" id="inlineRadio2" value="option2"> No.
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Division for section B. -->
        <div class="form-horizontal">
            
            <!-- Section B checkbox -->
            <div class="form-group">
                <div class="checkbox col-sm-offset-3 col-sm-8">
                    <label>
                        <input type="checkbox" onchange="sectionB()">B. Transfer for Referral to Senate Discipline Committee.
                    </label>
                </div>
            </div>
            
            <!-- Section B content -->
            <div class="form-group">
                <div class="section-b-content" id="section-b-content" style="display: none">
                    
                    <label class="col-sm-offset-3 col-sm-3 control-label">Reason for referral:</label>
                    <div class="checkbox col-sm-offset-3 col-sm-8">
                        <label>
                            <input type="checkbox">Student has prior offence on record and this is not a common allegation.
                        </label>
                    </div>
                    
                    <div class="checkbox col-sm-offset-3 col-sm-8">
                        <label>
                            <input type="checkbox">Student has failed to attend scheduled meeting(s).
                        </label>
                    </div>
                    
                    <label for="reason" class="col-sm-offset-3 col-sm-1 control-label">Reason:</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" style="margin-bottom: 3px" placeholder="Reason" id="reason" name="reason">
                    </div>
                    
                    <div class="checkbox col-sm-offset-3 col-sm-8">
                        <label>
                            <input type="checkbox">Student did not accept assessment and/or recommended penalty.
                        </label>
                    </div>
                    
                    <div class="checkbox col-sm-offset-3 col-sm-8">
                        <label>
                            <input type="checkbox">Level of penalty is at issue.
                        </label>
                    </div>
                    
                    <div class="checkbox col-sm-offset-3 col-sm-8">
                        <label>
                            <input type="checkbox">This is a common allegationand due to prior involvement of this student. The student has accepted that the allegation is proven.
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Division for the date -->
        <div class="form-horizontal">
            
            <div class="form-group">
                <label for="signDate" class="col-sm-3 control-label">Date:</label>
                <div class="date col-sm-8">
                    <input class="form-control" placeholder="MM/DD/YYYY" style="margin-bottom: 3px" id="signDate" name="signDate" required>
                </div>
            </div>
        </div>
        
        <!-- Division for the submit button. -->
        <div>
            <button type="submit" class="btn btn-success center-block" name="SubmitFormB">Submit</button>
        </div>
	</form>
    </body>
</html>


