<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config.php';
class ProfileController {
    private $userModel;
    private $db;
    
    public function __construct() {
        $this->userModel = new User();
        $this->db = new Config();
    }
    public function view() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?route=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostRequest();
            exit();
        }

        $user = $this->userModel->getUser($_SESSION['user_id']);
        $qualifications = $this->getQualifications($_SESSION['user_id']);
        $experiences = $this->getExperiences($_SESSION['user_id']);
        
        include __DIR__ . '/../views/profile/profile.php';
    }

    private function handlePostRequest() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit();
        }

        $userId = $_SESSION['user_id'];

        if (!empty($_FILES["profile_pic"]["name"])) {
            $this->handleProfilePicUpdate($userId);
        } elseif (!empty($_POST['field']) && isset($_POST['value'])) {
            $field = $_POST['field'];
            $value = $_POST['value'];

            if ($field === "qualifications" || $field === "experiences") {
                $this->handleArrayUpdate($userId, $field, $value);
            } else {
                $this->handleFieldUpdate($userId, $field, $value);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        }
    }

    private function handleProfilePicUpdate($userId) {
        $targetDir = "assets/images/";
        $fileName = basename($_FILES["profile_pic"]["name"]);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedTypes = ["jpg", "jpeg", "png"];
        $maxFileSize = 2 * 1024 * 1024;

        if (!in_array($fileExt, $allowedTypes)) {
            echo json_encode(['status' => 'error', 'message' => 'Only JPG, JPEG, PNG files are allowed.']);
            exit();
        }

        if ($_FILES["profile_pic"]["size"] > $maxFileSize) {
            echo json_encode(['status' => 'error', 'message' => 'File size must be less than 2MB.']);
            exit();
        }

        $profilePic = $targetDir . uniqid() . "." . $fileExt;

        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $profilePic)) {
            $stmt = $this->db->preparedQuery(
                "SELECT profile_pic FROM users WHERE id = ?",
                [$userId],
                "i"
            );
            $oldPic = $stmt->get_result()->fetch_assoc()['profile_pic'];
            if ($oldPic && file_exists($oldPic)) {
                unlink($oldPic);
            }

            $updateStmt = $this->db->preparedQuery(
                "UPDATE users SET profile_pic = ? WHERE id = ?",
                [$profilePic, $userId],
                "si"
            );

            if ($updateStmt) {
                echo json_encode([
                    'status' => 'success', 
                    'message' => 'Profile picture updated successfully!',
                    'newPath' => $profilePic
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error uploading file.']);
        }
    }

    private function handleArrayUpdate($userId, $field, $value) {
        $data = json_decode($value, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data format!']);
            exit();
        }

        $table = ($field === "qualifications") ? "qualifications" : "experiences";
        $column = ($field === "qualifications") ? "qualification" : "experience";

        $deleteStmt = $this->db->preparedQuery(
            "DELETE FROM $table WHERE user_id = ?",
            [$userId],
            "i"
        );

        if (!$deleteStmt) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to clear old data']);
            exit();
        }

        foreach ($data as $entry) {
            $cleanEntry = $this->db->escape($entry);
            if (!empty($cleanEntry)) {
                $insertStmt = $this->db->preparedQuery(
                    "INSERT INTO $table (user_id, $column) VALUES (?, ?)",
                    [$userId, $cleanEntry],
                    "is"
                );
                if (!$insertStmt) {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to insert data']);
                    exit();
                }
            }
        }

        echo json_encode(['status' => 'success', 'message' => ucfirst($field) . ' updated successfully!']);
    }

    private function handleFieldUpdate($userId, $field, $value) {
        $allowedFields = ['fullname', 'dob', 'perm_address1', 'perm_address2', 'perm_city', 
                         'perm_state', 'curr_address1', 'curr_address2', 'curr_city', 'curr_state'];

        if (!in_array($field, $allowedFields)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid field specified!']);
            exit();
        }

        $updateStmt = $this->db->preparedQuery(
            "UPDATE users SET `$field` = ? WHERE id = ?",
            [$value, $userId],
            "si"
        );
        if ($updateStmt) {
            echo json_encode(['status' => 'success', 'message' => ucfirst($field) . ' updated successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update field']);
        }
    }
    private function getQualifications($userId) {
        $qualifications = [];
        $stmt = $this->db->preparedQuery(
            "SELECT qualification FROM qualifications WHERE user_id = ?",
            [$userId],
            "i"
        );
        if ($stmt) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $qualifications[] = $row['qualification'];
            }
        }
        return $qualifications;
    }
    private function getExperiences($userId) {
        $experiences = [];
        $stmt = $this->db->preparedQuery(
            "SELECT experience FROM experiences WHERE user_id = ?",
            [$userId],
            "i"
        );
        if ($stmt) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $experiences[] = $row['experience'];
            }
        }
        return $experiences;
    }
}
