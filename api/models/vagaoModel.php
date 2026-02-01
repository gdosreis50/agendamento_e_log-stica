<?php

    class vagaoModel {

    //GET methods
        public static function listarVagao ($pdo){
            $sql = "SELECT * FROM vagao where ativo = 'ativo'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function buscarPorId($pdo, $idVagao){
            $sql = "SELECT * FROM vagao WHERE idvagao = :idvagao AND ativo = 'ativo'";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":idvagao", $idVagao);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

    //POST method
        public static function criarVagao($data, $pdo){

            $required = ['comprimento', 'largura'];

            foreach ($required as $field) {
                if (empty($data[$field])) {
                    http_response_code(400);
                    return [
                        "success" => false,
                        "error" => "Campo obrigatório ausente: $field"
                    ];
                }
            }

            $sql = "INSERT INTO vagao (comprimento, largura, altura, idveiculo, idfuncionario)
            VALUES (?, ?, ?, ?, ?)";

            try{
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                $data['comprimento'],
                $data['largura'],
                $data['altura'],
                $data['idveiculo'],
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
    public static function editarVagao($data, $idVagao, $pdo){

        $required = ['comprimento', 'largura'];

            foreach ($required as $field) {
                if (empty($data[$field])) {
                    http_response_code(400);
                    return [
                        "success" => false,
                        "error" => "Campo obrigatório ausente: $field"
                    ];
                }
            }

            $sql = "UPDATE vagao set 
                            comprimento = ?,
                            largura = ?, 
                            altura = ?, 
                            idveiculo = ?, 
                            idfuncionario = ? WHERE idvagao = ?";

            try{
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $data['comprimento'],
                    $data['largura'],
                    $data['altura'],
                    $data['idveiculo'],
                    $data['idfuncionario'],
                    $idVagao
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

    public static function excluirVagao($idVagao, $pdo){

        $sql = "UPDATE vagao SET ativo = 'desativado' WHERE idvagao = :idvagao";

        try{
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":idvagao", $idVagao);
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