<?php

namespace App\Middlewares;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class CaptchaMiddleware {

    public function verifyCaptcha(Request $request, RequestHandler $handler) {

        $response = new Response();
        $data = json_decode($request->getBody(), true);

        if (!isset($data["captcha"]) || empty($data["captcha"])) {
            $response->getBody()->write(json_encode(["error" => "Veuillez remplir le captcha !"]));
            return $response->withStatus(400)->withHeader("Content-Type", "application/json");
        }

        $options = [
            "http" => [
                "header" => "Content-Type: application/x-www-form-urlencoded\r\n",
                "method" => 'POST',
                "content" => http_build_query(["secret" => $_ENV['CAPTCHA_SECRET_KEY'], "response" => $data["captcha"]]),
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents("https://www.google.com/recaptcha/api/siteverify", false, $context);
        
        if ($result === false) {
            $response->getBody()->write(json_encode(["error" => "Le serveur de captcha a rencontré un problème."]));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        $responseData = json_decode($result, true);
        if ($responseData["success"] === false) {
            $response->getBody()->write(json_encode(["error" => "Veuillez valider le CAPTCHA."]));
            return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
        }

        return $handler->handle($request);
    }

}

?>