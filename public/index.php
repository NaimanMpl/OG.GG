<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\ChatController;
use App\Controllers\MatchController;
use App\Middlewares\CaptchaMiddleware;
use App\Middlewares\ChatMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Ratchet\Server\IoServer;
use Slim\Factory\AppFactory;
use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\RegisterController;
use App\Controllers\ChampionController;
use App\Controllers\UserController;
use App\Controllers\SummonerController;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\FollowMiddleware;
use App\Models\Chat;
use Slim\Routing\RouteCollectorProxy;
use Slim\Views\PhpRenderer;
use App\Controllers\PageController;

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setErrorHandler(
    Slim\Exception\HttpNotFoundException::class,
    function (Request $request, Throwable $exception, bool $displayErrorDetails) {
        $response = new \Slim\Psr7\Response();
        $renderer = new PhpRenderer('../views');
        return $renderer->render($response->withStatus(404), 'notfound.php');
    }
);

$app->get('/', HomeController::class . ":render");
$app->get('/login', LoginController::class . ":render");
$app->get('/logout', UserController::class . ":logout");
$app->post('/user/login', UserController::class . ":login")->add(AuthMiddleware::class . ":handleLogin");

$app->get('/verify', UserController::class . ":activateAccount")->add(AuthMiddleware::class . ":verifyToken");
$app->get('/success', RegisterController::class . ":handleAccountCreation");

$app->get('/register', RegisterController::class . ":render");
$app->post('/user/register', UserController::class . ":register")->add(AuthMiddleware::class . ":handleRegister")->add(CaptchaMiddleware::class . ":verifyCaptcha");
$app->get('/users/by-email', UserController::class . ":getUserByEmail");
$app->get('/users/by-name/{username}', UserController::class . ":getUserByName");
$app->get('/user/follow/{summonerName}', UserController::class . ":followSummoner")->add(FollowMiddleware::class . ":handleFollow");
$app->get('/user/{userId}/followers', UserController::class . ":getFollowers");
$app->get('/user/me', UserController::class . ":getUser")->add(AuthMiddleware::class . ":handleAuth");

$app->get('/summoners/{summonerName}', SummonerController::class . ":getSummoner");
$app->get('/summoners/register/{summonerName}', SummonerController::class . ":registerSummoner");
$app->get('/summoners/search/{summonerName}', SummonerController::class . ":search");
$app->get('/summoners/matchs/register/{matchId}', ChampionController::class . ":update");
$app->get('/champions/updateleaderboard', ChampionController::class . ":updateLeaderboard");
$app->get('/summoner/{name}', SummonerController::class . ":render");

$app->get('/leaderboard', ChampionController::class . ":render");
$app->get('/champions/leaderboard', ChampionController::class . ':getLeaderboard');
$app->get('/matches/{matchId}', MatchController::class . ':getMatch');
$app->get('/followers', PageController::class . ":renderFollowers");

$app->group('/chat', function (RouteCollectorProxy $group) {
    $group->get('', PageController::class . ":renderChat");
    $group->get('/iron', PageController::class . ":renderChat");
    $group->get('/bronze', PageController::class . ":renderChat");
    $group->get('/silver', PageController::class . ":renderChat");
    $group->get('/gold', PageController::class . ":renderChat");
    $group->get('/platinium', PageController::class . ":renderChat");
    $group->get('/emerald', PageController::class . ":renderChat");
    $group->get('/diamond', PageController::class . ":renderChat");
    $group->get('/master', PageController::class . ":renderChat");
    $group->get('/grandmaster', PageController::class . ":renderChat");
    $group->get('/challenger', PageController::class . ":renderChat");

    $group->post('/send', ChatController::class . ":sendMessage")->add(ChatMiddleware::class . ":handleMessagePost");
});

$app->get('/riot.txt', function (Request $request, Response $response) {
    $path = "/riot.txt";

    if (!file_exists($path)) {
        return $response->withStatus(404);
    }

    $file = file_get_contents($path);

    $response->getBody()->write($file);

    return $response->withStatus(200);
});

$app->get('/robots.txt', function (Request $request, Response $response) {
    $path = "/robots.txt";

    if (!file_exists($path)) {
        return $response->withStatus(404);
    }

    $file = file_get_contents($path);

    $response->getBody()->write($file);

    return $response->withStatus(200);
});

$app->run();
?>