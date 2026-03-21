<?php

    require_once 'models/transportadoraModel.php';

    function handleTransportadora($metodo, $parametro, $pdo){
        switch($metodo){
            case 'GET':
                $transportadora = $parametro ? transportadoraModel::buscarPorId($pdo, $parametro) : transportadoraModel::listarTransportadora($pdo);

                echo json_encode($transportadora);
                
                break;
            
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                $sucesso = transportadoraModel::criarTransportadora($data, $pdo);

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
                $sucesso = transportadoraModel::editarTransportadora($data, $parametro, $pdo);

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

                $sucesso = transportadoraModel::excluirTransportadora($parametro, $pdo);

                echo json_encode($sucesso);
                break;

        }
    }

?>