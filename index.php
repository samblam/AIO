<?php

session_start();
//Open the db connection
include 'includes/db.php';
//These are the variables that will later be converted to session variables
$role;
$csid;
//Check if the form variables have been submitted, store them in the session variables
include 'includes/formProcess.php';

?>


<!DOCTYPE html>
<html>    
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Portal</title>
        <link rel="stylesheet" href="CSS/main.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body style="margin: auto;">
        
        <h2>Login</h2>
        
        <!-- Main Navagation tabs, each containing their own logins -->
        
        <!-- Login screen ready for backend php-->
	<form action="includes/login.php" method="post">

            <div class="container">
                <label for="uname"><b>Enter CS ID and password</b></label>
                <br>
                <input type="text" placeholder="Enter CS ID" name="uname" required>
                <br>
                <input type="password" placeholder="Enter Password" name="psw" required>
                <br><br>
                <!-- Roll select -->
                <label> Login as:
                    <br>
                    <select class="selectpicker" id="login_role" name="role"> 
                            <option value="professor">Professor</option>
                            <option value="aio">AIO</option>
                            <option value="admin">Admin</option>
                    </select>
                </label>    
                <br>
                <!-- 
                Temp button links to the active case screen.
                Use the second commented out button for when PHP is enabled.-->
                <!--<a href="HTML/ProfessorActiveCases.php" class="btn btn-info" role="button" name"LoginSubmit">Submit</a>-->
                <!--<button class="btn btn-info" type="submit" name="LoginSubmit">Submit</button>-->
               <input class="btn btn-info" type="submit" value="Submit" name="LoginSubmit">
            </div>
        </form>
    </body>
</html>

