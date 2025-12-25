<?php

class ClassController {
    private $classModel;
    private $subjectModel;
    private $validator;

    public function __construct() {
        $this->classModel = new ClassModel();
        $this->subjectModel = new Subject();
        $this->validator = new Validator();
    }

    public function index() {
        $classes = $this->classModel->getAll();
        require 'views/admin/layout.php';
    }

    public function create() {
        require 'views/admin/layout.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/classes');
            exit;
        }

        $data = [
            'class_name' => Security::sanitize($_POST['class_name']),
            'section' => Security::sanitize($_POST['section']),
            'capacity' => (int)$_POST['capacity']
        ];

        $rules = [
            'class_name' => 'required',
            'capacity' => 'numeric'
        ];

        if (!$this->validator->validate($data, $rules)) {
            $errors = $this->validator->getErrors();
            require 'views/admin/layout.php';
            return;
        }

        if ($this->classModel->create($data)) {
            header('Location: /admin/classes?success=Class created successfully');
        } else {
            $error = 'Failed to create class';
            require 'views/admin/layout.php';
        }
    }

    public function edit($id) {
        $class = $this->classModel->findById($id);
        if (!$class) {
            header('Location: /admin/classes?error=Class not found');
            exit;
        }
        require 'views/admin/layout.php';
    }

    public function update($id) {
        // Similar to store
    }

    public function delete($id) {
        if ($this->classModel->delete($id)) {
            header('Location: /admin/classes?success=Class deleted successfully');
        } else {
            header('Location: /admin/classes?error=Failed to delete class');
        }
    }

    public function subjects($classId) {
        // Manage subjects for class
    }
}