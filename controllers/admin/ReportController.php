<?php

class ReportController {
    private $reportModel;
    private $studentModel;
    private $classModel;
    private $examModel;

    public function __construct() {
        $this->reportModel = new Report();
        $this->studentModel = new Student();
        $this->classModel = new ClassModel();
        $this->examModel = new Exam();
    }

    public function index() {
        $classes = $this->classModel->getAll();
        $exams = $this->examModel->getAll();
        require 'views/admin/layout.php';
    }

    public function studentReport() {
        $studentId = $_GET['student_id'] ?? null;
        if ($studentId) {
            $report = $this->reportModel->getStudentReport($studentId);
        }
        $students = $this->studentModel->getAll();
        require 'views/admin/layout.php';
    }

    public function classReport() {
        $classId = $_GET['class_id'] ?? null;
        if ($classId) {
            $report = $this->reportModel->getClassReport($classId);
        }
        $classes = $this->classModel->getAll();
        require 'views/admin/layout.php';
    }

    public function feeReport() {
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-t');
        $report = $this->reportModel->getFeeReport($startDate, $endDate);
        require 'views/admin/layout.php';
    }

    public function examReport() {
        $examId = $_GET['exam_id'] ?? null;
        if ($examId) {
            $report = $this->reportModel->getExamReport($examId);
        }
        $exams = $this->examModel->getAll();
        require 'views/admin/layout.php';
    }

    public function exportPDF($type, $id) {
        // Use TCPDF for PDF export
        // This would require TCPDF library
        // For now, placeholder
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="report.pdf"');
        echo 'PDF Export Placeholder';
    }

    public function exportExcel($type, $id) {
        // Use PHPExcel or similar for Excel export
        // For now, placeholder
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="report.xls"');
        echo 'Excel Export Placeholder';
    }
}