<?php

require_once '../app/models/User.php';

class LoginController {
    public function login() {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

            $userModel = new User("1", "john_doe", "password");
            $userModel->login($username, $password);

            if (true) {
                header('Location: /dashboard');
                exit();
            } else {
                header('Location: /login');
                exit();
            }
        }
    }
}