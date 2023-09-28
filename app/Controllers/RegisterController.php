<?php

namespace App\Controllers;

use App\Controllers\Controller;
use Exception;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Database;

class RegisterController extends Controller {

    public function render(Request $request, Response $response) {
        $renderer = new PhpRenderer('../views');
        return $renderer->render($response->withStatus(200), 'register.php');
    }

    private function signup(string $email, string $username, string $password) {
        $database = new Database();
        try {
            $con = $database->connect();
            $query = "INSERT INTO users(email, username, password) VALUES(?, ?, ?)";
            $sth = $con->prepare($query);
            $sth->execute(array($email, $username, password_hash($password, PASSWORD_DEFAULT)));
        } catch (Exception $e) {
            throw new Exception("La connexion à la base de données à échoué.");
        }
    }

    public function register(Request $request, Response $response) {
        $userData = json_decode($request->getBody(), true);

        $HTTP_STATUS_CODE = 200;
        if (!isset($userData['username']) || empty($userData['username'])) {
            $HTTP_STATUS_CODE = 400;      
            $response->getBody()->write(json_encode(["error" => "Veuillez renseigner un nom d'utilisateur"]));

            return ($response
                ->withStatus($HTTP_STATUS_CODE)
                ->withHeader('Content-Type', 'application/json')
            );
        } else if (!isset($userData['email']) || empty($userData['email'])) {
            $HTTP_STATUS_CODE = 400;     
            $response->getBody()->write(json_encode(["error" => "Veuillez renseigner une adresse email !"]));

            return ($response
                ->withStatus($HTTP_STATUS_CODE)
                ->withHeader('Content-Type', 'application/json')
            );
        } else if (!isset($userData['password']) || empty($userData['password'])) {
            $HTTP_STATUS_CODE = 400;
            $response->getBody()->write(json_encode(["error" => "Veuillez renseigner un mot de passe !"]));

            return ($response
                ->withStatus($HTTP_STATUS_CODE)
                ->withHeader('Content-Type', 'application/json')
            );
        }

        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            $HTTP_STATUS_CODE = 400;
            $response->getBody()->write(json_encode(["error" => "Veuillez renseigner une adresse email valide !"]));

            return ($response
                ->withStatus($HTTP_STATUS_CODE)
                ->withHeader('Content-Type', 'application/json')
            );
        }

        if (strlen($userData['password']) < 8) {
            $HTTP_STATUS_CODE = 400;
            $response->getBody()->write(json_encode(["error" => "Le mot de passe doit avoir au moins 8 caractères !"]));

            return ($response
                ->withStatus($HTTP_STATUS_CODE)
                ->withHeader('Content-Type', 'application/json')
            );
        }

        try {
            $this->signup($userData['email'], $userData['username'], $userData['password']);
            $response->getBody()->write(json_encode(["success" => true]));
        } catch (Exception $e) {
            $HTTP_STATUS_CODE = 500;
            $response->getBody()->write(json_encode(["error" => "Le serveur a rencontré un problème, veuillez réessayer plus tard"]));
        }

        return ($response
            ->withStatus($HTTP_STATUS_CODE)
            ->withHeader('Content-Type', 'application/json')
        );
    }

}
?>