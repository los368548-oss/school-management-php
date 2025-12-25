<?php

class Attendance {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT a.*, s.first_name, s.last_name, c.class_name FROM attendance a JOIN students s ON a.student_id = s.id JOIN classes c ON a.class_id = c.id");
        return $stmt->fetchAll();
    }

    public function markAttendance($data) {
        $stmt = $this->db->prepare("INSERT INTO attendance (student_id, class_id, date, status, marked_by) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE status = ?, marked_by = ?");
        return $stmt->execute([$data['student_id'], $data['class_id'], $data['date'], $data['status'], $data['marked_by'], $data['status'], $data['marked_by']]);
    }

    public function getByClassAndDate($classId, $date) {
        $stmt = $this->db->prepare("SELECT a.*, s.first_name, s.last_name FROM attendance a JOIN students s ON a.student_id = s.id WHERE a.class_id = ? AND a.date = ?");
        $stmt->execute([$classId, $date]);
        return $stmt->fetchAll();
    }

    public function getReport($classId, $startDate, $endDate) {
        $stmt = $this->db->prepare("SELECT s.first_name, s.last_name, COUNT(CASE WHEN a.status = 'Present' THEN 1 END) as present, COUNT(CASE WHEN a.status = 'Absent' THEN 1 END) as absent, COUNT(CASE WHEN a.status = 'Late' THEN 1 END) as late FROM students s LEFT JOIN attendance a ON s.id = a.student_id AND a.date BETWEEN ? AND ? WHERE s.class_id = ? GROUP BY s.id");
        $stmt->execute([$startDate, $endDate, $classId]);
        return $stmt->fetchAll();
    }
}