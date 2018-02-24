$(document).ready(function () {
    "use strict";
    var date_input = $('input[id="date"]'); //our date input has the name "date"
    //var container = $('.form-group.date').length > 0 ? $('.form-group.date').parent() : "div";
    var options = {
        format: 'mm/dd/yyyy',
        todayHighlight: true,
        autoclose: true
    };
    date_input.datepicker(options);
});