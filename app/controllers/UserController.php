<?php

require_once '../app/models/User.php';

class UserController {

    public function getUser($id) {
        $user = new User($id, 'john_doe', 'john@example.com');

        return include '../app/views/pages/profile.php';
    }

    public function updateUser($id, $username, $email) {
        $updatedUser = new User($id, $username, $email);

        return $updatedUser;
    }

}

