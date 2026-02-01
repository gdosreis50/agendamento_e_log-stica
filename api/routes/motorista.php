<?php

    require_once 'models/motoristaModel.php';

    function handleMotorista($metodo, $parametro, $pdo){
        switch($metodo){
            case 'GET':
                $motorista = $parametro ? motoristaModel::buscarPorId($pdo, $parametro) : motoristaModel::listarMotorista($pdo);
                if($motorista){
                    echo json_encode($motorista);
                }else {
                    http_response_code(404);
                    echo json_encode([
                        "erro" => "Motorista não encontrado"
                    ]);
                }
                break;
            
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                $sucesso = motoristaModel::criarMotorista($data, $pdo);

                echo json_encode($sucesso);

                break;

            case 'PUT':
                if(!$parametro){
                    http_response_code(400);
                    echo json_encode([
                        "erro" => "requisição sem parâmetros"
                    ]);

                    break;
                }

                $data = json_decode(file_get_contents("php://input"), true);
                $sucesso = motoristaModel::editarMotorista($data, $parametro, $pdo);

                echo json_encode($sucesso);
                break;

            case 'DELETE':
                if(!$parametro){
                    http_response_code(400);
                    echo json_encode([
                        "erro" => "requisição sem parâmetros"
                    ]);

                    break;
                }

                $sucesso = motoristaModel::excluirMotorista($parametro, $pdo);

                echo json_encode($sucesso);
                break;

        }
    }

?>