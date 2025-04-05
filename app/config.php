<?php
class Config {
    private $conn;
    
    public function __construct() {
$this->conn = new mysqli('localhost', 'root', '', 'Employee_management');
        if ($this->conn->connect_error) {
            error_log("Database connection failed: " . $this->conn->connect_error);
            die("Connection failed");
        }
    }
    public function preparedQuery($sql, $params = [], $types = "") {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        if (!$stmt->execute()) {
            error_log("Execute failed: " . $stmt->error);
            return false;
        }
        return $stmt;
    }
    public function escape($data) {
        return $this->conn->real_escape_string(htmlspecialchars(stripslashes(trim($data))));
    }
    
    public function getInsertId() {
        return $this->conn->insert_id;
    }
    
    public function error() {
        return $this->conn->error;
    }
}