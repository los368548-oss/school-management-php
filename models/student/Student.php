<?php

class Student {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT s.*, c.class_name, c.section FROM students s LEFT JOIN classes c ON s.class_id = c.id");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT s.*, c.class_name, c.section FROM students s LEFT JOIN classes c ON s.class_id = c.id WHERE s.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO students (scholar_number, admission_number, admission_date, first_name, middle_name, last_name, father_name, mother_name, guardian_name, guardian_contact, dob, gender, caste, category, nationality, religion, blood_group, village, address, permanent_address, mobile, email, aadhar, samagra, apaar, pan, previous_school, medical_conditions, photo, class_id, section, roll_number, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute(array_values($data));
    }

    public function update($id, $data) {
        $set = [];
        $params = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = ?";
            $params[] = $value;
        }
        $params[] = $id;
        $stmt = $this->db->prepare("UPDATE students SET " . implode(', ', $set) . " WHERE id = ?");
        return $stmt->execute($params);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM students WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getByClass($classId) {
        $stmt = $this->db->prepare("SELECT * FROM students WHERE class_id = ?");
        $stmt->execute([$classId]);
        return $stmt->fetchAll();
    }
}