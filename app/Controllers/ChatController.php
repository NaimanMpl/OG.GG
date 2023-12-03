<?php

namespace App\Controllers;
use App\ErrorHandler;
use App\Models\Database;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
class ChatController {

    public function sendMessage(Request $request, Response $response) {
        $data = json_decode($request->getBody(), true);
        try {
            $database = new Database();
            $conn = $database->getConnection();
            $query = "INSERT INTO messages(user_id, content, channel) VALUES(?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($data["userId"], $data["message"], $data["channel"]));
            $message = $stmt->fetch();
    
            $response->getBody()->write(json_encode([ "success" => true ]));
            return $response->withStatus(200)->withHeader("Content-Type", "application/json");
        } catch (PDOException $e) {

            if ($e->errorInfo[1] === 1452) {
                return ErrorHandler::sendError($response, 400, "Aucun utilisateur avec cet identifiant existe !");
            }

            return ErrorHandler::sendError($response, 500, "Le serveur a rencontré un problème");
        }
    }

}

?>