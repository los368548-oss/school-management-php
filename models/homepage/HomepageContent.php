<?php

class HomepageContent {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM homepage_content ORDER BY order_position ASC");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM homepage_content WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getBySection($section) {
        $stmt = $this->db->prepare("SELECT * FROM homepage_content WHERE section = ? AND is_active = 1 ORDER BY order_position ASC");
        $stmt->execute([$section]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO homepage_content (section, title, content, image_path, order_position, is_active, updated_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$data['section'], $data['title'], $data['content'], $data['image_path'], $data['order_position'], $data['is_active'], $data['updated_by']]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE homepage_content SET section = ?, title = ?, content = ?, image_path = ?, order_position = ?, is_active = ? WHERE id = ?");
        return $stmt->execute([$data['section'], $data['title'], $data['content'], $data['image_path'], $data['order_position'], $data['is_active'], $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM homepage_content WHERE id = ?");
        return $stmt->execute([$id]);
    }
}