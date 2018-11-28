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
            <h2>Manage Users</h2>

              <ul class="nav nav-tabs" role="tablist">
    			<li role="presentation" class="active"><a href="#1" aria-controls="1" role="tab" data-toggle="tab">Manage AIOs</a></li>
    			<li role="presentation"><a href="#2" aria-controls="2" role="tab" data-toggle="tab">Manage Professors</a></li>
    		</ul>
        </div>
         
<!--          <div>
            <span class="pull-right" style="display: inline-block;"> 
                <button class="btn btn-success" onclick="location.href='forma.php?ProfRequired=true'" style="font-size: 16px; vertical-align: bottom;">Submit new case</button>
            </span>
         </div> -->
         
    	<div class="tab-content">


        <div role="tabpanel" class="tab-pane active" id="1">
            
        	<a class='btn btn-primary' href="add_user.php?user=AIO">+ Add AIO</a>

            <table class="table table-bordered" style="font-size: 12px;">
                <thead class="cases-table">

                        <th>Name</th>
                        <th>CSID</th>
                        <th>Phone</th>
                        <th>email</th>
                        <th colspan="2">Action</th>

                </thead>
                <tbody>
                  <?php
                  $conn = OpenCon();
                  $result = selectAIO($conn);
/*
                    $result = $conn->query("
                                  SELECT
                                    aio.fname, 
                                    aio.lname, 
                                    aio.csid, 
                                    aio.phone,
                                    aio.email,
                                    aio.aio_id  
                                  FROM aio 
                                  ORDER BY aio.lname ");
*/
                    if( !$result ) {
                      echo "Database Error. Please contact admin.";
                      echo $conn->error;
                    }

                    while( $row = $result->fetch_array() ) {
                      
                      $name = $row[1].", ". $row[0];
                      $csid = $row[2];
                      $phone = $row[3];
                      $email = $row[4];
                      $AIO_id = $row['aio_id'];
                      echo <<<ViewAllPost
                      <tr>
                          <td>$name</td>
                          <td>$csid</td>
                          <td>$phone</td>
                          <td>$email</td>
                          <td><a class='btn btn-warning btn-sm' href='edit_user.php?aio=$AIO_id'>EDIT</a></td>
                          <td><a class='btn btn-danger btn-sm' href='ManageUsers.php?delete_aio=$AIO_id' onclick="return confirm('Are you sure you want to delete this user?')">DELETE</a></td>



                      </tr>
ViewAllPost;
                    }

                    $result->close();
                    CloseCon( $conn );

                  ?>

                </tbody>
            </table>
        </div>



<!-- Professor Table -->
        <div role="tabpanel" class="tab-pane" id="2">
            
        <a class='btn btn-primary' href="add_user.php?user=Professor">+ Add Professor</a>

            <table class="table table-bordered" style="font-size: 12px;">
                <thead class="cases-table">
                    <tr>
                        <th>Name</th>
                        <th>CSID</th>
                        <th>Faculty</th>
                       
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Alternate Email</th>
                        <th colspan="2">Action</th>

                </thead>
                <tbody>
                  <?php
                  $conn = OpenCon();
                $result =  selectPROF($conn);
/*

                    $result = $conn->query("
                                  SELECT *
                                  FROM professor 
                                  ORDER BY professor.lname ");
*/
                    if( !$result ) {
                      echo "Database Error. Please contact admin.";
                      echo $conn->error;
                    }

                    while( $row = $result->fetch_array() ) {
                      $name = $row[3].", ". $row[2];
                      $csid = $row[1];
                      $faculty = $row[6].", ".$row[7];
                      $phone = $row[4];
                      $email = $row[5];
                      $altemail = $row[8];
                      $prof_id = $row['professor_id'];
                      echo <<<ViewAllPost
                      <tr>
                          <td>$name</td>
                          <td>$csid</td>
                          <td>$faculty</td>
                         
                          <td>$phone</td>
                          <td>$email</td>
                          <td>$altemail</td>
                          <td><a class='btn btn-warning btn-sm' href='edit_user.php?prof=$prof_id'>EDIT</a></td>
                          <td><a class='btn btn-danger btn-sm' href='ManageUsers.php?delete_prof=$prof_id' onclick="return confirm('Are you sure you want to delete this user?')">DELETE</a></td>

                      </tr>
ViewAllPost;
                    }

                    $result->close();
                    CloseCon( $conn );

                  ?>

                </tbody>
            </table>
        </div>

        </div>

    </body>
</html>



<?php
// Deleta a user
if (isset($_GET['delete_aio'])) {
	$delete_aio = $_GET['delete_aio'];
	deleteAIO($delete_aio);
/*	$conn= OpenCon();
	$sql = "DELETE FROM aio WHERE aio_id = '$delete_aio'";

	$result_delete_aio = $conn->query($sql);

	CloseCon($conn);
*/
	?>
	<script type="text/javascript">
		window.location.href = 'ManageUsers.php';
	</script>
	<?php
}


if (isset($_GET['delete_prof'])) {
	$delete_prof = $_GET['delete_prof'];
    deletePROF($delete_prof);

/*	$conn = OpenCon();
	$sql = "DELETE FROM professor WHERE professor_id = '$delete_prof'";

	$result_delete_prof = $conn->query($sql);
	CloseCon($conn);
*/
	?>
	<script type="text/javascript">
		window.location.href = 'ManageUsers.php';
	</script>
	<?php
}


?>