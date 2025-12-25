<?php

class Gallery {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM gallery ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO gallery (title, description, image_path, category, uploaded_by) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$data['title'], $data['description'], $data['image_path'], $data['category'], $data['uploaded_by']]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE gallery SET title = ?, description = ?, image_path = ?, category = ? WHERE id = ?");
        return $stmt->execute([$data['title'], $data['description'], $data['image_path'], $data['category'], $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM gallery WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getByCategory($category) {
        $stmt = $this->db->prepare("SELECT * FROM gallery WHERE category = ? ORDER BY created_at DESC");
        $stmt->execute([$category]);
        return $stmt->fetchAll();
    }
}