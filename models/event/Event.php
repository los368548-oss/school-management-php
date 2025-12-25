<?php

class Event {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM events ORDER BY event_date DESC");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO events (title, description, event_date, event_time, location, created_by) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$data['title'], $data['description'], $data['event_date'], $data['event_time'], $data['location'], $data['created_by']]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE events SET title = ?, description = ?, event_date = ?, event_time = ?, location = ? WHERE id = ?");
        return $stmt->execute([$data['title'], $data['description'], $data['event_date'], $data['event_time'], $data['location'], $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM events WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getUpcoming() {
        $stmt = $this->db->prepare("SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}