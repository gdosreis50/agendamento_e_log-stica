<?php

    class funcionarioModel {
        //GET methods
        public static function listarFuncionario ($pdo){
            $sql = "SELECT * FROM funcionario WHERE ativo = 'ativo'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function buscarPorId($pdo, $idFunc){
            $sql = "SELECT * FROM funcionario WHERE idfuncionario = :idFunc AND ativo = 'ativo'";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":idFunc", $idFunc);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        //POST method

        public static function criarFuncionario($data, $pdo){

            $required = ['nomeFunc'];

            foreach ($required as $field) {
                if (empty($data[$field])) {
                    http_response_code(400);
                    return [
                        "success" => false,
                        "error" => "Campo obrigatório ausente: $field"
                    ];
                }
            }

            $sql = "INSERT INTO funcionario (nomeFunc, cpf)
            VALUES (?, ?)";

            try{
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                $data['nomeFunc'],
                $data['cpf']
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
        public static function editarFuncionario($data, $idFunc, $pdo){

        $required = ['nomeFunc'];

            foreach ($required as $field) {
                if (empty($data[$field])) {
                    http_response_code(400);
                    return [
                        "success" => false,
                        "error" => "Campo obrigatório ausente: $field"
                    ];
                }
            }

            $sql = "UPDATE funcionario set 
                            nomeFunc = ?,
                            cpf = ?
                            WHERE idfuncionario = ?";

            try{
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $data['nomeFunc'],
                    $data['cpf'],
                    $idFunc
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

        public static function excluirFuncionario($idFunc, $pdo){

        $sql = "UPDATE funcionario SET ativo = 'desativado' WHERE idfuncionario = :idFunc";

        try{
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":idFunc", $idFunc);
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