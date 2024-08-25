<?php

namespace DatabaseNamespace;

use PDO;
use PDOException;

class Database
{
    private $host = "127.0.0.1:3306";
    private $db_name = "php_poo";
    private $username = "root";
    private $password = "XerecaAzeda123!";
    private $conn;

    public function connect()
    {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        return $this->conn;
    }
}
