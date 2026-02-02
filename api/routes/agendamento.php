<?php

    require_once 'models/agendamentoModel.php';

    function handleAgendamento($metodo, $parametro, $pdo){
        switch($metodo){
            case 'GET':
                $agendamento = $parametro ? agendamentoModel::buscarPorId($pdo, $parametro) : agendamentoModel::listarAgendamento($pdo);
                if($agendamento){
                    echo json_encode($agendamento);
                }else {
                    http_response_code(404);
                    echo json_encode([
                        "erro" => "agendamento não encontrado"
                    ]);
                }
                break;
            
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                $sucesso = agendamentoModel::criarAgendamento($data, $pdo);

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
                $sucesso = agendamentoModel::editarAgendamento($data, $parametro, $pdo);

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

                $sucesso = agendamentoModel::excluirAgendamento($parametro, $pdo);

                echo json_encode($sucesso);
                break;

        }
    }

?>