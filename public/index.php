<?php

require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response) {
    return $response;
});

$app->get('/summoner/{name}', function (Request $request, Response $response, array $args) {
    $name = urlencode($args['name']); 
    $apiKey = $_ENV['RIOT_API_KEY'];
    $apiLink = "https://euw1.api.riotgames.com/lol/summoner/v4/summoners/by-name/".$name."?api_key=".$apiKey;
    $res = file_get_contents($apiLink);
    $summonerData = json_decode($res, true);
    ob_start();
    include 'views/index.php';
    $response->getBody()->write(ob_get_clean());
    return $response;
});

$app->run();

?>