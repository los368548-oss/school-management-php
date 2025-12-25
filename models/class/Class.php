<?php

class ClassModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM classes");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM classes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO classes (class_name, section, capacity) VALUES (?, ?, ?)");
        return $stmt->execute([$data['class_name'], $data['section'], $data['capacity']]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE classes SET class_name = ?, section = ?, capacity = ? WHERE id = ?");
        return $stmt->execute([$data['class_name'], $data['section'], $data['capacity'], $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM classes WHERE id = ?");
        return $stmt->execute([$id]);
    }
}