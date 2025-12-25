<?php

class AttendanceController {
    private $attendanceModel;
    private $studentModel;
    private $classModel;
    private $validator;

    public function __construct() {
        $this->attendanceModel = new Attendance();
        $this->studentModel = new Student();
        $this->classModel = new ClassModel();
        $this->validator = new Validator();
    }

    public function index() {
        $attendances = $this->attendanceModel->getAll();
        require 'views/admin/layout.php';
    }

    public function mark() {
        $classes = $this->classModel->getAll();
        require 'views/admin/layout.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/attendance');
            exit;
        }

        $classId = (int)$_POST['class_id'];
        $date = $_POST['date'];
        $attendances = $_POST['attendance'];

        foreach ($attendances as $studentId => $status) {
            $this->attendanceModel->markAttendance([
                'student_id' => $studentId,
                'class_id' => $classId,
                'date' => $date,
                'status' => $status,
                'marked_by' => Session::get('user_id')
            ]);
        }

        header('Location: /admin/attendance?success=Attendance marked successfully');
    }

    public function report() {
        $classes = $this->classModel->getAll();
        $report = [];
        if (isset($_GET['class_id']) && isset($_GET['start_date']) && isset($_GET['end_date'])) {
            $report = $this->attendanceModel->getReport($_GET['class_id'], $_GET['start_date'], $_GET['end_date']);
        }
        require 'views/admin/layout.php';
    }
}