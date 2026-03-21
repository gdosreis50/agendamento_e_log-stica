<?php

    require_once 'models/funcionarioModel.php';

    function handleFuncionario($metodo, $parametro, $pdo){
        switch($metodo){
            case 'GET':
                $funcionario = $parametro ? funcionarioModel::buscarPorId($pdo, $parametro) : funcionarioModel::listarFuncionario($pdo);
                echo json_encode($funcionario);
                break;
            
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                $sucesso = funcionarioModel::criarFuncionario($data, $pdo);

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
                $sucesso = funcionarioModel::editarFuncionario($data, $parametro, $pdo);

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

                $sucesso = funcionarioModel::excluirFuncionario($parametro, $pdo);

                echo json_encode($sucesso);
                break;

        }
    }

?>