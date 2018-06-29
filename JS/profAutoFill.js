<script>
//Used in form A when the admin is submitting a case
//script auto fills the prof email and phone number
function fillProf(){
    var selectBox = document.getElementById("profSelect");
    var selectedValue = selectBox.options[selectBox.selectedIndex].value;
    var res = selectedValue.split(",");
    document.getElementById('email').value = res[0];
    var phoneNumber = res[1].split("");
    //gross way to format numbers.
    //will break if phone number format changes
    var formattedNumber= 
    phoneNumber[0] + phoneNumber[1] + phoneNumber[2] + "-" +
    phoneNumber[3] + phoneNumber[4] + phoneNumber[5] + "-" +
    phoneNumber[6] + phoneNumber[7] + phoneNumber[8];
    document.getElementById('phoneNum').value = formattedNumber;
};
</script>