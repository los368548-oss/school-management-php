<?php

class Permission {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM permissions");
        return $stmt->fetchAll();
    }

    public function getByRole($roleId) {
        $stmt = $this->db->prepare("SELECT * FROM permissions WHERE role_id = ?");
        $stmt->execute([$roleId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO permissions (role_id, permission) VALUES (?, ?)");
        return $stmt->execute([$data['role_id'], $data['permission']]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM permissions WHERE id = ?");
        return $stmt->execute([$id]);
    }
}