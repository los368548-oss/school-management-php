<?php

class ExamController {
    private $examModel;
    private $classModel;
    private $studentModel;
    private $subjectModel;
    private $validator;

    public function __construct() {
        $this->examModel = new Exam();
        $this->classModel = new ClassModel();
        $this->studentModel = new Student();
        $this->subjectModel = new Subject();
        $this->validator = new Validator();
    }

    public function index() {
        $exams = $this->examModel->getAll();
        require 'views/admin/layout.php';
    }

    public function create() {
        $classes = $this->classModel->getAll();
        require 'views/admin/layout.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/exams');
            exit;
        }

        $data = [
            'exam_name' => Security::sanitize($_POST['exam_name']),
            'exam_type' => Security::sanitize($_POST['exam_type']),
            'class_id' => (int)$_POST['class_id'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'created_by' => Session::get('user_id')
        ];

        $rules = [
            'exam_name' => 'required',
            'exam_type' => 'required',
            'class_id' => 'required|numeric',
            'start_date' => 'required',
            'end_date' => 'required'
        ];

        if (!$this->validator->validate($data, $rules)) {
            $errors = $this->validator->getErrors();
            $classes = $this->classModel->getAll();
            require 'views/admin/layout.php';
            return;
        }

        if ($this->examModel->create($data)) {
            header('Location: /admin/exams?success=Exam created successfully');
        } else {
            $error = 'Failed to create exam';
            $classes = $this->classModel->getAll();
            require 'views/admin/layout.php';
        }
    }

    public function edit($id) {
        $exam = $this->examModel->findById($id);
        $classes = $this->classModel->getAll();
        if (!$exam) {
            header('Location: /admin/exams?error=Exam not found');
            exit;
        }
        require 'views/admin/layout.php';
    }

    public function update($id) {
        // Similar to store
    }

    public function delete($id) {
        if ($this->examModel->delete($id)) {
            header('Location: /admin/exams?success=Exam deleted successfully');
        } else {
            header('Location: /admin/exams?error=Failed to delete exam');
        }
    }

    public function results($examId) {
        $exam = $this->examModel->findById($examId);
        $results = $this->examModel->getResults($examId);
        require 'views/admin/layout.php';
    }

    public function enterResult() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/exams');
            exit;
        }

        $data = [
            'exam_id' => (int)$_POST['exam_id'],
            'student_id' => (int)$_POST['student_id'],
            'subject_id' => (int)$_POST['subject_id'],
            'marks_obtained' => (float)$_POST['marks_obtained'],
            'max_marks' => (float)$_POST['max_marks'],
            'grade' => Security::sanitize($_POST['grade']),
            'remarks' => Security::sanitize($_POST['remarks']),
            'entered_by' => Session::get('user_id')
        ];

        if ($this->examModel->enterResult($data)) {
            header('Location: /admin/exams/results/' . $data['exam_id'] . '?success=Result entered successfully');
        } else {
            header('Location: /admin/exams/results/' . $data['exam_id'] . '?error=Failed to enter result');
        }
    }
}