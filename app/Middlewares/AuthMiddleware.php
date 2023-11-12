<?php

namespace App\Middlewares;

use App\Controllers\UserController;
use App\Models\Database;
use PDOException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\ErrorHandler;
use Slim\Psr7\Response as SlimResponse;

class AuthMiddleware {

    public function handleLogin(Request $request, RequestHandler $handler): Response {
        $userData = json_decode($request->getBody(), true);
        $response = new SlimResponse();

        if (!isset($userData["email"]) || empty($userData["email"]) || !isset($userData["password"]) | empty($userData["password"])) {
            return ErrorHandler::sendError($response, 400, "Identifiant ou mot de passe incorrect.");
        }

        if (!filter_var($userData["email"], FILTER_VALIDATE_EMAIL)) {
            return ErrorHandler::sendError($response, 400, "Identifiant ou mot de passe incorrect.");
        }
        
        $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/';

        if (!preg_match($passwordRegex, $userData['password'])) {
            return ErrorHandler::sendError($response, 400, "Identifiant ou mot de passe incorrect.");    
        }

        try {
            $database = new Database();
            $controller = new UserController();
            $user = $controller->findUniqueUser($database, $userData["email"], $userData["password"]);

            if (empty($user)) {
                return ErrorHandler::sendError($response, 400, "Identifiant ou mot de passe incorrect.");
            }

            $request = $request->withAttribute("userId", $user["id"]);
            $request = $request->withAttribute("username", $user["username"]);
            $request = $request->withAttribute("email", $user["email"]);

            return $handler->handle($request);
        } catch (PDOException $e) {
            return ErrorHandler::handleDatabaseError($e, $response, 500, "Le serveur a rencontré un problème, veuillez réessayer plus tard.");
        }
    }

    public function handleRegister(Request $request, RequestHandler $handler): Response {
        $userData = json_decode($request->getBody(), true);
        $response = new SlimResponse();

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

        $request = $request->withAttribute("email", $userData["email"]);
        $request = $request->withAttribute("username", $userData["username"]);
        $request = $request->withAttribute("password", $userData["password"]);

        return $handler->handle($request);
    }

};

?>