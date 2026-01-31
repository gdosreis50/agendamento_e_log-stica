<?php

    require_once 'models/PedidoModel.php';

    function handlePedido ($metodo, $parametro, $pdo){
        
        if($metodo !== 'GET'){
            http_response_code(405);
            echo json_encode([
                "erro" => "Método nao permitido"
            ]);
            return;
        }

        if($parametro){
            $pedido = PedidoModel::buscarPorNumero($pdo, $parametro);

            if($pedido){
                echo json_encode($pedido);
            } else {
                http_response_code(404);
                echo json_encode([
                    "erro" => "Pedido não encontrado"
                ]);
            }
        }else {
            $pedidos = PedidoModel::listar($pdo);
            echo json_encode($pedidos, JSON_UNESCAPED_UNICODE);
        }
    }

?>