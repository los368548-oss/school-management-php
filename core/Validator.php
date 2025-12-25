<?php

class Validator {
    private $errors = [];

    public function validate($data, $rules) {
        $this->errors = [];
        foreach ($rules as $field => $rule) {
            $rulesArray = explode('|', $rule);
            foreach ($rulesArray as $singleRule) {
                $this->applyRule($field, $data[$field] ?? null, $singleRule);
            }
        }
        return empty($this->errors);
    }

    private function applyRule($field, $value, $rule) {
        if (strpos($rule, ':') !== false) {
            list($ruleName, $param) = explode(':', $rule, 2);
        } else {
            $ruleName = $rule;
            $param = null;
        }

        switch ($ruleName) {
            case 'required':
                if (empty($value)) {
                    $this->errors[$field][] = "$field is required";
                }
                break;
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = "$field must be a valid email";
                }
                break;
            case 'min':
                if (strlen($value) < $param) {
                    $this->errors[$field][] = "$field must be at least $param characters";
                }
                break;
            case 'max':
                if (strlen($value) > $param) {
                    $this->errors[$field][] = "$field must not exceed $param characters";
                }
                break;
            case 'numeric':
                if (!is_numeric($value)) {
                    $this->errors[$field][] = "$field must be numeric";
                }
                break;
            // Add more rules as needed
        }
    }

    public function getErrors() {
        return $this->errors;
    }

    public function getFirstError($field) {
        return $this->errors[$field][0] ?? null;
    }
}