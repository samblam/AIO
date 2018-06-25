<script>
//Used in form A when the admin is submitting a case
//script auto fills the prof email and phone number
function fillProf(){
    var selectBox = document.getElementById("profSelect");
    var selectedValue = selectBox.options[selectBox.selectedIndex].value;
    var res = selectedValue.split(",");
    document.getElementById('email').value = res[0];
    document.getElementById('phoneNum').value = res[1];
};
</script>