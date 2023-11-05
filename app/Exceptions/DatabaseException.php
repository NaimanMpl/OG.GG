<?php

namespace App\Exceptions;
use Exception;

class DatabaseException extends Exception {

    private int $errorCode;

    public function __construct(int $errorCode, string $message) {
        parent::__construct($message);
        $this->errorCode = $errorCode;
    }

    public function getErrorCode(): int {
        return $this->errorCode;
    }

}

?>