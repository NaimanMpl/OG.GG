<?php

namespace App\Controllers;

use App\ErrorHandler;
use App\Exceptions\DatabaseException;
use App\Models\Database;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Exception;

class UserController {

    public function getUserByEmail(Request $request, Response $response) {
        $database = new Database();
        $con = $database->getConnection();
        $query = "SELECT email FROM users WHERE email=?";
        $stmt = $con->prepare($query);
        $stmt->execute(array(strtolower(urldecode($request->getQueryParams()['email']))));
        
        if ($stmt->rowCount() > 0) {
            $response->getBody()->write(json_encode(["email" => $stmt->fetch()["email"]]));
        } else {
            $response->getBody()->write(json_encode([]));
        }

        return (
            $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json')
        );
    }

    public function getUserByName(Request $request, Response $response, array $args) {
        $database = new Database();
        $con = $database->getConnection();
        $query = "SELECT username FROM users WHERE username=?";
        $stmt = $con->prepare($query);
        $stmt->execute(array(urldecode($args['username'])));
        
        if ($stmt->rowCount() > 0) {
            $response->getBody()->write(json_encode(["username" => $stmt->fetch()["username"]]));
        } else {
            $response->getBody()->write(json_encode([]));
        }

        return (
            $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json')
        );
    }

    public function logout(Request $request, Response $response) {
        session_start();
        setcookie(session_name(), '', 100);
        session_unset();
        session_destroy();
        $_SESSION = array();
        return $response->withStatus(301)->withHeader('Location', '/');
    }

    private function signup(string $email, string $username, string $password) {
        $database = new Database();
        try {
            $con = $database->getConnection();
            $query = "INSERT INTO users(email, username, password) VALUES(?, ?, ?)";
            $sth = $con->prepare($query);
            $sth->execute(array($email, $username, password_hash($password, PASSWORD_DEFAULT)));
        } catch (PDOException $e) {
            throw new DatabaseException($e->errorInfo[1], "La connexion à la base de données à échoué.");
        }
    }

    public function register(Request $request, Response $response) {
        $userData = json_decode($request->getBody(), true);

        if (!isset($userData['username']) || empty($userData['username'])) {
            return ErrorHandler::sendError($response, 400, "Veuillez renseigner un nom d'utilisateur");    
        } else if (!isset($userData['email']) || empty($userData['email'])) {
            return ErrorHandler::sendError($response, 400, "Veuillez renseigner une adresse email !");    
        } else if (!isset($userData['password']) || empty($userData['password'])) {
            return ErrorHandler::sendError($response, 400, "Veuillez renseigner un mot de passe !");    
        } else if (!isset($userData["confirmPassword"]) || empty($userData["confirmPassword"])) {
            return ErrorHandler::sendError($response, 400, "Veuillez renseigner un mot de passe de confirmation !");    
        }

        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            return ErrorHandler::sendError($response, 452, "Veuillez renseigner une adresse email valide !");    
        }

        $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/';

        if (!preg_match($passwordRegex, $userData['password'])) {
            return ErrorHandler::sendError($response, 453, "Le mot de passe doit contenir au moins 8 caractères, une majuscule et un caractère spécial !");    
        }

        if ($userData["password"] !== $userData["confirmPassword"]) {
            return ErrorHandler::sendError($response, 454, "Les 2 mots de passes doivent être identiques !");    
        }

        try {
            $this->signup(strtolower($userData['email']), $userData['username'], $userData['password']);
            $response->getBody()->write(json_encode(["success" => true]));
        } catch (DatabaseException $e) {

            if ($e->getErrorCode() === 1062) {
                return ErrorHandler::sendError($response, 512, "Un utilisateur avec cette adresse mail existe déjà !");    
            }

            return ErrorHandler::sendError($response, 500, "Le serveur a rencontré un problème, veuillez réessayer plus tard");    
        }

        return ($response
            ->withStatus(301)
            ->withHeader('Location', '/login')
        );
    }
}

?>