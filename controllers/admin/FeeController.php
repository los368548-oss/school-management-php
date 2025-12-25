<?php

class FeeController {
    private $feeModel;
    private $studentModel;
    private $classModel;
    private $validator;

    public function __construct() {
        $this->feeModel = new Fee();
        $this->studentModel = new Student();
        $this->classModel = new ClassModel();
        $this->validator = new Validator();
    }

    public function index() {
        $fees = $this->feeModel->getAll();
        require 'views/admin/layout.php';
    }

    public function create() {
        $classes = $this->classModel->getAll();
        require 'views/admin/layout.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/fees');
            exit;
        }

        $data = [
            'class_id' => (int)$_POST['class_id'],
            'fee_type' => Security::sanitize($_POST['fee_type']),
            'amount' => (float)$_POST['amount'],
            'academic_year' => Security::sanitize($_POST['academic_year'])
        ];

        $rules = [
            'class_id' => 'required|numeric',
            'fee_type' => 'required',
            'amount' => 'required|numeric',
            'academic_year' => 'required'
        ];

        if (!$this->validator->validate($data, $rules)) {
            $errors = $this->validator->getErrors();
            $classes = $this->classModel->getAll();
            require 'views/admin/layout.php';
            return;
        }

        if ($this->feeModel->create($data)) {
            header('Location: /admin/fees?success=Fee structure created successfully');
        } else {
            $error = 'Failed to create fee structure';
            $classes = $this->classModel->getAll();
            require 'views/admin/layout.php';
        }
    }

    public function collect() {
        $classes = $this->classModel->getAll();
        $students = [];
        if (isset($_GET['class_id'])) {
            $students = $this->studentModel->getByClass($_GET['class_id']);
        }
        require 'views/admin/layout.php';
    }

    public function storePayment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/fees/collect');
            exit;
        }

        $data = [
            'student_id' => (int)$_POST['student_id'],
            'fee_id' => (int)$_POST['fee_id'],
            'amount_paid' => (float)$_POST['amount_paid'],
            'payment_date' => $_POST['payment_date'],
            'payment_mode' => Security::sanitize($_POST['payment_mode']),
            'transaction_id' => Security::sanitize($_POST['transaction_id']),
            'receipt_number' => Security::sanitize($_POST['receipt_number']),
            'remarks' => Security::sanitize($_POST['remarks']),
            'collected_by' => Session::get('user_id')
        ];

        if ($this->feeModel->recordPayment($data)) {
            header('Location: /admin/fees/collect?success=Payment recorded successfully');
        } else {
            header('Location: /admin/fees/collect?error=Failed to record payment');
        }
    }

    public function report() {
        $payments = $this->feeModel->getPayments();
        $outstanding = $this->feeModel->getOutstandingFees();
        require 'views/admin/layout.php';
    }
}