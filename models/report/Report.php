<?php

class Report {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getStudentReport($studentId) {
        $stmt = $this->db->prepare("
            SELECT s.*, c.class_name, c.section,
                   COUNT(CASE WHEN a.status = 'Present' THEN 1 END) as present_days,
                   COUNT(CASE WHEN a.status = 'Absent' THEN 1 END) as absent_days,
                   AVG(er.marks_obtained / er.max_marks * 100) as average_percentage
            FROM students s
            LEFT JOIN classes c ON s.class_id = c.id
            LEFT JOIN attendance a ON s.id = a.student_id
            LEFT JOIN exam_results er ON s.id = er.student_id
            WHERE s.id = ?
            GROUP BY s.id
        ");
        $stmt->execute([$studentId]);
        return $stmt->fetch();
    }

    public function getClassReport($classId) {
        $stmt = $this->db->prepare("
            SELECT s.first_name, s.last_name, s.roll_number,
                   COUNT(CASE WHEN a.status = 'Present' THEN 1 END) as present_days,
                   COUNT(CASE WHEN a.status = 'Absent' THEN 1 END) as absent_days,
                   AVG(er.marks_obtained / er.max_marks * 100) as average_percentage
            FROM students s
            LEFT JOIN attendance a ON s.id = a.student_id
            LEFT JOIN exam_results er ON s.id = er.student_id
            WHERE s.class_id = ?
            GROUP BY s.id
            ORDER BY s.roll_number
        ");
        $stmt->execute([$classId]);
        return $stmt->fetchAll();
    }

    public function getFeeReport($startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT fp.*, s.first_name, s.last_name, f.fee_type
            FROM fee_payments fp
            JOIN students s ON fp.student_id = s.id
            JOIN fees f ON fp.fee_id = f.id
            WHERE fp.payment_date BETWEEN ? AND ?
            ORDER BY fp.payment_date DESC
        ");
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }

    public function getExamReport($examId) {
        $stmt = $this->db->prepare("
            SELECT er.*, s.first_name, s.last_name, sub.subject_name
            FROM exam_results er
            JOIN students s ON er.student_id = s.id
            JOIN subjects sub ON er.subject_id = sub.id
            WHERE er.exam_id = ?
            ORDER BY s.roll_number
        ");
        $stmt->execute([$examId]);
        return $stmt->fetchAll();
    }

    public function getDashboardStats() {
        $stats = [];

        // Total students
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM students");
        $stats['total_students'] = $stmt->fetch()['total'];

        // Total classes
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM classes");
        $stats['total_classes'] = $stmt->fetch()['total'];

        // Today's attendance
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM attendance WHERE date = CURDATE()");
        $stmt->execute();
        $stats['today_attendance'] = $stmt->fetch()['total'];

        // Pending fees
        $stmt = $this->db->query("SELECT SUM(f.amount) - COALESCE(SUM(fp.amount_paid), 0) as total FROM fees f LEFT JOIN fee_payments fp ON f.id = fp.fee_id");
        $stats['pending_fees'] = $stmt->fetch()['total'] ?? 0;

        return $stats;
    }
}