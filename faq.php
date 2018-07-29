<?php
require_once 'includes/session.php';

//These are the variables that will later be converted to session variables
$role;
$csid;
//benefited from https://www.tutorialrepublic.com/twitter-bootstrap-tutorial/bootstrap-accordion.php

?>
<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>FAQ</title>
<link rel="stylesheet" href="../CSS/main.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style type="text/css">
    .panel-title .glyphicon{
        font-size: 14px;
    }
</style>
<script>
    $(document).ready(function(){
        // Add minus icon for collapse element which is open by default
        $(".collapse.in").each(function(){
            $(this).siblings(".panel-heading").find(".glyphicon").addClass("glyphicon-minus").removeClass("glyphicon-plus");
            });
            
            // Toggle plus minus icon on show hide of collapse element
            $(".collapse").on('show.bs.collapse', function(){
                $(this).parent().find(".glyphicon").removeClass("glyphicon-plus").addClass("glyphicon-minus");
            }).on('hide.bs.collapse', function(){
                $(this).parent().find(".glyphicon").removeClass("glyphicon-minus").addClass("glyphicon-plus");
            });
        });
    </script>
</head>
<body style="margin: auto;">
    <?php 
        include_once './includes/navbar.php';
    ?>
    <br>
    <br>
<div class="bs-example">
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">How do I submit a case?</a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam diam ex, suscipit non congue eget, accumsan a lacus. 
                    Morbi accumsan neque a est mattis porttitor. Vestibulum porttitor eros in vehicula molestie. Vestibulum tristique congue neque ut ornare. 
                    Nam at rhoncus risus.
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">How do I contact the administrator?</a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam diam ex, suscipit non congue eget, accumsan a lacus. 
                    Morbi accumsan neque a est mattis porttitor. Vestibulum porttitor eros in vehicula molestie. Vestibulum tristique congue neque ut ornare. 
                    Nam at rhoncus risus.
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree"> How do I close a case?</a>
                </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam diam ex, suscipit non congue eget, accumsan a lacus. 
                    Morbi accumsan neque a est mattis porttitor. Vestibulum porttitor eros in vehicula molestie. Vestibulum tristique congue neque ut ornare. 
                    Nam at rhoncus risus.
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour"> How do I assign myself a case?</a>
                </h4>
            </div>
            <div id="collapseFour" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam diam ex, suscipit non congue eget, accumsan a lacus. 
                    Morbi accumsan neque a est mattis porttitor. Vestibulum porttitor eros in vehicula molestie. Vestibulum tristique congue neque ut ornare. 
                    Nam at rhoncus risus.
                </div>
            </div>
        </div>
    </div>

    <div class="center-block text-center">
    <p><strong>Note: </strong>For further inquiries please contact the Administrator at
    <a href="mailto:Admin@cs.dal.ca?Subject=Hello%20again" target="_top">Admin@cs.dal.ca</a>
    </p>
    </div>
</div>
</body>
</html>     