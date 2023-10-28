function doValidate() {
         console.log('Validating...');
         try {
             pw = document.getElementById('pass').value;
             em = document.getElementById('email').value;
             console.log("Validating pw="+pw);
             console.log("Validating em="+em);
             if (pw == null || pw == "" || em == null || em == "") {
                 alert("Both fields must be filled out");
                 return false;
             }
             return true;
         } catch(e) {
             return false;
         }
         return false;
     }