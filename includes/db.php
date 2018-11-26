<?php
  /**
   * This file opens the connection to the database.
   * The first if statement allows you to connect to localhost database if working on a localhost.
   * This assumes that you local db is called aio, your local db is on localhost, and your username and password are root
   *
   * The else connects you to the live database for when you are using this live.
   * This only needs to be changed if you decide to change the database password.
   */
  function OpenCon() {
    $dbhost = "";
    $dbuser = "";
    $dbpass = "";
    if($_SERVER['SERVER_NAME'] == "localhost"){
      $dbhost = "localhost";
      $dbuser = "root";
      $dbpass = "root";
    }
    else{
      $dbhost = "db.cs.dal.ca";
      $dbuser = "aio";
      $dbpass = "ge7ochooCae7";
    }
    $db = "aio";
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed: %s\n". $conn -> error);
    return $conn;
  }

  function CloseCon($conn) {
    $conn -> close();
  }

  /**
   * Checks whether the provided csid can be found in the DB
   */
  function db_checkCSID($csid) {
    $conn = OpenCon();
    
    //$stmt->bind_param( "ss", $table_name, $csid );
    $tables = array( 'admin', 'aio', 'professor', 'student' );
    foreach( $tables as $table_name ) {

      $st = "SELECT * FROM `$table_name` WHERE csid='$csid'";

      $result = $conn->query( $st );
      if( !$result ){
        echo "Database Error. Please contact admin.\nError details: " . $conn->error;
        $result->close();
        break;
      }
      elseif( $result->num_rows > 0 ) {
        $result->close();
        return true;

      }
    }
    return false;
  }

  /**
   * Get's session data from DB
   *
   * Returns an array with elements:
   *     csid          -> the CSID of the user
   *     roles         -> list of roles the user has
   *     default_role  -> the highest ranked of the user's roles
   *                      ( admin -> aio -> professor -> student )
   *     role          -> the user's active role, starts same as default_role
   *     fname         -> the user's first name
   *     lname         -> the user's last name
   *     phone         -> the user's phone number
   *     email         -> the user's email address
   */
  function db_getUserData($csid) {
    $conn = OpenCon();
    $data = array( 'csid' => $csid );

    // Fetch user roles
    $roles = array();
    $tables = array( 'admin', 'aio', 'professor', 'student' );
    foreach ($tables as $table_name ) {
      $st = "SELECT * FROM `$table_name` WHERE csid='$csid'";

      $result = $conn->query( $st );
      if( !$result ){
        echo "Database Error. Please contact admin.\nError details: " . $conn->error;
        $result->close();
        exit;
      }
      elseif( $result->num_rows > 0 ) {
         $roles[] = $table_name;
        $result->close();
      }
    }
    $data['roles'] = $roles;

    // Determine default user role
    if( in_array( 'admin', $roles ) ) {
      $data['default_role'] = 'admin';
    }
    elseif( in_array( 'aio', $roles ) ) {
      $data['default_role'] = 'aio';
    }
    elseif( in_array( 'professor', $roles) ) {
      $data['default_role'] = 'professor';
    }
    else {
      $data['default_role'] = 'student';
    }
    $data['role'] = $data['default_role'];

    $def = $data['role'];
    // Fetch user data from table corresponding to default role
    $st = "SELECT fname, lname, phone, email FROM `$def` WHERE csid='$csid'";
    $result = $conn->query( $st );
    $arr = $result->fetch_assoc();
    $data['fname'] = $arr['fname'];
    $data['lname'] = $arr['lname'];
    $data['phone'] = $arr['phone'];
    $data['email'] = $arr['email'];

    $result->close();
    CloseCon( $conn );
    return $data;
  }
// Extracted SQL from various project files below here

