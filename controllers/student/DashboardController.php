<?php

class DashboardController {
    public function index() {
        // Get student data
        $userId = Session::get('user_id');
        $userModel = new User();
        $user = $userModel->findById($userId);

        // Assuming student is linked to user, get student info
        $studentModel = new Student();
        $student = $studentModel->findById($user['id']); // Assuming user id is student id for simplicity

        // Get attendance
        $attendanceModel = new Attendance();
        $attendance = $attendanceModel->getByClassAndDate($student['class_id'], date('Y-m-d'));

        // Get results
        $examModel = new Exam();
        $results = $examModel->getResultsForStudent($student['id']);

        // Get fees
        $feeModel = new Fee();
        $fees = $feeModel->getPayments($student['id']);

        require 'views/student/layout.php';
    }
}