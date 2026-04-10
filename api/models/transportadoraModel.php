<?php

    class transportadoraModel {
        //GET methods
        public static function listarTransportadora ($pdo){
            $sql = "SELECT * FROM transportadora WHERE ativo = 'ativo' AND idtransportadora <> 4";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function buscarPorId($pdo, $idTrans){
            $sql = "SELECT * FROM transportadora WHERE idtransportadora = :idTrans AND ativo = 'ativo'";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":idTrans", $idTrans);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

    //POST method
        public static function criarTransportadora($data, $pdo){

            $required = ['nomeTransportadora','cnpj','antt'];

            foreach ($required as $field) {
                if (empty($data[$field])) {
                    http_response_code(400);
                    return [
                        "success" => false,
                        "error" => "Campo obrigatório ausente: $field"
                    ];
                }
            }

            $sql = "INSERT INTO transportadora (nomeTransportadora, cnpj, antt, email, idfuncionario)
            VALUES (?, ?, ?, ?, ?)";

            try{
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                $data['nomeTransportadora'],
                $data['cnpj'],
                $data['antt'],
                $data['email'],
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
    public static function editarTransportadora($data, $idTrans, $pdo){

        $required = ['nomeTransportadora','cnpj','antt'];

            foreach ($required as $field) {
                if (empty($data[$field])) {
                    http_response_code(400);
                    return [
                        "success" => false,
                        "error" => "Campo obrigatório ausente: $field"
                    ];
                }
            }

            $sql = "UPDATE transportadora set 
                            nomeTransportadora = ?,
                            cnpj = ?, 
                            antt = ?, 
                            email = ?,  
                            idFuncionario = ? WHERE idtransportadora = ?";

            try{
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $data['nomeTransportadora'],
                    $data['cnpj'],
                    $data['antt'],
                    $data['email'],
                    $data['idfuncionario'],
                    $idTrans
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

    public static function excluirTransportadora($idTrans, $pdo){

        $sql = "UPDATE transportadora SET ativo = 'desativado' WHERE idtransportadora = :idTrans";

        try{
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":idTrans", $idTrans);
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