<?php

namespace App\Controllers;

use App\Models\Database;
use App\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;
use App\Controllers\Controller;

class LoginController extends Controller {

    public function render(Request $request, Response $response) {
        $renderer = new PhpRenderer('../views');
        $this->login(new User('', '', ''));
        return $renderer->render($response->withStatus(200), 'login.php');
    }

    public function login(User $user) {
        /*
        $db = new Database();
        $con = $db->connect();
        $query = "SELECT * FROM users";
        $sth = $con->prepare($query);
        $sth->execute();
        */
    }

}

?>