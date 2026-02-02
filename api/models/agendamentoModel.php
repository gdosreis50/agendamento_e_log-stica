<?php

    class agendamentoModel {
        //GET methods
        public static function listarAgendamento ($pdo){
            $sql = "SELECT * FROM agendamento WHERE ativo = 'ativo'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function buscarPorId($pdo, $idAgen){
            $sql = "SELECT * FROM agendamento WHERE idagendamento = :idAgen AND ativo = 'ativo'";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":idAgen", $idAgen);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

    //POST method
        public static function criarAgendamento($data, $pdo){

            $required = ['dataPrevista'];

            foreach ($required as $field) {
                if (empty($data[$field])) {
                    http_response_code(400);
                    return [
                        "success" => false,
                        "error" => "Campo obrigatório ausente: $field"
                    ];
                }
            }

            $sql = "INSERT INTO agendamento (dataPrevista, motorista_idmotoristas, funcionario_idfuncionario, pedido_idpedidos, transportadora_idtransportadora, veiculo_idveiculo)
            VALUES (?, ?, ?, ?, ?, ?)";

            try{
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                $data['dataPrevista'],
                $data['motorista_idmotoristas'],
                $data['funcionario_idfuncionario'],
                $data['pedido_idpedidos'],
                $data['transportadora_idtransportadora'],
                $data['veiculo_idveiculo']
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
    public static function editarAgendamento($data, $idAgen, $pdo){

        $required = ['dataPrevista'];

            foreach ($required as $field) {
                if (empty($data[$field])) {
                    http_response_code(400);
                    return [
                        "success" => false,
                        "error" => "Campo obrigatório ausente: $field"
                    ];
                }
            }

            $sql = "UPDATE agendamento set 
                            dataPrevista = ?,
                            motorista_idmotoristas = ?, 
                            funcionario_idfuncionario = ?, 
                            pedido_idpedidos = ?,  
                            transportadora_idtransportadora = ?,
                            veiculo_idveiculo = ? WHERE idagendamento = ?";

            try{
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $data['dataPrevista'],
                    $data['motorista_idmotoristas'],
                    $data['funcionario_idfuncionario'],
                    $data['pedido_idpedidos'],
                    $data['transportadora_idtransportadora'],
                    $data['veiculo_idveiculo'],
                    $idAgen
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

    public static function excluirAgendamento($idAgen, $pdo){

        $sql = "UPDATE agendamento SET ativo = 'desativado' WHERE idagendamento = :idAgen";

        try{
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":idAgen", $idAgen);
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