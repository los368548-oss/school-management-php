<?php

class Subject {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM subjects");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM subjects WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO subjects (subject_name, subject_code, description) VALUES (?, ?, ?)");
        return $stmt->execute([$data['subject_name'], $data['subject_code'], $data['description']]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE subjects SET subject_name = ?, subject_code = ?, description = ? WHERE id = ?");
        return $stmt->execute([$data['subject_name'], $data['subject_code'], $data['description'], $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM subjects WHERE id = ?");
        return $stmt->execute([$id]);
    }
}