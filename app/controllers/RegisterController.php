<?php

namespace App\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;

class RegisterController {
    public function register() {
        $errors = [];

        // Your registration logic here, for example:
        if (isset($_POST['submit'])) {

            // Your validation logic and other code.
            $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
            $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
            $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
            $phone = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING));
            $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
            $confirmPassword = trim(filter_input(INPUT_POST, 'confirmPassword', FILTER_SANITIZE_STRING));

            $isValid = true;

            if (empty($name) || empty($username) || empty($email) || empty($phone) || empty($password) || empty($confirmPassword)) {
                $isValid = false;
                echo "All fields are required.<br>";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $isValid = false;
                echo "Invalid email format.<br>";
            }
            if (strlen($password) < 8) {
                $isValid = false;
                echo "Password must be at least 8 characters long.<br>";
            }
            if ($password !== $confirmPassword) {
                $isValid = false;
                echo "Passwords do not match.<br>";
            }

            if (!$isValid) {
                $errors[] = "Error message";
            } else {
                $user = new User();
                $user->setName($name);
                $user->setUsername($username);
                $user->setEmail($email);
                $user->setPhone($phone);
                $user->setPassword(password_hash($password, PASSWORD_DEFAULT));

                $userRepository = new UserRepository();
                $userRepository->register($user);
            }
        }

        return $errors;
    }
}
