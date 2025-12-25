<?php

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT u.*, r.role_name FROM users u JOIN user_roles r ON u.role_id = r.id WHERE u.email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT u.*, r.role_name FROM users u JOIN user_roles r ON u.role_id = r.id WHERE u.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $data['password'] = Security::hashPassword($data['password']);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$data['username'], $data['email'], $data['password'], $data['role_id']]);
    }

    public function updateLastLogin($id) {
        $stmt = $this->db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT u.*, r.role_name FROM users u JOIN user_roles r ON u.role_id = r.id");
        return $stmt->fetchAll();
    }

    public function update($id, $data) {
        if (isset($data['password'])) {
            $data['password'] = Security::hashPassword($data['password']);
        }
        $set = [];
        $params = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = ?";
            $params[] = $value;
        }
        $params[] = $id;
        $stmt = $this->db->prepare("UPDATE users SET " . implode(', ', $set) . " WHERE id = ?");
        return $stmt->execute($params);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}