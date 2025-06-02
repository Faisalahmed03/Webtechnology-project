<?php
class User {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function findByVerificationToken($token) {
        $stmt = $this->conn->prepare("SELECT id, is_verified FROM users WHERE verification_token = ?");
        if ($stmt === false) return false;
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->num_rows === 1 ? $result->fetch_assoc() : false;
        $stmt->close();
        return $user;
    }

    public function verifyUser($id) {
        $stmt = $this->conn->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?");
        if ($stmt === false) return false;
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $error = $stmt->error;
        $stmt->close();
        return $success ? true : $error;
    }
}
