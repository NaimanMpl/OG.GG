<?php

namespace App\Models;

use \PDO;

class Database {

    public function connect(): PDO {
        $conn_str = "mysql:host=".$_ENV['DB_HOST'].";dbname=".$_ENV['DB_NAME'].";port=".$_ENV['DB_PORT'];
        $conn = new PDO($conn_str, $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
    
}

?>