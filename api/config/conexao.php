<?php

    $host = "localhost";
    $user = "root";
    $db = "mydb";
    $password = "";

    
    try{
        $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $password,

        [
            PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    );

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $_e){
        http_response_code(500);
        echo json_encode([
            "erro" => "Erro de conexão à base de dados"
        ]);
        exit;
    }
    


?>