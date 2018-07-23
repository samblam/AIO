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

    $('input.timepicker').timepicker({
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
});


function loadFormC(caseID, studentID, num_students) {
    $("#formc").load("formc.php?case_id=" + caseID + "&student_id=" + studentID + "&num_students=" + num_students);
};