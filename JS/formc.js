jQuery(function () {
    "use strict";
    var date_input = $('input[id="date"]'); //our date input has the name "date"
    var options = {
        format: 'mm/dd/yyyy',
        todayHighlight: true,
        autoclose: true
    };
    date_input.datepicker(options);
});

function loadFormC(caseID, studentID, num_students) {
    $("#formc").load(
		("formc.php?case_id=" + caseID + "&student_id=" + studentID + "&num_students=" + num_students),
		{"internal": "true"}
	);
};