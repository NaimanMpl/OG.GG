<?php

namespace App\Controllers;

use App\ErrorHandler;
use App\Exceptions\DatabaseException;
use App\Models\Database;
use App\Models\Mailer;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Exception;

class UserController {

    public function getUserByEmail(Request $request, Response $response) {
        $database = new Database();
        $con = $database->getConnection();
        $query = "SELECT email FROM users WHERE email=?";
        $stmt = $con->prepare($query);
        $stmt->execute(array(strtolower(urldecode($request->getQueryParams()['email']))));
        
        if ($stmt->rowCount() > 0) {
            $response->getBody()->write(json_encode(["email" => $stmt->fetch()["email"]]));
        } else {
            $response->getBody()->write(json_encode([]));
        }

        return (
            $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json')
        );
    }

    public function getUserByName(Request $request, Response $response, array $args) {
        $database = new Database();
        $con = $database->getConnection();
        $query = "SELECT username FROM users WHERE username=?";
        $stmt = $con->prepare($query);
        $stmt->execute(array(urldecode($args['username'])));
        
        if ($stmt->rowCount() > 0) {
            $response->getBody()->write(json_encode(["username" => $stmt->fetch()["username"]]));
        } else {
            $response->getBody()->write(json_encode([]));
        }

        return (
            $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json')
        );
    }

    public function findUniqueUser(Database $database, string $email, string $password): array {
        $conn = $database->getConnection();
        $query = "SELECT users.id, email, username, password, created_at, profilepicture, picture FROM users JOIN profilepictures ON users.profilepicture = profilepictures.id WHERE email=?";
        $stmt = $conn->prepare($query);
        $stmt->execute(array($email));
        $user = $stmt->fetch();
        if ($stmt->rowCount() == 0 || !password_verify($password, $user["password"])) {
            return [];
        } 
        return $user;
    }