// ActiveCases.php
  function getActiveCases($conn)
  {

      $result = $conn->query("
                                  SELECT 
                                    active_cases.case_id, 
                                    active_cases.class_name_code, 
                                    professor.fname, 
                                    professor.lname, 
                                    aio.fname, 
                                    aio.lname  
                                  FROM active_cases 
                                  LEFT JOIN professor 
                                  ON professor.professor_id = active_cases.prof_id 
                                  LEFT JOIN aio 
                                  ON aio.aio_id = active_cases.aio_id
                                  ORDER BY active_cases.case_id ");

      return $result;

  }
 // add_user.php
  function insertUserAIO($a,$b,$c,$d,$e){
    $conn= OpenCon();
    $sql_aio="INSERT INTO aio(csid, fname, lname, phone, email) VALUES ('$a', '$b','$c','$d' ,'$e')";

    $conn->query($sql_aio);
    CloseCon( $conn );

  }
  function insertUserPROF($a,$b,$c,$d,$e,$f,$g,$h){
$conn= OpenCon();
    $sql_prof="INSERT INTO professor(csid, fname, lname, phone, email, faculty, department, alt_email) VALUES ('$a', '$b','$c','$d' ,'$e','$f','$g','$h')";

    $conn->query($sql_prof);
    CloseCon( $conn );

  }
// changeAIO.php
  function getCurrentAIO($a,$conn){

    $statement = $conn->prepare("SELECT active_cases.aio_id, aio.fname, aio.lname FROM active_cases LEFT JOIN aio ON aio.aio_id = active_cases.aio_id WHERE active_cases.case_id = '$a' ");
    return $statement;
}
function selectNameAIO($conn)
{

    $statement = $conn->prepare("SELECT fname, lname FROM aio");
    return $statement;

}
//edituser.php
function getAIOidROW($a,$conn){

    $sql = "SELECT * FROM aio WHERE aio_id = '$a'";

    $result = $conn->query($sql);

    return $result;
}

function getPROFidROW($a,$conn)
{

    $sql = "SELECT * FROM professor WHERE professor_id = '$a'";

    $result = $conn->query($sql);


    return $result;

}

function updateAIO($a,$b,$c,$d,$e,$f){
  $conn = OpenCon();
  $sql_aio = "UPDATE aio SET csid='$a', fname='$b', lname='$c', phone='$d', email='$e' WHERE aio_id= '$f'";


  $conn->query($sql_aio);
  CloseCon($conn);
}
function updatePROF($a,$b,$c,$d,$e,$f,$g,$h,$i){
    $conn= OpenCon();
    $sql_prof= "UPDATE professor SET csid='$a', fname='$b', lname='$c', phone='$d', email='$e', faculty='$f', department='$g', alt_email= '$h' WHERE professor_id= '$i'";


  $conn->query($sql_prof);
  CloseCon($conn);
}
// ManageUsers.php
function selectAIO($conn){
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
   return $result;
}

function selectPROF($conn){

    $result = $conn->query("
                                  SELECT *
                                  FROM professor 
                                  ORDER BY professor.lname ");
   return $result;
}

function deleteAIO($a){
    $conn= OpenCon();
    $sql = "DELETE FROM aio WHERE aio_id = '$a'";

    $conn->query($sql);

    CloseCon($conn);
}
function deletePROF($a){
    $conn = OpenCon();
    $sql = "DELETE FROM professor WHERE professor_id = '$a'";

    $conn->query($sql);
    CloseCon($conn);
}
//CaseInformation.php
function getCaseInfo($a,$conn)
{
    $statement = $conn->prepare("SELECT evidence_fileDir, aio_id, prof_id FROM active_cases WHERE case_id ='$a' ");
    return $statement;

}
function getMoreCaseInfo($a,$conn){

    $statement = $conn->prepare("
                    SELECT
                        active_cases.form_a_submit_date,
                        active_cases.stu_csid_list,
                        professor.fname, 
                        professor.lname, 
                        student.fname, 
                        student.lname, 
                        student.csid,
                        student.student_id
                    FROM 
                        professor 
                        LEFT JOIN active_cases ON professor.professor_id = active_cases.prof_id 
                        LEFT JOIN student ON student.case_id = '$a'
                    WHERE 
                        active_cases.case_id = '$a'
                        ");
    return $statement;

}
function selectCaseID($a,$conn){
    $statement = $conn->prepare("SELECT aio_id FROM active_cases WHERE case_id = '$a'");
    return $statement;
}
function getCaseVerdict($a,$b,$conn){
$statement = $conn->prepare("SELECT case_verdict FROM active_cases WHERE case_id = '$a' AND aio_id = '$b'");
return $statement;
}
function getAIOId($a,$conn){
    $res = $conn->query( "SELECT aio_id FROM `aio` WHERE csid='$a'" );
    return $res;
}
//processFormC.php
function setMeeting($conn){
    $setMeeting = $conn->prepare("
		UPDATE student
		SET
			c_meetingDate = ?,
			c_meetingLocation = ?,
			c_meetingTime = ?
		WHERE student_id = ?
	");
    return $setMeeting;
}
//AIO/ActiveCases.php
Function getAssignedCases($conn){

    $statement = $conn->prepare("
                          SELECT 
                            professor.fname, 
                            professor.lname, 
                            student.fname, 
                            student.lname, 
                            student.csid, 
                            active_cases.case_id 
                          FROM 
                            professor 
                            LEFT JOIN active_cases ON professor.professor_id = active_cases.prof_id 
                            LEFT JOIN student ON student.case_id = active_cases.case_id 
                          WHERE 
                            active_cases.aio_id = ?
                          ORDER BY
                            active_cases.case_id
                          ");
    return $statement;
}

function getAssignedAIOId($a,$conn){
    $result = $conn->query( "SELECT aio_id FROM `aio` WHERE csid='$a'" );
    return $result;
}
function getUnassignedCases($conn){
    $query = $conn->prepare(
        "            SELECT 
                        active_cases.case_id, 
                        active_cases.class_name_code, 
                        professor.fname, 
                        professor.lname 
                      FROM 
                        professor 
                        RIGHT JOIN active_cases ON professor.professor_id = active_cases.prof_id 
                      WHERE 
                        active_cases.aio_id IS NULL
                      ORDER BY
                        active_cases.case_id
                      ");
    return $query;
}
//common/forma.php
function getActiveCase($conn){
    $statement = $conn->prepare("SELECT evidence_fileDir, form_a_submit_date FROM active_cases WHERE case_id = ?");
    return $statement;
}
function getPROF($conn){
    $statement = $conn->prepare("SELECT professor_id, fname, lname, email, phone FROM professor");
    return $statement;
}
//common/formc.php
function getStudent($a,$conn){
    $getStudentList = $conn->prepare("
									SELECT
										student_id,
										fname,
										lname
									FROM
										student
									WHERE
										case_id = $a
									");
    return $getStudentList;
}
function getStudentInfo($a,$conn){
    $studentInfo = $conn->prepare("
									SELECT
										S.fname,
										S.lname,
										S.email,
										S.csid
									FROM
										student as S
									WHERE
										student_id = $a
									");
    return $studentInfo;
}

function getAdditionalCaseInfo($a,$conn){
    $caseInfo = $conn->prepare("
									SELECT
										A.evidence_fileDir,
										P.fname,
										P.lname,
										P.email,
										A.aio_id
									FROM
										active_cases as A
									INNER JOIN
										professor as P ON A.prof_id = P.professor_id
									WHERE
										case_id = $a

									");
    return $caseInfo;
}
function getAdditionalAIOInfo($a,$conn)
{
    $AIOInfo = $conn->prepare("
									SELECT
										phone,
										email
									FROM
										aio
									WHERE
										aio_id = $a
									");
    return $AIOInfo;
}
//common/student-case-information.php
function getStudentCaseInfo($a,$conn){
    $statement = $conn->prepare("SELECT evidence_fileDir, aio_id, prof_id FROM active_cases WHERE case_id = '$a'");
    return $statement;
}
//includes/AcceptDeny.php
function setAIOid($a,$conn){
    $result = $conn->query( "SELECT aio_id FROM `aio` WHERE csid='$a'" );
    return $result;
}
function setCurrentAIOId($conn){
    $CaseInfo = $conn->prepare("UPDATE active_cases SET aio_id = ?  WHERE case_id = ?");
    return $CaseInfo;
}
function setOtherCurrentAIOid($conn){
    $CaseInfo = $conn->prepare("UPDATE active_cases SET aio_id = NULL WHERE case_id = ?");
    return $CaseInfo;
}
//includes/formFill.php
function getPROFbyId($conn)
{
    $query = $conn->prepare("SELECT fname, lname, phone, email, department FROM professor WHERE professor_id = ?");
    return $query;
}
function getActiveCaseInfoById($conn)
{
    $query = $conn->prepare("SELECT prof_id, class_name_code, date_aware FROM active_cases WHERE case_id = ?");
    return $query;
}
function getPROFInfoById($conn)
{
    $query = $conn->prepare("SELECT fname, lname, phone, email, department FROM professor WHERE professor_id = ?");
    return $query;
}
//includes/formProcess.php
function findAIObySelection($a,$conn){
    $statement = $conn->prepare("SELECT aio_id FROM aio WHERE CONCAT(TRIM(fname), ' ', TRIM(lname)) LIKE '$a'");
    return $statement;
}

function setAIOByCaseId($a,$b,$conn){

    $conn->query("UPDATE active_cases SET aio_id = '$a' WHERE case_id = '$b'");

}
function getStudentInfoByCaseId($a,$conn){
    $statement = $conn->prepare("SELECT fname, lname, csid FROM student WHERE case_id = '$a'");
    return $statement;
}
function getPROFInfoByCaseId($a,$conn)
{
    $statement = $conn->prepare("SELECT professor.email FROM active_cases LEFT JOIN professor ON active_cases.prof_id = professor.professor_id WHERE active_cases.case_id = '$a'");
    return $statement;
}
function deleteStudentInfoByCaseID($a,$conn){
    $conn->query("DELETE FROM student WHERE case_id = '$a'");
    $conn->query("DELETE FROM active_cases WHERE case_id = '$a'");

}

function insertThenDeleteStudentInfoByCaseID($a,$conn){
    $conn->query("INSERT INTO history (class_name, verdict, date_allegation) SELECT class_name_code, case_verdict, date_aware FROM active_cases WHERE case_id = '$a'");
    $conn->query("DELETE FROM student WHERE case_id = '$a'");
    $conn->query("DELETE FROM active_cases WHERE case_id = '$a'");
}
//includes/processFormA.php
function setActiveCasesById($conn){
    $statement = $conn->prepare("UPDATE active_cases SET prof_id = ?, class_name_code = ?, date_aware = ?, description = ?, form_a_submit_date = ? WHERE case_id = ?");
    return $statement;
}
function insertNewCase($conn){
    $statement = $conn->prepare("INSERT INTO active_cases (prof_id, class_name_code, date_aware, description, form_a_submit_date) VALUES (?, ?, ?, ?, ?)");
    return $statement;
}
function setActiveCaseById($a,$conn){
    $updateEvidence = $conn->prepare("UPDATE active_cases SET evidence_fileDir = ? WHERE case_id = '$a'");
    return $updateEvidence;
}
function insertNewStudent($conn){
    $statement = $conn->prepare("INSERT INTO student (csid, case_id, fname) VALUES (?, ?, ?)");
    return $statement;
}
function insertNewCaseSave($conn){
    $statement = $conn->prepare("INSERT INTO active_cases (prof_id, class_name_code, date_aware, description) VALUES (?, ?, ?, ?)");
    return $statement;
}
function setEvidenceByCaseId($a,$conn){
    $updateEvidence = $conn->prepare("UPDATE active_cases SET evidence_fileDir = ? WHERE case_id ='$a' ");
    return $updateEvidence;
}
function insertNewSaveInfo($a,$b,$c,$d,$e,$f,$g,$h,$i,$j,$conn){
    $statement = $conn->prepare("INSERT INTO saved_info (professor, email, course, faculty, student_name, student_bannerid, date, comments, case_id, phone) VALUES ('$a','$b','$c','$d','$e[0]','$f[0]','$g','$h','$i','$j')");
    return $statement;
}
function setNewActiveCaseById($a,$conn){
    $statement = $conn->prepare("UPDATE active_cases SET prof_id = ?, class_name_code = ?, date_aware = ?, description = ? WHERE case_id = '$a'");
    return $statement;
}

function findStudentByCaseId($a,$conn){
    $statement = $conn->prepare("SELECT fname, csid FROM student WHERE case_id = '$a'");
    return $statement;
}

function setNewSaveInfo($a,$b,$c,$d,$e,$f,$g,$h,$i,$j,$conn){
    $statement = $conn->prepare("UPDATE saved_info SET professor='$a', email='$b', course='$c', faculty='$d', student_name='$e[0]', student_bannerid='$f[0]', date='$g', comments='$h', phone='$i' WHERE case_id='$j'");
    return $statement;
}

function setStudentByCaseID($a,$b,$conn){
    $statement = $conn->prepare("UPDATE student SET fname = ?, WHERE case_id = '$a' AND csid = '$b[0]'");
    return $statement;
}
function deleteStudentByCaseId($a,$b,$conn){
    $statement = $conn->prepare("DELETE FROM student WHERE case_id = '$a' AND csid = '$b[0]'");
    return $statement;
}
function findPROFById($a,$conn){
    $res = $conn->query( "SELECT professor_id FROM `professor` WHERE csid='$a'" );
    return $res;
}
//Instructor/ActiveCases.php
function getAllActiveCasesByProfId($a,$conn){
    $statement = $conn->prepare("
					SELECT
						aio.fname,
						aio.lname,
						student.fname,
						student.lname,
						student.csid,
						active_cases.case_id,
						active_cases.form_a_submit_date
					FROM active_cases
						LEFT JOIN student ON student.case_id = active_cases.case_id
						LEFT JOIN aio ON aio.aio_id = active_cases.aio_id
					WHERE active_cases.prof_id = '$a'
					");
    return $statement;
}
?>