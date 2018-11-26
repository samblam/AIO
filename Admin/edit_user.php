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
    <title>Edit Users</title>
    <link rel="stylesheet" href="../CSS/formA.css">

    <link rel="stylesheet" href="../CSS/main.css">

    <!-- bootstrap imports -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  </head>

  <body style="margin: auto;">
        <?php include_once '../includes/navbar.php'; ?>

        <?php

        if (isset($_GET['aio'])) {

        	$user= 'AIO';

        	$aio_id= $_GET['aio'];

        	$_SESSION["edit"] = $_GET['aio'];

        	$conn = OpenCon();

        	$sql = "SELECT * FROM aio WHERE aio_id = '$aio_id'";

			$result = $conn->query($sql);


			$row= $result->fetch_assoc();
        }

        if (isset($_GET['prof'])) {

        	$user= 'Professor';

        	$prof_id= $_GET['prof'];

        	$_SESSION["edit"] = $_GET['prof'];

        	$conn = OpenCon();

        	$sql = "SELECT * FROM professor WHERE professor_id = '$prof_id'";

			$result = $conn->query($sql);


			$row= $result->fetch_assoc();
        }


        ?>


        <div>
            <h2>Edit <?php echo $user; ?></h2>



<?php

if($user == 'AIO'){
?>
<div class="form-container">
<form class="form-horizontal" action="edit_user.php" method="post" enctype="multipart/form-data">
	<div class="form">

			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">CSID</label>
				<input type="text" class="form-control" name="csid" value=<?php echo $row['csid'];?> required >
			</div>
			</div>

			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">First Name</label>
				<input type="text" class="form-control" name="fname" value=<?php echo $row['fname'];?>  required >
			</div>
			</div>

			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">Last Name</label>
				<input type="text" class="form-control" name="lname" value=<?php echo $row['lname'];?>  required >
			</div>
			</div>

			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">Phone Number</label>
				<input type="text" class="form-control" name="phone" value=<?php echo $row['phone'];?> required >
			</div>
			</div>

			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">Email</label>
				<input type="text" class="form-control" name="email" value=<?php echo $row['email'];?>  required >
			</div>
			</div>


      <div class="form-group col-lg-6">
        <label for="user_image">Signature</label>
          <?php
            if($row['signature'] != "") {
              echo "<br><img width='100' src='../AIOSignatures/{$row['signature']}' alt='thumbnails'><br><br>";
            }
            else {
              echo "<br>No Signature Added<br><br><br>";
            }
          ?>
        <input type="file" name="aio_signature">
      </div>


			<div class="form-group">
			<div class="col-sm-9">
				<input type="submit" class="btn btn-success" name="edit_aio" value="Update AIO" onclick="return confirm('Are you sure you want to make changes to this user?')">
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
<form class="form-horizontal" action="edit_user.php" method="post" enctype="multipart/form-data">
	<div class="form">

			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">CSID</label>
				<input type="text" class="form-control" name="csid" value=<?php echo $row['csid'];?> required >
			</div>
			</div>

			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">First Name</label>
				<input type="text" class="form-control" name="fname" value=<?php echo $row['fname'];?>  required >
			</div>
			</div>

			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">Last Name</label>
				<input type="text" class="form-control" name="lname" value=<?php echo $row['lname'];?>  required >
			</div>
			</div>

			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">Phone Number</label>
				<input type="text" class="form-control" name="phone" value=<?php echo $row['phone'];?> required >
			</div>
			</div>

			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">Email</label>
				<input type="text" class="form-control" name="email" value=<?php echo $row['email'];?>  required >
			</div>
			</div>

			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">Faculty</label>
				<input type="text" class="form-control" name="faculty" value=<?php echo $row['faculty'];?> required >
			</div>
			</div>

			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">Department</label>
				<input type="text" class="form-control" name="department" value=<?php echo $row['department'];?> required >
			</div>
			</div>

			<div class="form-group">
			<div class="col-sm-9">
				<label for="title">Alternative Email</label>
				<input type="text" class="form-control" name="aemail" value=<?php echo $row['alt_email'];?> required >
			</div>
			</div>



			<div class="form-group">
			<div class="col-sm-9">
				<input type="submit" class="btn btn-success" name="edit_prof" value="Update Professor" onclick="return confirm('Are you sure you want to make changes to this user?')">
				<a  class="btn btn-danger" href="ManageUsers.php">Cancel</a>
			</div>
			</div>


	</div>

</form>
</div>

<?php }?>

<?php


if($_SERVER["REQUEST_METHOD"] == "POST") {

	$conn = OpenCon();

    if (isset($_POST['edit_aio'])) {
        //get all the values from the form
        	$aio_id1=(int)$_SESSION["edit"];
        	$aio_csid=$_POST['csid'];
        	$aio_fname=$_POST['fname'];
        	$aio_lname=$_POST['lname'];
        	$aio_phone=$_POST['phone'];
        	$aio_email=$_POST['email'];


          $file = $_FILES['aio_signature'];

          $fileName = $_FILES['aio_signature']['name'];
          $fileTmpName = $_FILES['aio_signature']['tmp_name'];

          $targetFile = "../AIOSignatures/" . $fileName;

          move_uploaded_file($fileTmpName, "$targetFile");


			//make an sql statment
			$sql_aio = "UPDATE aio SET csid='$aio_csid', fname='$aio_fname', lname='$aio_lname', phone='$aio_phone', email='$aio_email', signature='$fileName' WHERE aio_id= '$aio_id1'";


			$result = $conn->query($sql_aio);



			?>
			<script type="text/javascript">
				window.location.href = 'ManageUsers.php';
			</script>
			<?php



    }
    if (isset($_POST['edit_prof'])) {
        //get all the values from the form
        	$prof_id1=(int)$_SESSION["edit"];
        	$prof_csid=$_POST['csid'];
        	$prof_fname=$_POST['fname'];
        	$prof_lname=$_POST['lname'];
        	$prof_phone=$_POST['phone'];
        	$prof_email=$_POST['email'];
        	$prof_faculty=$_POST['faculty'];
        	$prof_department=$_POST['department'];
        	$prof_aemail=$_POST['aemail'];




			//make an sql statment
			$sql_prof= "UPDATE professor SET csid='$prof_csid', fname='$prof_fname', lname='$prof_lname', phone='$prof_phone', email='$prof_email', faculty='$prof_faculty', department='$prof_department', alt_email= '$prof_aemail' WHERE professor_id= '$prof_id1'";


			$result = $conn->query($sql_prof);

			?>
			<script type="text/javascript">
				window.location.href = 'ManageUsers.php';
			</script>
			<?php



    }




    CloseCon( $conn );

 }




?>





      </body>
</html>
