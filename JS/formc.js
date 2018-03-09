$(document).ready(function () {
    "use strict";
    var date_input = $('input[id="date"]');
    var options = {
        format: 'mm/dd/yyyy',
        todayHighlight: true,
        autoclose: true
    };
    
    var datepicker = date_input.datepicker(options)
    datepicker.on('show', function(e) {
        var rect = e.currentTarget.getBoundingClientRect();
        $(this).data('datepicker').picker.css('left', rect.left);
    });
    
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