<script>
  //Used in form A when the admin is submitting a case
  //script auto fills the prof email and phone number
  function fillProf(){
    var selectBox = document.getElementById("profSelect");
    var selectedValue = selectBox.options[selectBox.selectedIndex].value;

    var res = selectedValue.split(",");
    document.getElementById('ProfessorName').value = res[0];
    document.getElementById('email').value = res[1];

    var phoneNumber = res[2].split("");
    //gross way to format numbers.
    //will break if phone number format changes
    var formattedNumber= 
    "(" + phoneNumber[0] + phoneNumber[1] + phoneNumber[2] + ") " +
    phoneNumber[3] + phoneNumber[4] + phoneNumber[5] + "-" +
    phoneNumber[6] + phoneNumber[7] + phoneNumber[8] + phoneNumber[9];
    document.getElementById('phoneNum').value = formattedNumber;

    // set the professor ID so that the selected professor will be able to view the evidence 
    // files even if the admin has submitted the case for them
    document.getElementById("AdminSubmittedProfId").value = res[3];
  };
</script>