<?php

class Role {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM user_roles");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM user_roles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO user_roles (role_name, description) VALUES (?, ?)");
        return $stmt->execute([$data['role_name'], $data['description']]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE user_roles SET role_name = ?, description = ? WHERE id = ?");
        return $stmt->execute([$data['role_name'], $data['description'], $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM user_roles WHERE id = ?");
        return $stmt->execute([$id]);
    }
}