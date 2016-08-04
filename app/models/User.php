<?php

class User extends Model
{

    protected $table = 'users';

    public function __construct()
    {
        // Load core model   functions
        parent::__construct();
    }


    public function validateAdd($data)
    {


        $first_name = $data['first_name'];
        $last_name = $data['last_name'];

        $email = $data['email'];
        $password = $data['password'];
        $confirm_password = $data['cpassword'];
        $image = $data['image'];


        $errors = array();
        if (empty($first_name)) {
            $errors['fname_error'] = 'This field is required';
        }
        if (empty($last_name)) {
            $errors['lname_error'] = 'This field is required';
        }
        if (empty($email)) {
            $errors['email_error'] = 'This field is required';
        }
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email_error'] = 'Please enter valid email';
        }
        if (empty($password)) {
            $errors['password_error'] = 'This field is required';
        }

        if (!empty($password) && !(preg_match('/[a-z]/', $password) &&
                preg_match('/[A-Z]/', $password) && preg_match('/[0-9]/', $password))
        ) {
            $errors['password_error'] = 'Password must contain digit, uppercase and lowercase letters';
        }

        if (!empty($password) && (strlen($password) < 3) || strlen($password) > 8) {
            $errors['password_error'] = 'Password must contain 3-8 letters';
        }

        if (empty($confirm_password)) {
            $errors['cpassword_error'] = 'This field is required';
        }

        if (!empty($confirm_password) && !empty($password) && $confirm_password != $password) {
            $errors['cpassword_error'] = 'Passwords does not match';
        }

        if (empty($image)) {
            $errors['image_error'] = 'This field is required';
        }

        if (empty($errors)) {
            return true;
        } else {
            return $errors;
        }
    }

    public function validateLogin($data)
    {
        $email = $data['email'];
        $password = $data['password'];

        $errors = array();

        if (empty($email)) {
            $errors['email_error'] = 'This field is required';
        }
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email_error'] = 'Please enter valid email';
        }
        if (empty($password)) {
            $errors['password_error'] = 'This field is required';
        }

        if (!empty($password) && !(preg_match('/[a-z]/', $password) &&
                preg_match('/[A-Z]/', $password) && preg_match('/[0-9]/', $password))
        ) {
            $errors['password_error'] = 'Password must contain digit, uppercase and lowercase letters';
        }

        if (!empty($password) && (strlen($password) < 3) || strlen($password) > 8) {
            $errors['password_error'] = 'Password must contain 3-8 letters';
        }

        if (empty($errors)) {
            return true;
        } else {
            return $errors;
        }
    }

    public function validateEmail($data)
    {
        $email = $data['email'];

        $errors = array();

        if (empty($email)) {
            $errors['email_error'] = 'This field is required';
        }
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email_error'] = 'Please enter valid email';
        }

        if (empty($errors)) {
            return true;
        } else {
            return $errors;
        }
    }

    /**
     * validate Passwords
     */
    public function validatePasswords($data)
    {

        $password = $data['password'];
        $confirm_password = $data['cpassword'];


        $errors = array();

        if (empty($password)) {
            $errors['password_error'] = 'This field is required';
        }

        if (!empty($password) && !(preg_match('/[a-z]/', $password) &&
                preg_match('/[A-Z]/', $password) && preg_match('/[0-9]/', $password))
        ) {
            $errors['password_error'] = 'Password must contain digit, uppercase and lowercase letters';
        }

        if (!empty($password) && (strlen($password) < 3) || strlen($password) > 8) {
            $errors['password_error'] = 'Password must contain 3-8 letters';
        }

        if (empty($confirm_password)) {
            $errors['cpassword_error'] = 'This field is required';
        }

        if (!empty($confirm_password) && !empty($password) && $confirm_password != $password) {
            $errors['cpassword_error'] = 'Passwords does not match';
        }


        if (empty($errors)) {
            return true;
        } else {
            return $errors;
        }
    }
}