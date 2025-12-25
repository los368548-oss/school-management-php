<?php

class StudentController {
    private $studentModel;
    private $classModel;
    private $validator;

    public function __construct() {
        $this->studentModel = new Student();
        $this->classModel = new ClassModel(); // Assume we have it
        $this->validator = new Validator();
    }

    public function index() {
        $students = $this->studentModel->getAll();
        require 'views/admin/layout.php';
    }

    public function create() {
        $classes = $this->classModel->getAll();
        require 'views/admin/layout.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/students');
            exit;
        }

        $data = [
            'scholar_number' => Security::sanitize($_POST['scholar_number']),
            'admission_number' => Security::sanitize($_POST['admission_number']),
            'admission_date' => $_POST['admission_date'],
            'first_name' => Security::sanitize($_POST['first_name']),
            'middle_name' => Security::sanitize($_POST['middle_name']),
            'last_name' => Security::sanitize($_POST['last_name']),
            'father_name' => Security::sanitize($_POST['father_name']),
            'mother_name' => Security::sanitize($_POST['mother_name']),
            'guardian_name' => Security::sanitize($_POST['guardian_name']),
            'guardian_contact' => Security::sanitize($_POST['guardian_contact']),
            'dob' => $_POST['dob'],
            'gender' => $_POST['gender'],
            'caste' => Security::sanitize($_POST['caste']),
            'category' => Security::sanitize($_POST['category']),
            'nationality' => Security::sanitize($_POST['nationality']),
            'religion' => Security::sanitize($_POST['religion']),
            'blood_group' => $_POST['blood_group'],
            'village' => Security::sanitize($_POST['village']),
            'address' => Security::sanitize($_POST['address']),
            'permanent_address' => Security::sanitize($_POST['permanent_address']),
            'mobile' => Security::sanitize($_POST['mobile']),
            'email' => Security::sanitize($_POST['email']),
            'aadhar' => Security::sanitize($_POST['aadhar']),
            'samagra' => Security::sanitize($_POST['samagra']),
            'apaar' => Security::sanitize($_POST['apaar']),
            'pan' => Security::sanitize($_POST['pan']),
            'previous_school' => Security::sanitize($_POST['previous_school']),
            'medical_conditions' => Security::sanitize($_POST['medical_conditions']),
            'photo' => '', // Handle file upload
            'class_id' => (int)$_POST['class_id'],
            'section' => Security::sanitize($_POST['section']),
            'roll_number' => (int)$_POST['roll_number'],
            'status' => 'Active'
        ];

        $rules = [
            'scholar_number' => 'required',
            'admission_number' => 'required',
            'admission_date' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'father_name' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'class_id' => 'required|numeric',
            'email' => 'email'
        ];

        if (!$this->validator->validate($data, $rules)) {
            $errors = $this->validator->getErrors();
            $classes = $this->classModel->getAll();
            require 'views/admin/layout.php';
            return;
        }

        if ($this->studentModel->create($data)) {
            header('Location: /admin/students?success=Student created successfully');
        } else {
            $error = 'Failed to create student';
            $classes = $this->classModel->getAll();
            require 'views/admin/layout.php';
        }
    }

    public function edit($id) {
        $student = $this->studentModel->findById($id);
        $classes = $this->classModel->getAll();
        if (!$student) {
            header('Location: /admin/students?error=Student not found');
            exit;
        }
        require 'views/admin/layout.php';
    }

    public function update($id) {
        // Similar to store
    }

    public function delete($id) {
        if ($this->studentModel->delete($id)) {
            header('Location: /admin/students?success=Student deleted successfully');
        } else {
            header('Location: /admin/students?error=Failed to delete student');
        }
    }

    public function view($id) {
        $student = $this->studentModel->findById($id);
        if (!$student) {
            header('Location: /admin/students?error=Student not found');
            exit;
        }
        require 'views/admin/layout.php';
    }
}