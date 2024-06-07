<?php

namespace App\Middlewares;

use App\Exceptions\SummonerNotFoundException;
use App\Models\Summoner;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\ErrorHandler;
use Slim\Psr7\Response as SlimResponse;
use Slim\Routing\RouteContext;

class FollowMiddleware {

    public function handleFollow(Request $request, RequestHandler $handler): Response {
        session_start();
        $response = new SlimResponse();
        $routeContext = RouteContext::fromRequest($request);
        $summonerName = $routeContext->getRoute()->getArgument('summonerName');
        $summonerTag = $routeContext->getRoute()->getArgument('tag');

        if (!isset($_SESSION["userId"]) || empty($_SESSION["userId"])) {
            $response->getBody()->write(json_encode(["error" => "Vous devez être connecté pour suivre un summoner !"]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            $summoner = new Summoner($summonerName, $summonerTag);

            $request = $request->withAttribute("summonerId", $summoner->getId());
            $request = $request->withAttribute("summonerPuuid", $summoner->getPuuid());
            $request = $request->withAttribute("summonerName", $summoner->getName());
            $request = $request->withAttribute("summonerTag", $summoner->getTag());

            return $handler->handle($request);
        } catch (SummonerNotFoundException $e) {
            return ErrorHandler::sendError($response, 404, $e->getMessage());
        }
    }

}

?>