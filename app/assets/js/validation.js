$(document).ready(function() {
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function isValidPassword(password) {
        
        const passwordRegex = /^[a-zA-Z0-9$%@&*]{8,20}$/;
        return passwordRegex.test(password);
    }

    function showError(selector, message) {
        $(selector).text(message).show();
    }

    function clearError(selector) {
        $(selector).text('').hide();
    }

    
    const $loginForm = $('#loginForm');
    if ($loginForm.length) {
        $loginForm.on('submit', function(e) {
            let isValid = true;

            const email = $('#email').val();
            if (!email) {
                showError('#emailError', 'Email is required');
                isValid = false;
            } else if (!isValidEmail(email)) {
                showError('#emailError', 'Please enter a valid email address');
                isValid = false;
            } else {
                clearError('#emailError');
            }

           
            const password = $('#password').val();
            if (!password) {
                showError('#passwordError', 'Password is required');
                isValid = false;
            } else if (!isValidPassword(password)) {
                showError('#passwordError', 'Password must be 8-20 characters and contain only letters, numbers, or $%@&*');
                isValid = false;
            } else {
                clearError('#passwordError');
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    const $signupForm = $('#signupForm');
    if ($signupForm.length) {
        $signupForm.on('submit', function(e) {
            let isValid = true;

            const fullname = $('input[name="fullname"]').val().trim();
            if (!fullname) {
                showError('#message', 'Full Name is required');
                isValid = false;
            } else if (fullname.length > 100) {
                showError('#message', 'Full Name must be less than 100 characters');
                isValid = false;
            } else {
                clearError('#message');
            }

           
            const dob = $('input[name="dob"]').val();
            if (!dob) {
                showError('#message', 'Date of Birth is required');
                isValid = false;
            } else {
                const today = new Date();
                const birthDate = new Date(dob);
                if (birthDate >= today) {
                    showError('#message', 'Date of Birth must be in the past');
                    isValid = false;
                }
            }
            const email = $('#email').val();
            if (!email) {
                showError('#message', 'Email is required');
                isValid = false;
            } else if (!isValidEmail(email)) {
                showError('#message', 'Please enter a valid email address');
                isValid = false;
            }
            const password = $('#password').val();
            const confirmPassword = $('#confirmPassword').val();
            if (!password) {
                showError('#passwordMessage', 'Password is required');
                isValid = false;
            } else if (!isValidPassword(password)) {
                showError('#passwordMessage', 'Password must be 8-20 characters and contain only letters, numbers, or $%@&*');
                isValid = false;
            } else if (password !== confirmPassword) {
                showError('#passwordMessage', 'Passwords do not match');
                isValid = false;
            } else {
                clearError('#passwordMessage');
            }
            const profilePic = $('#uploadButton')[0].files[0];
            if (profilePic) {
                const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!validImageTypes.includes(profilePic.type)) {
                    showError('#message', 'Profile picture must be JPEG, PNG, or GIF');
                    isValid = false;
                }
                if (profilePic.size > 5 * 1024 * 1024) { // 5MB limit
                    showError('#message', 'Profile picture must be less than 5MB');
                    isValid = false;
                }
            }

            const $qualifications = $('input[name="qualifications[]"]');
            $qualifications.each(function() {
                if (!$(this).val().trim()) {
                    showError('#message', 'All qualifications must be filled');
                    isValid = false;
                }
            });

          
            const $experiences = $('input[name="experiences[]"]');
            $experiences.each(function() {
                if (!$(this).val().trim()) {
                    showError('#message', 'All experiences must be filled');
                    isValid = false;
                }
            });

           
            const permAddress1 = $('input[name="perm_address1"]').val().trim();
            const permCity = $('input[name="perm_city"]').val().trim();
            const permState = $('select[name="perm_state"]').val();
            if (!permAddress1 || !permCity || !permState) {
                showError('#message', 'Permanent Address: All required fields must be filled');
                isValid = false;
            }
  
            const currAddress1 = $('input[name="curr_address1"]').val().trim();
            const currCity = $('input[name="curr_city"]').val().trim();
            const currState = $('select[name="curr_state"]').val();
            if (!currAddress1 || !currCity || !currState) {
                showError('#message', 'Current Address: All required fields must be filled');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });

      
        $('#password, #confirmPassword').on('input', function() {
            const password = $('#password').val();
            const confirmPassword = $('#confirmPassword').val();
            
            if (password && !isValidPassword(password)) {
                showError('#passwordMessage', 'Password must be 8-20 characters and contain only letters, numbers, or $%@&*');
            } else if (password && confirmPassword && password !== confirmPassword) {
                showError('#passwordMessage', 'Passwords do not match');
            } else {
                clearError('#passwordMessage');
            }
        });

        
        $('#email').on('input', function() {
            const email = $(this).val();
            if (email && !isValidEmail(email)) {
                showError('#message', 'Please enter a valid email address');
            } else {
                clearError('#message');
            }
        });
    }
});