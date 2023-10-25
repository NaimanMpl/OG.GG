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

$app->get('/matchHistory/save/{matchId}', ChampionController::class . ":update");

$app->get('/users/by-email', UserController::class . ":getUserByEmail");
$app->get('/users/by-name/{username}', UserController::class . ":getUserByName");
$app->get('/test/{summonerName}', SummonerController::class . ":getSummoner");

function getMostPlayedChamp(String $puuid){
    $apiKey=$_ENV['RIOT_API_KEY'];
    $apiLink="https://europe.api.riotgames.com/lol/match/v5/matches/by-puuid/".$puuid."/ids?start=0&count=5&api_key=".$apiKey;
    $matchHistoryContent=file_get_contents($apiLink);
    $matchesId=json_decode($matchHistoryContent,true);
    $championCount=array();
    $mostPlayedChamp=array("id"=>NULL,"count"=>0);
    for($i=0;$i<count($matchesId);$i++){
        $matchData=getMatchDataById($matchesId[$i]);
        for($j=0;$j<count($matchData["info"]["participants"]);$j++){
            if($matchData["info"]["participants"][$j]["puuid"]==$puuid){
                $championId=$matchData["info"]["participants"][$j]["championId"];
                if(isset($championCount[$championId])){
                    $championCount[$championId]++;
                }
                else{
                    $championCount[$championId]=1;
                }
                if($mostPlayedChamp["count"]<$championCount[$championId]){
                    $mostPlayedChamp["id"]=$championId;
                    $mostPlayedChamp["count"]=$championCount[$championId];
                }
            }
        }
    }
    return $mostPlayedChamp["id"];
}
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
    $match_count=1;
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
$app->get('/user/{name}', function (Request $request, Response $response, array $args) {
    $summonerData=getSummonerDataByName($args['name']);
    $apiKey=$_ENV['RIOT_API_KEY'];
    $ranked_url = "https://euw1.api.riotgames.com/lol/league/v4/entries/by-summoner/".$summonerData["id"]."?api_key=".$apiKey;
    $res2=file_get_contents($ranked_url);
    $rankedData=json_decode($res2,true);
    $rankedData["queues"]=null;
    if(isset($rankedData[0]["tier"])){
    $summonerData["queues"]["soloQRank"]["tier"]=$rankedData[0]["tier"];
    $summonerData["queues"]["soloQRank"]["rank"]=$rankedData[0]["rank"];
    $summonerData["queues"]["soloQRank"]["leaguePoints"]=$rankedData[0]["leaguePoints"];
    $summonerData["queues"]["soloQRank"]["wins"]=$rankedData[0]["wins"];
    $summonerData["queues"]["soloQRank"]["losses"]=$rankedData[0]["losses"];
    }
    if(isset($rankedData[1]["tier"])){
        $summonerData["queues"]["flexQRank"]["tier"]=$rankedData[1]["tier"];
        $summonerData["queues"]["flexQRank"]["rank"]=$rankedData[1]["rank"];
        $summonerData["queues"]["flexQRank"]["leaguePoints"]=$rankedData[1]["leaguePoints"];
        $summonerData["queues"]["flexQRank"]["wins"]=$rankedData[1]["wins"];
        $summonerData["queues"]["flexQRank"]["losses"]=$rankedData[1]["losses"];
        }
    $summonerData["mostPlayedChamp"]=getMostPlayedChamp($summonerData["puuid"]);
    $jsonsumdata=json_encode($summonerData);
    $response->getBody()->write($jsonsumdata);
    return $response->withHeader("Content-Type","application/json")->withStatus(200);
});

$app->run();
?>