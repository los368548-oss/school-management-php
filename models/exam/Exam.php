<?php

class Exam {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT e.*, c.class_name FROM exams e JOIN classes c ON e.class_id = c.id");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM exams WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO exams (exam_name, exam_type, class_id, start_date, end_date, created_by) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$data['exam_name'], $data['exam_type'], $data['class_id'], $data['start_date'], $data['end_date'], $data['created_by']]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE exams SET exam_name = ?, exam_type = ?, class_id = ?, start_date = ?, end_date = ? WHERE id = ?");
        return $stmt->execute([$data['exam_name'], $data['exam_type'], $data['class_id'], $data['start_date'], $data['end_date'], $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM exams WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getResults($examId) {
        $stmt = $this->db->prepare("SELECT er.*, s.first_name, s.last_name, sub.subject_name FROM exam_results er JOIN students s ON er.student_id = s.id JOIN subjects sub ON er.subject_id = sub.id WHERE er.exam_id = ?");
        $stmt->execute([$examId]);
        return $stmt->fetchAll();
    }

    public function enterResult($data) {
        $stmt = $this->db->prepare("INSERT INTO exam_results (exam_id, student_id, subject_id, marks_obtained, max_marks, grade, remarks, entered_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE marks_obtained = ?, grade = ?, remarks = ?, entered_by = ?");
        return $stmt->execute([$data['exam_id'], $data['student_id'], $data['subject_id'], $data['marks_obtained'], $data['max_marks'], $data['grade'], $data['remarks'], $data['entered_by'], $data['marks_obtained'], $data['grade'], $data['remarks'], $data['entered_by']]);
    }
}