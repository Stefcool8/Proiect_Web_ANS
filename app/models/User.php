<?php

class User {
    private $id;
    private $username;
    private $email;

    public function __construct($id, $username, $email) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function login($username, $password) {
        // Login logic
    }

    public function logout() {
        // Logout logic
    }

    public function register($username, $email, $password) {
        // Register logic
    }

    public function update($username, $email, $password) {
        // Update logic
    }

    public function delete() {
        // Delete logic
    }

}
