<?php

namespace App\Models;

class User {
    private int $id;
    private string $username;
    private string $email;
    private string $name;
    private string $phone;
    private string $password;

    public function getUsername(): string {
        return $this->username;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setUsername(string $username): void {
        $this->username = $username;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setPhone(string $phone): void {
        $this->phone = $phone;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPhone(): string {
        return $this->phone;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public static function fromArray(array $properties): self {
        $user = new self();
        $user->setId($properties['id']);
        $user->setUsername($properties['username']);
        // $user->setEmail($properties['email']);
        $user->setName($properties['name']);
        // $user->setPhone($properties['phone']);
        $user->setPassword($properties['password_hash']);
        return $user;
    }
}
