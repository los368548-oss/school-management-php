<?php

class AuthController {
    private $userModel;
    private $validator;

    public function __construct() {
        $this->userModel = new User();
        $this->validator = new Validator();
    }

    public function showLogin() {
        if (Auth::isLoggedIn()) {
            $this->redirectBasedOnRole();
        }
        require 'views/auth/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $email = Security::sanitize($_POST['email']);
        $password = $_POST['password'];

        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ];

        if (!$this->validator->validate($_POST, $rules)) {
            $errors = $this->validator->getErrors();
            require 'views/auth/login.php';
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if ($user && Security::verifyPassword($password, $user['password'])) {
            Session::start();
            Session::set('user_id', $user['id']);
            Session::set('user_role', $user['role_name']);
            $this->userModel->updateLastLogin($user['id']);
            $this->redirectBasedOnRole();
        } else {
            $error = 'Invalid credentials';
            require 'views/auth/login.php';
        }
    }

    public function logout() {
        Auth::logout();
    }

    private function redirectBasedOnRole() {
        $role = Session::get('user_role');
        if ($role === 'Admin') {
            header('Location: /admin/dashboard');
        } elseif ($role === 'Student') {
            header('Location: /student/dashboard');
        } else {
            header('Location: /');
        }
        exit;
    }
}