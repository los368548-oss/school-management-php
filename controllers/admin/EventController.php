<?php

class EventController {
    private $eventModel;
    private $validator;

    public function __construct() {
        $this->eventModel = new Event();
        $this->validator = new Validator();
    }

    public function index() {
        $events = $this->eventModel->getAll();
        require 'views/admin/layout.php';
    }

    public function create() {
        require 'views/admin/layout.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/events');
            exit;
        }

        $data = [
            'title' => Security::sanitize($_POST['title']),
            'description' => Security::sanitize($_POST['description']),
            'event_date' => $_POST['event_date'],
            'event_time' => $_POST['event_time'],
            'location' => Security::sanitize($_POST['location']),
            'created_by' => Session::get('user_id')
        ];

        $rules = [
            'title' => 'required',
            'description' => 'required',
            'event_date' => 'required',
            'event_time' => 'required',
            'location' => 'required'
        ];

        if (!$this->validator->validate($data, $rules)) {
            $errors = $this->validator->getErrors();
            require 'views/admin/layout.php';
            return;
        }

        if ($this->eventModel->create($data)) {
            header('Location: /admin/events?success=Event created successfully');
        } else {
            $error = 'Failed to create event';
            require 'views/admin/layout.php';
        }
    }

    public function edit($id) {
        $event = $this->eventModel->findById($id);
        if (!$event) {
            header('Location: /admin/events?error=Event not found');
            exit;
        }
        require 'views/admin/layout.php';
    }

    public function update($id) {
        // Similar to store
    }

    public function delete($id) {
        if ($this->eventModel->delete($id)) {
            header('Location: /admin/events?success=Event deleted successfully');
        } else {
            header('Location: /admin/events?error=Failed to delete event');
        }
    }
}