<?php

namespace App;
use App\Exceptions\DatabaseException;
use Exception;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;

class ErrorHandler {

    public static function handleDatabaseError(Exception $e, Response $response, int $httpCode, string $errorMessage): Response {
        
        if ($e instanceof PDOException) {
            $errorCode = $e->errorInfo[1];
        } else if ($e instanceof DatabaseException) {
            $errorCode = $e->getErrorCode();
        }

        if ($errorCode == 1062) {
            $response->getBody()->write(json_encode(["error" => $errorMessage]));
        } else {
            $response->getBody()->write(json_encode(["error" => "Le serveur a rencontre un probleme avec la base de données :" . $e->getMessage()]));
            $httpCode = 500;
        }

        return (
            $response
                ->withStatus($httpCode)
                ->withHeader("Content-Type", "application/json;charset=utf-8")
        );
    }

    public static function sendError(Response $response, int $httpCode, string $message) {

        $response->getBody()->write(json_encode(["error" => $message]));

        return (
            $response
                ->withStatus($httpCode)
                ->withHeader("Content-Type", "application/json")
        );
    }

}

?>