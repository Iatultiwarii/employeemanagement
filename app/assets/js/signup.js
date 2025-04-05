
$(document).ready(function() {
    $("#addQualification").click(function() {
        $("#qualifications").append('<input type="text" name="qualifications[]" placeholder="Enter Qualification">');
    });
    
    $("#addExperience").click(function() {
        $("#experiences").append('<input type="text" name="experiences[]" placeholder="Enter Experience">');
    });
    
    $("#uploadButton").change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $("#profilePreview").attr("src", e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });
    
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirmPassword");
    const message = document.getElementById("passwordMessage");
    const passwordPattern = /^[a-zA-Z0-9$%@&*]+$/;
    
    function validatePasswords() {
        const passValue = password.value;
         const confirmValue = confirmPassword.value;
        
        if (!passwordPattern.test(passValue)) {
            message.textContent = "Invalid characters in password. Allowed: a-z, A-Z, 0-9, $%@&*";
            message.style.display = "block";
            password.style.borderColor = "red";
            return;
        }
        
        password.style.borderColor = "#ccc";
        
        if (passValue !== confirmValue && confirmValue.length > 0) {
            message.textContent = "Passwords do not match!";
            message.style.display = "block";
            confirmPassword.style.borderColor = "red";
        } else {
            message.style.display = "none";
            confirmPassword.style.borderColor = "#ccc";
        }
    }
    
    password.addEventListener("input", validatePasswords);
    confirmPassword.addEventListener("input", validatePasswords);
});
