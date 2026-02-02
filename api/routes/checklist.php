<?php

    require_once 'models/checkModel.php';

    function handleCheck($metodo, $parametro, $pdo){
        switch($metodo){
            case 'GET':
                $check = $parametro ? checkModel::buscarPorId($pdo, $parametro) : checkModel::listarCheck($pdo);
                if($check){
                    echo json_encode($check);
                }else {
                    http_response_code(404);
                    echo json_encode([
                        "erro" => "checklist não encontrado"
                    ]);
                }
                break;
            
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                $sucesso = checkModel::criarCheck($data, $pdo);

                echo json_encode($sucesso);

                break;

            case 'PUT':
                http_response_code(400);
                echo json_encode([
                    "erro" => "requisição inadequada"
                ]);

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