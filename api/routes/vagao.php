<?php

    require_once 'models/vagaoModel.php';

    function handleVagao($metodo, $parametro, $pdo){
        switch($metodo){
            case 'GET':
                $vagao = $parametro ? vagaoModel::buscarPorId($pdo, $parametro) : vagaoModel::listarVagao($pdo);
                if($vagao){
                    echo json_encode($vagao);
                }else {
                    http_response_code(404);
                    echo json_encode([
                        "erro" => "vagao não encontrado"
                    ]);
                }
                break;
            
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                $sucesso = vagaoModel::criarVagao($data, $pdo);

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
                $sucesso = vagaoModel::editarVagao($data, $parametro, $pdo);

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

                $sucesso = vagaoModel::excluirVagao($parametro, $pdo);

                echo json_encode($sucesso);
                break;

        }
    }

?>