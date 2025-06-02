<?php
class User {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT id, username, password_hash, role, is_verified FROM users WHERE email = ?");
        if ($stmt === false) return false;
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }
}
