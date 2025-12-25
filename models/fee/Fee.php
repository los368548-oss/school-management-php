<?php

class Fee {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT f.*, c.class_name FROM fees f JOIN classes c ON f.class_id = c.id");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM fees WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO fees (class_id, fee_type, amount, academic_year) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$data['class_id'], $data['fee_type'], $data['amount'], $data['academic_year']]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE fees SET class_id = ?, fee_type = ?, amount = ?, academic_year = ? WHERE id = ?");
        return $stmt->execute([$data['class_id'], $data['fee_type'], $data['amount'], $data['academic_year'], $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM fees WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getPayments($studentId = null) {
        $query = "SELECT fp.*, s.first_name, s.last_name, f.fee_type FROM fee_payments fp JOIN students s ON fp.student_id = s.id JOIN fees f ON fp.fee_id = f.id";
        if ($studentId) {
            $query .= " WHERE fp.student_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$studentId]);
        } else {
            $stmt = $this->db->query($query);
        }
        return $stmt->fetchAll();
    }

    public function recordPayment($data) {
        $stmt = $this->db->prepare("INSERT INTO fee_payments (student_id, fee_id, amount_paid, payment_date, payment_mode, transaction_id, receipt_number, remarks, collected_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$data['student_id'], $data['fee_id'], $data['amount_paid'], $data['payment_date'], $data['payment_mode'], $data['transaction_id'], $data['receipt_number'], $data['remarks'], $data['collected_by']]);
    }

    public function getOutstandingFees() {
        $stmt = $this->db->query("SELECT s.first_name, s.last_name, f.fee_type, f.amount, SUM(fp.amount_paid) as paid FROM students s JOIN fees f ON s.class_id = f.class_id LEFT JOIN fee_payments fp ON f.id = fp.fee_id AND s.id = fp.student_id GROUP BY s.id, f.id HAVING f.amount > IFNULL(SUM(fp.amount_paid), 0)");
        return $stmt->fetchAll();
    }
}