<?php

require_once __DIR__ . '/../models/User.php';
class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $userId = $this->userModel->login($email, $password);
            
            if ($userId) {
                $_SESSION['user_id'] = $userId;
                $_SESSION['email'] = $email;
                header("Location: index.php?route=profile");
                exit();
            } else {
                $_SESSION['error'] = "Invalid email or password";
            }
        }
        include __DIR__ . '/../views/auth/login.php';
    }
    
    public function signup() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'fullname' => $_POST['fullname'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'dob' => $_POST['dob'],
                'perm_address1' => $_POST['perm_address1'],
                'perm_address2' => $_POST['perm_address2'] ?? '',
                'perm_city' => $_POST['perm_city'],
                'perm_state' => $_POST['perm_state'],
                'curr_address1' => $_POST['curr_address1'],
                'curr_address2' => $_POST['curr_address2'] ?? '',
                'curr_city' => $_POST['curr_city'],
                'curr_state' => $_POST['curr_state'],
                'qualifications' => $_POST['qualifications'] ?? [],
                'experiences' => $_POST['experiences'] ?? []
            ];
            
            if (!empty($_FILES["profile_pic"]["name"])) {
                $data['profile_pic'] = $this->handleProfilePicUpload();
            }
            
            if ($this->userModel->register($data)) {
                header("Location: index.php?route=login");
                exit();
            } else {
                $_SESSION['error'] = "Email already registered";
            }
        }
        include __DIR__ . '/../views/auth/signup.php';
    }
    
    private function handleProfilePicUpload() {
        $basePath = '/opt/lampp/htdocs/newemployee/app/';
        $targetDir = $basePath . 'assets/images/';
        if (!is_dir($targetDir)) {
            error_log("Upload directory missing: $targetDir");
            $_SESSION['error'] = "Upload directory not configured";
            return false;
        }
        if (!is_writable($targetDir)) {
            error_log("Directory not writable: $targetDir");
            $_SESSION['error'] = "Upload directory not writable";
            return false;
        }
        $file = $_FILES["profile_pic"];
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];
        $maxFileSize = 2 * 1024 * 1024; // 2MB
    
        if (!in_array($fileExt, $allowedTypes)) {
            $_SESSION['error'] = "Only JPG, JPEG, PNG, GIF allowed";
            return false;
        }
    
        if ($file['size'] > $maxFileSize) {
            $_SESSION['error'] = "File too large (max 2MB)";
            return false;
        }
        $newFilename = uniqid() . '.' . $fileExt;
        $fullPath = $targetDir . $newFilename;
        $relativePath = 'assets/images/' . $newFilename;

        if (move_uploaded_file($file['tmp_name'], $fullPath)) {
            return $relativePath;
        } else {
            error_log("Upload failed. Error: " . $file['error']);
            error_log("Temp path: " . $file['tmp_name']);
            error_log("Target path: $fullPath");
            $_SESSION['error'] = "File upload failed";
            return false;
        }
    }
    public function logout() {
        session_destroy();
        header("Location: index.php?route=login");
        exit();
    }
}