    public function getFollowers(Request $request, Response $response, array $args) {
        try {
            $database = new Database();
            $conn = $database->getConnection();
            $query = "SELECT summoner_puuid, summoner_id, summoner_name FROM follows WHERE user_id=?";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($args["userId"]));

            $response->getBody()->write(json_encode([ "results" => $stmt->fetchAll() ]));

            return $response->withStatus(200)->withHeader("Content-Type", "application/json");
        } catch (PDOException $e) {
            return ErrorHandler::handleDatabaseError($e, $response, 500, "Le serveur a rencontré un problème, veuillez réessayer plus tard.");
        }
    }

    public function logout(Request $request, Response $response) {
        session_start();
        setcookie(session_name(), '', 100);
        session_unset();
        session_destroy();
        $_SESSION = array();
        return $response->withStatus(301)->withHeader('Location', '/');
    }

    private function signup(string $email, string $username, string $password) {
        $database = new Database();
        try {
            $con = $database->getConnection();
            $query = "INSERT INTO users(email, username, password) VALUES(?, ?, ?)";
            $stmt = $con->prepare($query);
            $stmt->execute(array($email, $username, password_hash($password, PASSWORD_DEFAULT)));
            
            $token = bin2hex(random_bytes(16));

            $query = "INSERT INTO tokens(email, token) VALUES (?, ?)";
            $stmt = $con->prepare($query);
            $stmt->execute(array($email, $token));
            
            $mailer = new Mailer();
            $mailer->sendMail($email, "Confirmation de votre adresse e-mail", 'confirm-mail-template.php', [ "token" => $token ]);

        } catch (PDOException $e) {
            throw new DatabaseException($e->errorInfo[1], "La connexion à la base de données à échoué.");
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function login(Request $request, Response $response) {
        session_start();

        $_SESSION["userId"] = $request->getAttribute("userId");
        $_SESSION["username"] = $request->getAttribute("username");
        $_SESSION["email"] = $request->getAttribute("email");
        $_SESSION["profilepicture"] = $request->getAttribute("profilepicture");

        $response->getBody()->write(json_encode(["success" => true]));

        return ($response
            ->withStatus(301)
            ->withHeader('Location', '/')
        );
    }

    public function register(Request $request, Response $response) {
        try {
            $this->signup(strtolower($request->getAttribute("email")), $request->getAttribute("username"), $request->getAttribute("password"));
            $response->getBody()->write(json_encode(["message" => "Un email de confirmation vous a été envoyé.", "success" => true]));
        } catch (DatabaseException $e) {

            if ($e->getErrorCode() === 1062) {
                return ErrorHandler::sendError($response, 512, "Un utilisateur avec cette adresse mail existe déjà !");    
            }

            return ErrorHandler::sendError($response, 500, "Le serveur a rencontré un problème, veuillez réessayer plus tard");    
        } catch (Exception $e) {
            return ErrorHandler::sendError($response, 500, "Le serveur a rencontré un problème, veuillez réessayer plus tard");    
        }

        return ($response
            ->withStatus(200)
            ->withHeader("Content-Type", "application/json")
        );
    }

    public function followSummoner(Request $request, Response $response) {
        $summonerId = $request->getAttribute('summonerId');
        $summonerPuuid = $request->getAttribute('summonerPuuid');
        $summonerName = $request->getAttribute('summonerName');
        $summonerTag = $request->getAttribute('summonerTag');
        $userId = $_SESSION["userId"];
        try {
            $database = new Database();
            $conn = $database->getConnection();
            $query = "INSERT INTO follows(user_id, summoner_id, summoner_puuid, summoner_name, summoner_tag) VALUES(?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($userId, $summonerId, $summonerPuuid, $summonerName, $summonerTag));

            $response->getBody()->write(json_encode(["success" => true]));

            return (
                $response
                    ->withStatus(200)
                    ->withHeader("Content-Type", 'Application/json')
            );
        } catch (PDOException $e) {
            if ($e->errorInfo[1] === 1062) {
                return ErrorHandler::handleDatabaseError($e, $response, 400, "Cet utilisateur suit déjà ce summoner !");
            }

            return ErrorHandler::handleDatabaseError($e, $response, 500, "Le serveur a rencontré un problème, veuillez réessayer plus tard.");
        }
    }

    public function validAccount(Database $database, string $email): bool {
        $conn = $database->getConnection();
        $query = "SELECT email FROM tokens WHERE email=?";
        $stmt = $conn->prepare($query);
        $stmt->execute(array($email));

        return $stmt->rowCount() == 0;
    }

    public function activateAccount(Request $request, Response $response) {
        $email = $request->getAttribute("email");
        try {
            $database = new Database();

            $conn = $database->getConnection();
            $query = "DELETE FROM tokens WHERE email=?";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($email));

            $query = "SELECT username FROM users WHERE email=?";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($email));
            
            $username = $stmt->fetch()["username"];

            return $response->withStatus(301)->withHeader("Location", "/success?username=".urlencode($username));
        } catch (PDOException $e) {
            return ErrorHandler::sendError($response, 500, "Le serveur a rencontré un problème, veuillez réessayer plus tard.");
        }
    }

    public function getUser(Request $request, Response $response) {

        try {
            $database = new Database();
            $conn = $database->getConnection();
            $query = "SELECT summoner_name, profileiconid FROM follows JOIN summoners ON follows.summoner_name=summoners.name WHERE user_id=?";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($_SESSION["userId"]));

            $results = $stmt->fetchAll();
            $followers = [];

            foreach ($results as $follower) {
                $followers[] = ["summonerName" => $follower['summoner_name'], "profileIconId" => $follower['profileiconid']];
            }

            $user = [
                "userId" => $_SESSION["userId"],
                "email" => $_SESSION["email"],
                "username" => $_SESSION["username"],
                "followers" => $followers,
                "profilepicture" => $_SESSION["profilepicture"]
            ];

            $response->getBody()->write(json_encode($user));
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            return ErrorHandler::handleDatabaseError($e, $response, 500, "Le serveur a rencontré un problème, veuillez réessayer plus tard.");
        }
    }

    public function sendPost(Request $request, Response $response) {
        $data = json_decode($request->getBody(), true);
        try {
            $database = new Database();
            $conn = $database->getConnection();
            $query = "INSERT INTO posts(user_id, summoner_name, summoner_tag, message) VALUES(?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($_SESSION["userId"], $data["summonerName"], $data["summonerTag"], $data["message"]));
    
            $response->getBody()->write(json_encode([ "success" => true ]));
            return $response->withStatus(200)->withHeader("Content-Type", "application/json");
        } catch (PDOException $e) {

            if ($e->errorInfo[1] === 1452) {
                return ErrorHandler::sendError($response, 400, "Aucun utilisateur avec cet identifiant existe !");
            }

            $response->getBody()->write($e->getMessage());

            return ErrorHandler::sendError($response, 500, "Le serveur a rencontré un problème");
        }
    }

    public function getProfilePictures(Request $request, Response $response) {
        try {
            $database = new Database();
            $conn = $database->getConnection();
            $query = "SELECT * FROM profilepictures";
            $stmt = $conn->prepare($query);
            $stmt->execute();

            $results = $stmt->fetchAll();
            $profilepictures = [];

            foreach ($results as $profilepicture) {
                $profilepictures[] = ["id" => $profilepicture["id"], "image" => base64_encode($profilepicture["picture"])];
            }

            $response->getBody()->write(json_encode($profilepictures));

            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            return ErrorHandler::sendError($response, 500, "Le serveur a rencontré un problème.");
        }
    }

    public function updateUser(Request $request, Response $response) {
        $userData = json_decode($request->getBody(), true);
        $method = $request->getAttribute("method");
        try {
            $database = new Database();
            $conn = $database->getConnection();
            if ($method === "both" || $method == "profilepicture") {
                $query = "UPDATE users SET profilepicture=? WHERE id=?";
                $stmt = $conn->prepare($query);
                $stmt->execute(array($userData["profilePicture"], $_SESSION["userId"]));
    
                $query = "SELECT picture FROM profilepictures WHERE id=?";
                $stmt = $conn->prepare($query);
                $stmt->execute(array($userData["profilePicture"]));

                $result = $stmt->fetch();
                $_SESSION['profilepicture'] = base64_encode($result["picture"]);

            }

            if ($method === "both" || $method == "password") {
                // Traitement
            }

            $response->getBody()->write(json_encode(["message" => "Profil mis à jour avec succès.", "success" => true]));
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {

            if ($e->errorInfo[1] === 1054) {
                $response->getBody()->write($e->getMessage());
                return ErrorHandler::sendError($response, 400, "La photo de profil renseigné n'existe pas !");
            }

            return ErrorHandler::sendError($response, 500, "Le serveur a rencontré un problème.");
        } catch (Exception $e) {
            return ErrorHandler::sendError($response, 500, "Le serveur a rencontré un problème ou la photo de profil n'existe pas.");
        }
    }
    
}

?>