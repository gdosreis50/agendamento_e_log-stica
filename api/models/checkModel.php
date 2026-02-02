<?php

    class checkModel {
        //GET methods
        public static function listarCheck ($pdo){
            $sql = "SELECT * FROM checklist";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function buscarPorId($pdo, $idCheck){
            $sql = "SELECT * FROM checklist WHERE idchecklist = :idCheck";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":idCheck", $idCheck);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        //POST method

        public static function criarCheck($data, $pdo){

            $required = ['dataEmissao', 'idveiculo', 'idmotoristas', 'idtransportadora', 'idfuncionario'];

            foreach ($required as $field) {
                if (empty($data[$field])) {
                    http_response_code(400);
                    return [
                        "success" => false,
                        "error" => "Campo obrigatório ausente: $field"
                    ];
                }
            }

            $sql = "INSERT INTO checklist (dataEmissao, idveiculo, idmotoristas, idtransportadora, idfuncionario)
            VALUES (?, ?, ?, ?, ?)";

            try{
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                $data['dataEmissao'],
                $data['idveiculo'],
                $data['idmotoristas'],
                $data['idtransportadora'],
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

    }

?>