<?php

    class veiculoModel {

    //GET methods
    //Consultas retornam veiculo e vagão relacionados
        public static function listarVeiculo ($pdo){
            $sql = "SELECT veiculo.idveiculo, veiculo.placa, veiculo.tipo, vagao.idvagao, vagao.comprimento, vagao.largura, vagao.altura, funcionario.idfuncionario, funcionario.nomeFunc, funcionario.cpf, funcionario.adm, funcionario.ativo as funcativo FROM veiculo
                    LEFT JOIN vagao
                    ON veiculo.idveiculo = vagao.idveiculo AND vagao.ativo = 'ativo'
                    INNER JOIN funcionario
                    ON veiculo.idfuncionario = funcionario.idfuncionario
                    WHERE veiculo.ativo = 'ativo'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $objVeiculos = [];

            $veiculos = [];
            foreach ($dados as $row){

                $id = $row["idveiculo"];

                // Se o veículo ainda não foi criado no array
                if (!isset($veiculos[$id])) {

                    $veiculos[$id] = [
                        "idveiculo" => $row["idveiculo"],
                        "placa" => $row["placa"],
                        "tipo" => $row['tipo'],
                        "vagoes" => [], // agora é lista
                        "funcionario" => [
                            "idfuncionario" => $row['idfuncionario'],
                            "nomeFunc" => $row['nomeFunc'],
                            "cpf" => $row['cpf'],
                            "adm" => $row['adm'],
                            "funcativo" => $row['funcativo']
                        ]
                    ];
                }

        // Se existir vagão (LEFT JOIN pode trazer null)
        if ($row['idvagao'] != null) {

            $veiculos[$id]["vagoes"][] = [
                "comprimento" => $row['comprimento'],
                "largura" => $row['largura'],
                "altura" => $row['altura']
            ];
        }

         
    }

    // Remove as chaves associativas (fica array normal)
    return array_values($veiculos);
}

        public static function buscarPorId($pdo, $idVeiculo){
  
            $sql = "SELECT * FROM veiculo
                    INNER JOIN vagao
                    ON veiculo.idveiculo = vagao.idveiculo
                    WHERE veiculo.idVeiculo = :idVeiculo  AND veiculo.ativo = 'ativo'";
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