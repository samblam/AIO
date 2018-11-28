<?php
require_once '../includes/session.php';

require_once 'secure.php';
//Open the db connection
include '../includes/db.php';
//Check if the form variables have been submitted, store them in the session variables
include '../includes/formProcess.php';
?>

<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../CSS/formA.css">

    <link rel="stylesheet" href="../CSS/main.css">

    <!-- bootstrap imports -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  </head>

  <body style="margin: auto;">
        <?php include_once '../includes/navbar.php'; ?>
        <div>
            <h2>Add <?php  $user = $_GET['user']; echo $user; ?></h2>



<?php

if($user == 'AIO'){	
?>
<div class="form-container">
<form class="form-horizontal" action="add_user.php" method="post" enctype="multipart/form-data">
	<div class="form">

			
			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">CSID</label>
				<input type="text" class="form-control" name="csid" required >
			</div>
			</div>

			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">First Name</label>
				<input type="text" class="form-control" name="fname"  required >
			</div>
			</div>
			
			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">Last Name</label>
				<input type="text" class="form-control" name="lname"  required >
			</div>
			</div>
			
			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">Phone Number</label>
				<input type="text" class="form-control" name="phone"  required >
			</div>
			</div>

			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">Email</label>
				<input type="text" class="form-control" name="email"  required >
			</div>
			</div>



			<div class="form-group">
			<div class="col-sm-9">
				<input type="submit" class="btn btn-success" name="add_aio" value="Add New AIO">
				<a  class="btn btn-danger" href="ManageUsers.php">Cancel</a>
			</div>
			</div>


	</div>

</form>
</div>

<?php }?>


<?php

if($user == 'Professor'){	
?>
<div class="form-container">
<form class="form-horizontal" action="add_user.php" method="post" enctype="multipart/form-data">
	<div class="form">

			
			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">CSID</label>
				<input type="text" class="form-control" name="csid" required >
			</div>
			</div>

			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">First Name</label>
				<input type="text" class="form-control" name="fname"  required >
			</div>
			</div>
			
			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">Last Name</label>
				<input type="text" class="form-control" name="lname"  required >
			</div>
			</div>
			
			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">Phone Number</label>
				<input type="text" class="form-control" name="phone"  required >
			</div>
			</div>

			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">Email</label>
				<input type="text" class="form-control" name="email"  required >
			</div>
			</div>

			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">Faculty</label>
				<input type="text" class="form-control" name="faculty" required >
			</div>
			</div>

			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">Department</label>
				<input type="text" class="form-control" name="department" required >
			</div>
			</div>

			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">Alternative Email</label>
				<input type="text" class="form-control" name="aemail" required >
			</div>
			</div>



			<div class="form-group">
			<div class="col-sm-9">
				<input type="submit" class="btn btn-success" name="add_prof" value="Add New Professor">
				<a  class="btn btn-danger" href="ManageUsers.php">Cancel</a>
			</div>
			</div>


	</div>

</form>
</div>

<?php }?>

<?php 


if($_SERVER["REQUEST_METHOD"] == "POST") {


	//$conn = OpenCon();

    if (isset($_POST['add_aio'])) {
        //get all the values from the form
        	$aio_csid=$_POST['csid'];
        	$aio_fname=$_POST['fname'];
        	$aio_lname=$_POST['lname'];
        	$aio_phone=$_POST['phone'];
        	$aio_email=$_POST['email'];

        insertUserAIO($aio_csid,$aio_fname,$aio_lname,$aio_phone,$aio_email);

			//make an sql statment
/*			$sql_aio="INSERT INTO aio(csid, fname, lname, phone, email) VALUES ('$aio_csid', '$aio_fname','$aio_lname','$aio_phone' ,'$aio_email')";

			$result = $conn->query($sql_aio);
*/
			?>
			<script type="text/javascript">
				window.location.href = 'ManageUsers.php';
			</script>
			<?php



    }
    if (isset($_POST['add_prof'])) {
        //get all the values from the form
        	$prof_csid=$_POST['csid'];
        	$prof_fname=$_POST['fname'];
        	$prof_lname=$_POST['lname'];
        	$prof_phone=$_POST['phone'];
        	$prof_email=$_POST['email'];
        	$prof_faculty=$_POST['faculty'];
        	$prof_department=$_POST['department'];
        	$prof_aemail=$_POST['aemail'];




			//make an sql statment
            insertUserPROF($prof_csid,$aio_fname,$aio_lname,$prof_phone,$prof_email,$prof_faculty,$prof_department,$prof_aemail);

/*			$sql_prof="INSERT INTO professor(csid, fname, lname, phone, email, faculty, department, alt_email) VALUES ('$prof_csid', '$prof_fname','$prof_lname','$prof_phone' ,'$prof_email','$prof_faculty','$prof_department','$prof_aemail')";

			$result = $conn->query($sql_prof);
*/
			?>
			<script type="text/javascript">
				window.location.href = 'ManageUsers.php';
			</script>
			<?php



    }




    //CloseCon( $conn );

 }




?>





      </body>
</html>