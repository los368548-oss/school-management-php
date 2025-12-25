<?php

class UserController {
    private $userModel;
    private $roleModel;
    private $validator;

    public function __construct() {
        $this->userModel = new User();
        $this->roleModel = new Role();
        $this->validator = new Validator();
    }

    public function index() {
        $users = $this->userModel->getAll();
        require 'views/admin/layout.php';
    }

    public function create() {
        $roles = $this->roleModel->getAll();
        require 'views/admin/layout.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/users');
            exit;
        }

        $data = [
            'username' => Security::sanitize($_POST['username']),
            'email' => Security::sanitize($_POST['email']),
            'password' => $_POST['password'],
            'role_id' => (int)$_POST['role_id']
        ];

        $rules = [
            'username' => 'required|min:3|max:50',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role_id' => 'required|numeric'
        ];

        if (!$this->validator->validate($data, $rules)) {
            $errors = $this->validator->getErrors();
            $roles = $this->roleModel->getAll();
            require 'views/admin/layout.php';
            return;
        }

        if ($this->userModel->create($data)) {
            header('Location: /admin/users?success=User created successfully');
        } else {
            $error = 'Failed to create user';
            $roles = $this->roleModel->getAll();
            require 'views/admin/layout.php';
        }
    }

    public function edit($id) {
        $user = $this->userModel->findById($id);
        $roles = $this->roleModel->getAll();
        if (!$user) {
            header('Location: /admin/users?error=User not found');
            exit;
        }
        require 'views/admin/layout.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/users');
            exit;
        }

        $data = [
            'username' => Security::sanitize($_POST['username']),
            'email' => Security::sanitize($_POST['email']),
            'role_id' => (int)$_POST['role_id']
        ];

        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }

        $rules = [
            'username' => 'required|min:3|max:50',
            'email' => 'required|email',
            'role_id' => 'required|numeric'
        ];

        if (!empty($_POST['password'])) {
            $rules['password'] = 'min:6';
        }

        if (!$this->validator->validate($data, $rules)) {
            $errors = $this->validator->getErrors();
            $user = $this->userModel->findById($id);
            $roles = $this->roleModel->getAll();
            require 'views/admin/layout.php';
            return;
        }

        if ($this->userModel->update($id, $data)) {
            header('Location: /admin/users?success=User updated successfully');
        } else {
            $error = 'Failed to update user';
            $user = $this->userModel->findById($id);
            $roles = $this->roleModel->getAll();
            require 'views/admin/layout.php';
        }
    }

    public function delete($id) {
        if ($this->userModel->delete($id)) {
            header('Location: /admin/users?success=User deleted successfully');
        } else {
            header('Location: /admin/users?error=Failed to delete user');
        }
    }
}