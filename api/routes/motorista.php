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

        }
    }

?>