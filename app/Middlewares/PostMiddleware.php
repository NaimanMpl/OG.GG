<?php

namespace App\Middlewares;

use App\ErrorHandler;
use App\Middlewares;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class PostMiddleware {

    public function handlePost(Request $request, RequestHandler $handler) {
        $postData = json_decode($request->getBody(), true);
        
        $response = new Response();
        if (!isset($postData["summonerName"]) || empty($postData["summonerName"])) {
            return ErrorHandler::sendError($response, 400, "Veuillez saisir un nom d'invocateur");
        }

        if (!isset($postData["message"]) || empty($postData["message"])) {
            return ErrorHandler::sendError($response, 400, "Veuillez saisir un message !");
        }

        if (strlen($postData["message"]) > 100) {
            return ErrorHandler::sendError($response, 400, "La taille du message ne peut dépasser 100 caractères !");
        }

        return $handler->handle($request);
    }

}

?>