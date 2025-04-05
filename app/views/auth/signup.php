<?php
//         $targetFile = $targetDir . uniqid() . '.' . $fileExt;    error_reporting(E_ALL);
ini_set('display_errors', '1');
ob_start();?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/signup.js"></script>
</head>
<body>
    <form id="signupForm" action="index.php?route=signup" method="POST" enctype="multipart/form-data">
        <h2>Signup</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>
        
        <div class="form-row">
            <div class="column">
                <div>
                    <label>Full Name:</label>
                    <input type="text" style="width: 400px;" name="fullname" required>
                </div>
                <div>
                    <label>Date Of Birth:</label>
                    <input type="date" style="width: 400px;" name="dob" required>
                </div>
            </div>
            <div id="profilePicContainer">
                <label for="uploadButton">
                    <img src="assets/images/default-profile.png" alt="Profile Picture" id="profilePreview">
                    <button id="upload-btn">Upload Profile pic</button>
                </label>
                <input type="file" id="uploadButton" name="profile_pic" accept="image/*">
            </div>
        </div>
        
        <label>Email:</label>
        <input type="email" style="width: 570px;" name="email" id="email" required>
        
        <div class="password-container">
            <div class="input-group">
                <label>Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <label>Retype Password:</label>
                <input type="password" id="confirmPassword" name="confirm_password" required>
            </div>
        </div>
        
        <p id="passwordMessage" class="error-message"></p>
        <p id="passwordPolicy" class="info-message">Allowed: a-z, A-Z, 0-9, $%@&*</p>
        
        <label>Qualifications:</label>
        <div id="qualifications">
            <input type="text" name="qualifications[]" placeholder="Enter Qualification" required>
        </div>
        <button type="button" id="addQualification">Add More</button>
        
        <label>Experiences:</label>
        <div id="experiences">
            <input type="text" name="experiences[]" placeholder="Enter Experience" required>
        </div>
        <button type="button" style="style:none" id="addExperience">Add More</button>
        
        <label>Permanent Address:</label>
        <input type="text" name="perm_address1" placeholder="Address Line 1" required>
        <input type="text" name="perm_address2" placeholder="Address Line 2">
        <input type="text" name="perm_city" placeholder="City" required>
        <select name="perm_state" required>
            <option value="">Select State</option>
            <option value="UTTAR PRADESH">UTTAR PRADESH</option>
            <option value="DELHI">DELHI</option>
            <option value="UK">UTTRAKHAND</option>
        </select>       
        
        <label>Current Address:</label>
        <input type="text" name="curr_address1" placeholder="Address Line 1" required>
        <input type="text" name="curr_address2" placeholder="Address Line 2">
        <input type="text" name="curr_city" placeholder="City" required>
        <select name="curr_state" required>
            <option value="">Select State</option>
            <option value="UTTAR PRADESH">UTTAR PRADESH</option>
            <option value="DELHI">DELHI</option>
            <option value="UK">UTTRAKHAND</option>
        </select>
        
        <p>Already have an account? <a href="index.php?route=login">Login here</a></p>
        <button type="submit">Sign Up</button>
    </form>
</body>
</html>