<?php

namespace App\Middlewares;

use App\ErrorHandler;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class UserMiddleware {

    public function handleUpdate(Request $request, RequestHandler $handler) {
        $response = new Response();
        $updateData = json_decode($request->getBody(), true);

        if (isset($updateData["profilePicture"]) && isset($updateData["password"])) {
            $request = $request->withAttribute("method", "both");
            return $handler->handle($request);
        }

        if (!isset($updateData["profilePicture"]) || empty($updateData["profilePicture"])) {
            if (!isset($updateData["password"]) || empty($updateData["password"])) {
                return ErrorHandler::sendError($response, 500, "Veuillez renseigner une photo de profil ou un mot de passe");
            } 
            
            $request = $request->withAttribute("method", "password");
            return $handler->handle($request);
        }

        if (!isset($updateData["password"]) || empty($updateData["password"])) {
            if (isset($updateData["profilePicture"]) && !empty($updateData["profilePicture"])) {
            $request = $request->withAttribute("method", "profilepicture");
                return $handler->handle($request);
            } 
            
            return ErrorHandler::sendError($response, 500, "Veuillez renseigner une photo de profil ou un mot de passe");
        }
        
        return ErrorHandler::sendError($response, 500, "Veuillez renseigner une photo de profil ou un mot de passe");

    }

}

?>