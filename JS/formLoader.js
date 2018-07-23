 $(document).ready( function() {
     $("#forma").load("forma.php");
 });

 $(document).ready( function() {
     $("#formb").load("formb.php");
 });

 /**
 $(document).ready( function() {
     console.log("Loading form C");
	 $("#formc").load("formc.php");
	 console.log("Form C loaded");
 });
*/

 $(document).ready( function() {
     $("#formd").load("formd.php");
 });


function loadFormC(caseID, studentID, num_students) {
    $("#formc").load("formc.php?case_id=" + caseID + "&student_id=" + studentID + "&num_students=" + num_students);
};

