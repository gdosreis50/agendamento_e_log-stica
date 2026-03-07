<?php

    class PedidoModel {
        public static function listar ($pdo){
            $sql = "SELECT * FROM pedido  WHERE statusPed <> 'carregado'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function buscarPorNumero ($pdo, $numPed){
            $sql = "SELECT * FROM pedido WHERE numPed = :numPed AND statusPed <> 'carregado'";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":numPed", $numPed);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

?>