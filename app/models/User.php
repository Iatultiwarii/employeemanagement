<?php
class User {
    private $db;
    
    public function __construct() {
        $this->db = new Config();
    }
    
    public function login($email, $password) {
        $sql = "SELECT id, password FROM users WHERE email = ?";
        $stmt = $this->db->preparedQuery($sql, [$email], "s");
        
        if ($stmt) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $data = $result->fetch_assoc();
                if (password_verify($password, $data['password'])) {
                    return $data['id'];
                }
            }
            $stmt->close();
        }
        error_log("Login failed for email: $email");
        return false;
    }
    
    public function register($data) {
        $checkSql = "SELECT id FROM users WHERE email = ?";
        $checkStmt = $this->db->preparedQuery($checkSql, [$data['email']], "s");
        
        if ($checkStmt && $checkStmt->get_result()->num_rows > 0) {
            $checkStmt->close();
            error_log("Registration attempt with existing email: " . $data['email']);
            return false;
        }
        $checkStmt->close();

        $sql = "INSERT INTO users (fullname, email, password, dob, perm_address1, perm_address2, perm_city, perm_state, curr_address1, curr_address2, curr_city, curr_state, profile_pic) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $password = password_hash($data['password'], PASSWORD_BCRYPT);
        $params = [
            $data['fullname'],
            $data['email'],
            $password,
            $data['dob'],
            $data['perm_address1'],
            $data['perm_address2'] ?? '',
            $data['perm_city'],
            $data['perm_state'],
            $data['curr_address1'],
            $data['curr_address2'] ?? '',
            $data['curr_city'],
            $data['curr_state'],
            $data['profile_pic'] ?? ''
        ];
        
        $types = "sssssssssssss";
        $stmt = $this->db->preparedQuery($sql, $params, $types);
        
        if ($stmt) {
            $userId = $this->db->getInsertId();
            $stmt->close();
            
            if (!empty($data['qualifications'])) {
                $this->saveQualifications($userId, $data['qualifications']);
            }
            
            if (!empty($data['experiences'])) {
                $this->saveExperiences($userId, $data['experiences']);
            }
            
            return true;
        }
        return false;
    }
    
    private function saveQualifications($userId, $qualifications) {
        $sql = "INSERT INTO qualifications (user_id, qualification) VALUES (?, ?)";
        foreach ($qualifications as $qualification) {
            $qualification = $this->db->escape($qualification);
            $stmt = $this->db->preparedQuery($sql, [$userId, $qualification], "is");
            $stmt->close();
        }
    }
    
    private function saveExperiences($userId, $experiences) {
        $sql = "INSERT INTO experiences (user_id, experience) VALUES (?, ?)";
        foreach ($experiences as $experience) {
            $experience = $this->db->escape($experience);
            $stmt = $this->db->preparedQuery($sql, [$userId, $experience], "is");
            $stmt->close();
        }
    }
    
    public function getUser($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->db->preparedQuery($sql, [$id], "i");
        
        if ($stmt) {
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
            return $user;
        }
        return false;
    }
}