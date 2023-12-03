<?php

namespace App\Middlewares;
use App\Models\Channel;
use Exception;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ChatMiddleware {

    public function handleMessagePost(Request $request, RequestHandler $handler) {
        $response = new Response();
        $data = json_decode($request->getBody(), true);

        if (!isset($data["userId"]) || empty($data["userId"])) {
            $response->getBody()->write(json_encode([ "error" => "Veuillez saisir un identifiant d'utilisateur !"]));
            return $response->withStatus(400)->withHeader("Content-Type", "application/json");
        }

        if (!isset($data["message"]) || empty($data["message"])) {
            $response->getBody()->write(json_encode([ "error" => "Veuillez saisir un message !"]));
            return $response->withStatus(400)->withHeader("Content-Type", "application/json");
        }

        if (!isset($data["channel"]) || empty($data["channel"])) {
            $response->getBody()->write(json_encode([ "error" => "Veuillez saisir un canal de discussion !"]));
            return $response->withStatus(400)->withHeader("Content-Type", "application/json");
        }


        if (filter_var($data["channel"], FILTER_VALIDATE_INT) === false) {
            $response->getBody()->write(json_encode(["error" => "Veuillez saisir un identifiant de channel valide (int) !"]));
            return $response->withStatus(400)->withHeader("Content-Type", "application/json");
        }

        if (!Channel::contains(intval($data["channel"]))) {
            $response->getBody()->write(json_encode(["error" => "Veuillez saisir un identifiant de channel valide !"]));
            return $response->withStatus(400)->withHeader("Content-Type", "application/json");
        }


        if (strlen($data["message"]) > 144) {
            $response->getBody()->write(json_encode(["error" => "Le message ne peut dépasser 144 caractères !"]));
            return $response->withStatus(400)->withHeader("Content-Type", "application/json");
        }

        return $handler->handle($request);

    }

}

?>