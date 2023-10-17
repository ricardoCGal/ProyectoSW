<?php
    $server = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'php_login';

    try {
        $conn = new PDO("mysql:host=$server;dbname=$database;", $username, $password);
    } catch(PDOException $e){
        die('Conneced failed: '.$e->getMessage());
    }

?>