<?php

namespace Http\Forms;

use Core\Validator;

class LoginForm {
    protected $errors = [];

    public function validate($email, $password) {
        if (! Validator::email($email)) {
            $this->errors['email'] = 'Please provide a valid email';
        }

        if (! Validator::string($password)) {
            $this->errors['password'] = 'Wrong password';
        }

        return empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }
}