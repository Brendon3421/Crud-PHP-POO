<?php
require_once 'database.class.php';
$database = new Database();
$conn = $database->connect();

if ($conn) {
    echo "Conexão bem-sucedida!";
} else {
    echo "Falha na conexão.";
}
