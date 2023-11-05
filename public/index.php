<?php

require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;
use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\RegisterController;
use App\Controllers\ChampionController;
use App\Controllers\UserController;
use App\Controllers\SummonerController;
use Slim\Views\PhpRenderer;

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
$app->post('/user/login', LoginController::class . ":login");

$app->get('/register', RegisterController::class . ":render");
$app->post('/user/register', RegisterController::class . ":register");


$app->get('/users/by-email', UserController::class . ":getUserByEmail");
$app->get('/users/by-name/{username}', UserController::class . ":getUserByName");
$app->get('/summoners/{summonerName}', SummonerController::class . ":getSummoner");
$app->get('/summoners/register/{summonerName}', SummonerController::class . ":registerSummoner");
$app->get('/summoners/search/{summonerName}', SummonerController::class . ":search");
$app->get('/summoners/matchs/register/{matchId}', ChampionController::class . ":update");
$app->get('/summoners/matchs/updateLeaderboard', ChampionController::class . ":updateLeaderboard");

function getSummonerDataByName(String $name){
    $encodedName=urlencode($name);
    $apiKey= $_ENV['RIOT_API_KEY'];
    $summonerApiLink="https://euw1.api.riotgames.com/lol/summoner/v4/summoners/by-name/".$encodedName."?api_key=".$apiKey;
    $summonerApiContents=file_get_contents($summonerApiLink);
    return json_decode($summonerApiContents,true);
}
function getMatchHistoryByPuuid(String $puuid,int $start,int $count){
    $apiKey=$_ENV['RIOT_API_KEY'];
    $matchHistoryApiLink="https://europe.api.riotgames.com/lol/match/v5/matches/by-puuid/".$puuid."/ids?start=".$start."&count=".$count."&api_key=".$apiKey;
    $matchHistoryContents=file_get_contents($matchHistoryApiLink);
    return json_decode($matchHistoryContents,true);
}
function getMatchDataById(String $id){
    $apiKey=$_ENV['RIOT_API_KEY'];
    $matchApiLink="https://europe.api.riotgames.com/lol/match/v5/matches/".$id."?api_key=".$apiKey;
    $matchContents=file_get_contents($matchApiLink);
    return json_decode($matchContents,true);
}
function FilterMatchInfos(String $id,String $playerPuuid){
    $matchData=getMatchDataById($id);
    $res=array();
    $res['matchInfos']['duration']['minutes']=floor($matchData['info']['gameDuration']/60);
    $res['matchInfos']['duration']['seconds']=$matchData['info']['gameDuration']%60;
    return $res;


}
$app->get('/matchHistory/{name}', function (Request $request, Response $response, array $args){
    $match_count=5;
    $summonerData=getSummonerDataByName($args['name']);
    $matchHistoryIds=getMatchHistoryByPuuid($summonerData['puuid'],0,$match_count);
    $MatchHistory=array();
    for($i=0;$i<count($matchHistoryIds);$i++){
        array_push($MatchHistory,FilterMatchInfos($matchHistoryIds[$i],$summonerData['puuid']));
    }
    $MatchHistoryJson=json_encode($MatchHistory);
    $response->getBody()->write($MatchHistoryJson);
    return $response->withHeader("Content-Type","application/json")->withStatus(200);

});

$app->get('/summoner/{name}', function (Request $request, Response $response, array $args) {
    $renderer = new PhpRenderer('../views');
    return $renderer->render($response, 'summoner-page.php');
});

$app->run();
?>