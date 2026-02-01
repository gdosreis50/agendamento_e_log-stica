<?php

    require_once 'models/veiculoModel.php';

    function handleVeiculo($metodo, $parametro, $pdo){
        switch($metodo){
            case 'GET':
                $veiculo = $parametro ? veiculoModel::buscarPorId($pdo, $parametro) : veiculoModel::listarVeiculo($pdo);
                if($veiculo){
                    echo json_encode($veiculo);
                }else {
                    http_response_code(404);
                    echo json_encode([
                        "erro" => "Veiculo não encontrado"
                    ]);
                }
                break;
            
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                $sucesso = veiculoModel::criarVeiculo($data, $pdo);

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
                $sucesso = veiculoModel::editarVeiculo($data, $parametro, $pdo);

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

                $sucesso = veiculoModel::excluirVeiculo($parametro, $pdo);

                echo json_encode($sucesso);
                break;

        }
    }

?>