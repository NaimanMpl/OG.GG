<?php

namespace App\Controllers;

use App\Models\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDOException;
use App\ErrorHandler;

class PostController {

    public function getPosts(Request $request, Response $response, array $args) {
        $summonerName = $args['summonerName'];
        try {
            $database = new Database();
            $conn = $database->getConnection();
            $query = "SELECT posts.id, picture, posts.user_id, users.username, summoner_name, message, date FROM posts JOIN users ON users.id=posts.user_id JOIN profilepictures ON users.profilepicture=profilepictures.id WHERE summoner_name=? ORDER BY posts.date DESC";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($summonerName));

            $results = $stmt->fetchAll();
            $posts = [];
            foreach($results as $post) {
                $posts[] = [
                    "id" => $post["id"],
                    "username" => $post["username"],
                    "summonerName" => $post["summoner_name"],
                    "message" => $post["message"],
                    "date" => $post["date"],
                    "profilepicture" => base64_encode($post["picture"])
                ];
            }

            $response->getBody()->write(json_encode($posts));
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            return ErrorHandler::sendError($response, 500, "Le serveur a rencontré un problème.");
        }
    }

}

?>