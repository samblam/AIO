jQuery(function () {
    "use strict";
	var date_input = $('input[id="date"]'); //our date input has the name "date"
    //var container = $('.form-group.date').length > 0 ? $('.form-group.date').parent() : "div";
    var options = {
        format: 'mm/dd/yyyy',
        todayHighlight: true,
        autoclose: true
    };
    date_input.datepicker(options);

	console.log($('input.timepicker'));
	console.log($('input[id="timepickerC"]'));

    //$('input.timepicker').timepicker({
    $('input[id="timepickerC"]').timepicker({
        timeFormat: 'h:mm p',
        interval: 10,
        minTime: '8',
        maxTime: '6:00pm',
        defaultTime: '10',
        startTime: '8:00',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });

	console.log("This is run");
});

function loadFormC(caseID, studentID, num_students) {
    $("#formc").load(
		("formc.php?case_id=" + caseID + "&student_id=" + studentID + "&num_students=" + num_students),
		{"internal": "true"}
	);
};

/**
function formCValid() {
	console.log("Checking form C");
	//$("#formCFields").validate();
	//$("#formCFields").validate();
	return $("#formCFields").checkValidity();
};
*/

function showModelC() {
	console.log("Showing");
	console.log($("#emailFormC"));
	//$("#emailFormC").fade = false;
};