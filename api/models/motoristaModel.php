<?php

    class motoristaModel {

    //GET methods
        public static function listarMotorista ($pdo){
            $sql = "SELECT m.idmotoristas, m.nomeMotorista, m.cpf as cpfMot, m.cnh, m.dataVencimentoCnh, m.categoriaCnh, m.telefone, 
                            f.idfuncionario, f.nomeFunc, f.cpf as cpfFunc, f.adm, f.ativo 
                            FROM motorista m
                    JOIN funcionario f ON m.idfuncionario = f.idfuncionario 
                    WHERE m.ativo = 'ativo'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $motoristas = [];
            foreach($dados as $row){
                $motoristas[] = [
                    "idmotoristas" => $row["idmotoristas"],
                    "nomeMotorista" => $row["nomeMotorista"],
                    "cpfMot" => $row["cpfMot"],
                    "cnh" => $row["cnh"],
                    "dataVencimentoCnh" => $row["dataVencimentoCnh"],
                    "categoriaCnh" => $row["categoriaCnh"],
                    "telefone" => $row["telefone"],
                    
                    "funcionario" => [
                        "idfuncionario" => $row["idfuncionario"],
                        "nomeFunc" => $row["nomeFunc"],
                        "cpfFunc" => $row['cpfFunc'],
                        "adm" => $row["adm"],
                        "ativo" => $row["ativo"],
                        ]   
                ];
            }

            return $motoristas;
        }

        public static function buscarPorId($pdo, $idMot){
            $sql = "SELECT * FROM motorista WHERE idMotoristas = :idMot AND ativo = 'ativo'";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":idMot", $idMot);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

    //POST method
        public static function criarMotorista($data, $pdo){

            $required = ['nomeMotorista','cpf','cnh','dataVencimentoCnh','categoriaCnh','idfuncionario'];

            foreach ($required as $field) {
                if (empty($data[$field])) {
                    http_response_code(400);
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
    public static function editarMotorista($data, $idMot, $pdo){

        $required = ['nomeMotorista','cpf','cnh','dataVencimentoCnh','categoriaCnh','idfuncionario'];

            foreach ($required as $field) {
                if (empty($data[$field])) {
                    http_response_code(400);
                    return [
                        "success" => false,
                        "error" => "Campo obrigatório ausente: $field"
                    ];
                }
            }

            $sql = "UPDATE motorista set 
                            nomeMotorista = ?,
                            cpf = ?, 
                            cnh = ?, 
                            dataVencimentoCnh = ?, 
                            categoriaCnh = ?, 
                            telefone = ?, 
                            idFuncionario = ? WHERE idMotoristas = ?";

            try{
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $data['nomeMotorista'],
                    $data['cpf'],
                    $data['cnh'],
                    $data['dataVencimentoCnh'],
                    $data['categoriaCnh'],
                    $data['telefone'],
                    $data['idfuncionario'],
                    $idMot
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

    public static function excluirMotorista($idMot, $pdo){

        $sql = "UPDATE motorista SET ativo = 'desativado' WHERE idMotoristas = :idMot";

        try{
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":idMot", $idMot);
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