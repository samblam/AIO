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
        <div>
            <table class="table table-bordered" style="font-size: 12px;">
                <thead class="cases-table">
                    <tr>
                        <th>Student(s) Banner</th>
                        <th>Student(s) Name</th>
                        <th>AIO</th>
                        <th>Action required</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    
                    
		      $statement = $conn->prepare("SELECT  FROM active_cases WHERE aio_id = ?");
                      //$statement = $conn->query("SELECT * FROM case WHERE aio_id = 1233");
		      //if (is_object($statement))
		      $statement->bind_param("s",$_SESSION['userId']); //bind the csid to the prepared statements
  		      //$num = "1";
		      if(!$statement->execute()){
     		        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
                      } 
		      $statement->bind_result($name, $code);
		      
		      $queryAllPosts = $conn->query("SELECT  FROM active_cases WHERE aio_id = '$_SESSION['userId']'");
  }

  $row = $queryAllPosts->fetch_array(MYSQLI_NUM);
  $isFirst = TRUE;
  while($statement->fetch()){
    $tableData = <<<ViewAllPost
    <tr>
      <td>B000</td>
      <td>Moe</td>
      <td>Fred</td>
      <td><button class="custombtn btn btn-danger">Yes</button></td>
      <td><button class="btn btn-primary">View Case</button></td>
    </tr>		
ViewAllPost;
?>
		    <tr>
                        <td>B00000002</td>
                        <td>Moe</td>
                        <td>Fred</td>
                        <td><button class="custombtn btn btn-danger">Yes</button></td>
                        <td><button class="btn btn-primary">View Case</button></td>
                    </tr>
                    <tr>
                        <td>B00000003</td>
                        <td>Dooley</td>
                        <td>Matt</td>
                        <td><button class="custombtn btn btn-danger">Yes</button></td>
                        <td><button class="btn btn-primary">View Case</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
