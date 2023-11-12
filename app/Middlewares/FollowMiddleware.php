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

        if (!isset($_SESSION["userId"]) || empty($_SESSION["userId"])) {
            return $response->withStatus(301)->withHeader('Location', '/');
        }

        try {
            $summoner = new Summoner($summonerName);

            $request = $request->withAttribute("summonerId", $summoner->getId());
            $request = $request->withAttribute("summonerPuuid", $summoner->getPuuid());
            $request = $request->withAttribute("summonerName", $summoner->getName());

            return $handler->handle($request);
        } catch (SummonerNotFoundException $e) {
            return ErrorHandler::sendError($response, 404, $e->getMessage());
        }
    }

}

?>