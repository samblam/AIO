<?php
require_once 'includes/session.php';
//Open the db connection
include 'includes/db.php';
//These are the variables that will later be converted to session variables
//Check if the form variables have been submitted, store them in the session variables
?>

<!DOCTYPE html>
<html>    
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Portal</title>
        <link rel="stylesheet" href="CSS/main.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script type="text/javascript">
       $(document).ready(function(){
           event.preventDefault();
           $("#LoginSubmit").click(function(){
           $.ajax({
               url:'includes/login.php',
               type:'post',
               data:formData,
               contentType: false,
               processData: false,
               success:function(data){
                   var msg = "";
                   if("error" in data){
                       msg = data['error'];
                       $("#message").html(msg);
                   }
               }
           });
           });
       });

        </script>
    </head>
    <body style="margin: auto;">
        
        <h2>Login</h2>
        
        <!-- Main Navigation tabs, each containing their own logins -->

        <!-- Login screen ready for backend php -->
    
        <form action="includes/login.php" method="post" enctype="multipart/form-data">
        <!-- Login screen ready for back end php -->

            <div class="container">
                <div id = "message"><div>
                <label for="uname"><b>Enter CS ID and password</b></label>
                <br>
                <input type="text" placeholder="Enter CS ID" name="uname" id = "uname" required>
                <br>
                <input type="password" placeholder="Enter Password" name="psw" id = "psw" required>
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
               <input class="btn btn-info" type="Submit" value="Submit" name="LoginSubmit" id="LoginSubmit">
               <div id = "caps_lock"><div>
            </div>
        </from>

    </body>
    <!--Checks for Caps Lock and alerts user if on -->
    <script type="text/javascript">
        $('#psw').keypress(function(e) { 
          var s = String.fromCharCode( e.which );
           if ( s.toUpperCase() === s && s.toLowerCase() !== s && !e.shiftKey ) {
                 $('#caps_lock').html("<span class='text-warning'>Warning: Caps Lock is On!</span>");
           }
           else{
            $('#caps_lock').css('visibility', 'hidden');
           }
        });
    </script>
</html>

