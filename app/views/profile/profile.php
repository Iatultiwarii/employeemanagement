<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?route=login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="assets/css/profile.css">
  
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/profile.js"></script>
</head>
<body>
    <div id="profile">
        <h2>Employee Profile</h2>
        <img id="profilePic" src="<?php echo !empty($user['profile_pic']) ? $user['profile_pic'] : '/assets/images/default-profile.png'; ?>" alt="Profile Picture" width="120" style="cursor: pointer;">
        <input type="file" id="profilePicUpload" accept="image/*" style="display: none;">
        <form id="profileForm">
            <div class="profile-container">
                <div class="editable-container">
                    <div class="profile-container">
                        <input type="text" name="fullname" id="fullnameInput" value="<?php echo htmlspecialchars($user['fullname']); ?>" class="editable-name auto-save">
                        <h class="non-editable"><?php echo htmlspecialchars($user['email']); ?></h>
                    </div>
                    <div class="form-group">
                        <label>DOB</label>
                        <span class="dob-display" onclick="editDOB(this)">
                            <?php echo date('d M Y', strtotime($user['dob'])); ?>
                        </span>
                        <input type="date" name="dob" class="editable-input auto-save" value="<?php echo $user['dob']; ?>" style="display: none;" onkeydown="handleKeyPress(event, this)">
                    </div>
                </div>
            </div>
            <div class="column-container">
                <div class="column">
                    <label>Qualifications:</label>
                    <div id="qualifications">
                        <?php foreach ($qualifications as $q) { ?>
                            <div class="input-group">
                                <input type="text" name="qualifications[]" value="<?php echo htmlspecialchars($q); ?>" class="auto-save">
                            </div>
                        <?php } ?>
                        <button type="button" id="addQualification" class="add-btn">Add Qualification</button>
                    </div>
                </div>
                <div class="column">
                    <label>Experiences:</label>
                    <div id="experiences">
                        <?php foreach ($experiences as $e) { ?>
                            <div class="input-group">
                                <input type="text" name="experiences[]" value="<?php echo htmlspecialchars($e); ?>" class="auto-save">
                            </div>
                        <?php } ?>
                        <button type="button" id="addExperience" class="add-btn">Add Experience</button>
                    </div>
                </div>
            </div>
            <div class="column-container">
                <div class="column address">
                    <label>Permanent Address:</label>
                    <div class="input-group">
                        <input type="text" name="perm_address1" value="<?php echo htmlspecialchars($user['perm_address1']); ?>" class="auto-save" placeholder="Address Line 1">
                        <input type="text" name="perm_address2" value="<?php echo htmlspecialchars($user['perm_address2']); ?>" class="auto-save" placeholder="Address Line 2">
                        <input type="text" name="perm_city" value="<?php echo htmlspecialchars($user['perm_city']); ?>" class="auto-save" placeholder="City">
                        <input type="text" name="perm_state" value="<?php echo htmlspecialchars($user['perm_state']); ?>" class="auto-save" placeholder="State">
                    </div>
                </div>
                <div class="column address">
                    <label>Current Address:</label>
                    <div class="input-group">
                        <input type="text" name="curr_address1" value="<?php echo htmlspecialchars($user['curr_address1']); ?>" class="auto-save" placeholder="Address Line 1">
                        <input type="text" name="curr_address2" value="<?php echo htmlspecialchars($user['curr_address2']); ?>" class="auto-save" placeholder="Address Line 2">
                        <input type="text" name="curr_city" value="<?php echo htmlspecialchars($user['curr_city']); ?>" class="auto-save" placeholder="City">
                        <input type="text" name="curr_state" value="<?php echo htmlspecialchars($user['curr_state']); ?>" class="auto-save" placeholder="State">
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>