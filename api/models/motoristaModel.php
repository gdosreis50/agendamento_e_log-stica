<?php

    class motoristaModel {
        public static function listarMotorista ($pdo){
            $sql = "SELECT * FROM motorista";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function buscarPorId($pdo, $idMot){
            $sql = "SELECT * FROM motorista WHERE idMotoristas = :idMot";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":idMot", $idMot);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public static function criarMotorista($data, $pdo){

            $required = ['nomeMotorista','cpf','cnh','dataVencimentoCnh','categoriaCnh','idfuncionario'];

            foreach ($required as $field) {
                if (empty($data[$field])) {
                    return [
                        "success" => false,
                        "error" => "Campo obrigatório ausente: $field"
                    ];
                }
            }

            $sql = "INSERT INTO motorista (nomeMotorista, cpf, cnh, dataVencimentoCnh, categoriaCnh, telefone, idfuncionario)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

            try{
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                $data['nomeMotorista'],
                $data['cpf'],
                $data['cnh'],
                $data['dataVencimentoCnh'],
                $data['categoriaCnh'],
                $data['telefone'],
                $data['idfuncionario']
            ]);

            return [
                "Success" => true,
                "id" => $pdo->lastInsertId()
            ];
            } catch (PDOException $e){
                return [
                    "Success" => false,
                    "Erro" => $e->getMessage()
                ];
            }
            
        }
    }



?>