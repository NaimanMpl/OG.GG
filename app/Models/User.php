<?php

namespace App\Models;

class User {

    private string $username, $email, $hashedPassword;

    public function __construct(string $username, string $email, string $hashedPassword) {
        $this->username = $username;
        $this->email = $email;
        $this->hashedPassword = $hashedPassword;
    }

    public function getName(): string {
        return $this->username;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getHashedPassword(): string {
        return $this->hashedPassword;
    }

}

?>