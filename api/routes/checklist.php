<?php

    require_once 'models/checkModel.php';

    function handleCheck($metodo, $parametro, $pdo){
        switch($metodo){
            case 'GET':
                $check = $parametro ? checkModel::buscarPorId($pdo, $parametro) : checkModel::listarCheck($pdo);
                echo json_encode($check);
                
                break;
            
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                $sucesso = checkModel::criarCheck($data, $pdo);

                echo json_encode($sucesso);

                break;

            case 'PUT':

                $baixa = checkModel::darBaixa($pdo, $parametro);
                echo json_encode($baixa);
                break;

            case 'DELETE':
                http_response_code(400);
                echo json_encode([
                    "erro" => "requisição inadequada"
                ]);

                break;

        }
    }

?>