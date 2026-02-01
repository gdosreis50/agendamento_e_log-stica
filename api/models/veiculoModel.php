<?php

    class veiculoModel {

    //GET methods
        public static function listarVeiculo ($pdo){
            $sql = "SELECT * FROM veiculo WHERE ativo = 'ativo'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function buscarPorId($pdo, $idVeiculo){
            $sql = "SELECT * FROM veiculo WHERE idVeiculo = :idVeiculo  AND ativo = 'ativo'";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":idVeiculo", $idVeiculo);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

    //POST method
        public static function criarVeiculo($data, $pdo){

            $required = ['placa'];

            foreach ($required as $field) {
                if (empty($data[$field])) {
                    http_response_code(400);
                    return [
                        "success" => false,
                        "error" => "Campo obrigatório ausente: $field"
                    ];
                }
            }

            $sql = "INSERT INTO veiculo (placa, tipo, idfuncionario)
            VALUES (?, ?, ?)";

            try{
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                $data['placa'],
                $data['tipo'],
                $data['idfuncionario']
            ]);

            http_response_code(201);
            return 
            [
                "Success" => true,
                "id" => $pdo->lastInsertId()
            ];
            } catch (PDOException $e){
                http_response_code(500);
                return [
                    "Success" => false,
                    "Erro" => $e->getMessage()
                ];
            }
            
        }

    //PUT method
    public static function editarVeiculo($data, $idVeiculo, $pdo){

        $required = ['placa'];

            foreach ($required as $field) {
                if (empty($data[$field])) {
                    http_response_code(400);
                    return [
                        "success" => false,
                        "error" => "Campo obrigatório ausente: $field"
                    ];
                }
            }

            $sql = "UPDATE veiculo set 
                            placa = ?,
                            tipo = ?, 
                            idFuncionario = ? WHERE idVeiculo = ?";

            try{
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $data['placa'],
                    $data['tipo'],
                    $data['idfuncionario'],
                    $idVeiculo
                ]);

                http_response_code(200);
                return [
                    "success" => true,
                ];
            } catch(PDOException $e){
                http_response_code(500);
                return [
                    "Success" => false,
                    "Erro" => $e->getMessage()
                ];
            }
    }


    //DELETE method

    public static function excluirVeiculo($idVeiculo, $pdo){

        $sql = "UPDATE veiculo SET ativo = 'desativado' WHERE idVeiculo = :idVeiculo";

        try{
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":idVeiculo", $idVeiculo);
            $stmt->execute();
            http_response_code(200);
            return ["success" => true];
        }catch (PDOException $e){
            http_response_code(500);
            return [
                    "Success" => false,
                    "Erro" => $e->getMessage()
                ];
        }
    }

    }


?>