//Function for displaying section a.
function sectionA(){
    document.getElementById("section-a-content").classList.toggle("show");
}
            
//Function for displaying section b.
function sectionB(){
    document.getElementById("section-b-content").classList.toggle("show");
}

$(document).ready(function () {
    "use strict";
    var date_input1 = $('input[id="signDate"]');
    var date_input2 = $('input[id="allegationDate"]');
    var date_input3 = $('input[id="allegationReceived"]');
    var date_input4 = $('input[id="allegationMeeting"]');
    var date_input5 = $('input[id="allegationStudent"]');
    var options = {
        format: 'mm/dd/yyyy',
        todayHighlight: true,
        autoclose: true
    };
    
    var datepicker = date_input1.datepicker(options)
    datepicker.on('show', function(e) {
        var rect = e.currentTarget.getBoundingClientRect();
        $(this).data('datepicker').picker.css('left', rect.left);
    });
    
    var datepicker = date_input2.datepicker(options)
    datepicker.on('show', function(e) {
        var rect = e.currentTarget.getBoundingClientRect();
        $(this).data('datepicker').picker.css('left', rect.left);
    });
    
    var datepicker = date_input3.datepicker(options)
    datepicker.on('show', function(e) {
        var rect = e.currentTarget.getBoundingClientRect();
        $(this).data('datepicker').picker.css('left', rect.left);
    });
    
    var datepicker = date_input4.datepicker(options)
    datepicker.on('show', function(e) {
        var rect = e.currentTarget.getBoundingClientRect();
        $(this).data('datepicker').picker.css('left', rect.left);
    });
    
    var datepicker = date_input5.datepicker(options)
    datepicker.on('show', function(e) {
        var rect = e.currentTarget.getBoundingClientRect();
        $(this).data('datepicker').picker.css('left', rect.left);
    });
});