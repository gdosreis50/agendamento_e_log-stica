<?php

    class checkModel {
        //GET methods
        public static function listarCheck ($pdo){
            $sql = "SELECT 
                        -- Dados do Checklist
                        c.idcheckList, c.dataEmissao,
                        
                        -- Funcionário do Checklist
                        func_chk.idfuncionario AS idfunc_checklist, 
                        func_chk.nomeFunc AS nomefunc_checklist, 
                        func_chk.cpf AS cpffunc_checklist, 
                        func_chk.adm AS admfunc_checklist, 
                        func_chk.ativo AS funcativo_checklist,

                        -- Dados do Veículo
                        veiculo.idveiculo, veiculo.placa, veiculo.tipo, veiculo.tara,
                        -- Funcionário do Veículo
                        func_vei.idfuncionario AS idfunc_veiculo, 
                        func_vei.nomeFunc AS nomefunc_veiculo,
                        func_vei.cpf AS cpffunc_veiculo,
                        func_vei.adm AS admfunc_veiculo,
                        func_vei.ativo AS funcativo_veiculo,

                        -- Dados do Motorista
                        motorista.idmotoristas, motorista.nomeMotorista, motorista.cpf, motorista.cnh, motorista.dataVencimentoCnh, motorista.categoriaCnh, motorista.telefone,
                        -- Funcionário do Motorista
                        func_mot.idfuncionario AS idfunc_motorista, 
                        func_mot.nomeFunc AS nomefunc_motorista,
                        func_mot.cpf AS cpffunc_motorista,
                        func_mot.adm AS admfunc_motorista,
                        func_mot.ativo AS funcativo_motorista,

                        -- Dados da Transportadora
                        transportadora.idtransportadora, transportadora.nomeTransportadora, transportadora.cnpj, transportadora.antt, transportadora.email,
                        -- Funcionário da Transportadora
                        func_trans.idfuncionario AS idfunc_transportadora, 
                        func_trans.nomeFunc AS nomefunc_transportadora,
                        func_trans.cpf AS cpffunc_transportadora,
                        func_trans.adm AS admfunc_transportadora,
                        func_trans.ativo AS funcativo_transportadora

                    FROM checklist c
                    INNER JOIN veiculo ON c.idveiculo = veiculo.idveiculo
                    INNER JOIN motorista ON c.idmotoristas = motorista.idmotoristas
                    INNER JOIN transportadora ON c.idtransportadora = transportadora.idtransportadora

                    -- Múltiplos JOINs na tabela funcionário, cada um com um Alias diferente
                    INNER JOIN funcionario func_chk ON c.idfuncionario = func_chk.idfuncionario
                    INNER JOIN funcionario func_vei ON veiculo.idfuncionario = func_vei.idfuncionario
                    INNER JOIN funcionario func_mot ON motorista.idfuncionario = func_mot.idfuncionario
                    INNER JOIN funcionario func_trans ON transportadora.idfuncionario = func_trans.idfuncionario

                    WHERE c.emFila = 'sim'
                    ORDER BY c.idcheckList;";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $checks = [];
            foreach ($dados as $row){

                $id = $row["idcheckList"];

                // Se o veículo ainda não foi criado no array
                if (!isset($checks[$id])) {

                    $checks[$id] = [
                        "idCheckList" => $row["idcheckList"],
                        "dataEmissao" => $row["dataEmissao"],
                        "funcionario" => [
                            "idfuncionario" => $row['idfunc_checklist'],
                            "nomeFunc" => $row['nomefunc_checklist'],
                            "cpf" => $row['cpffunc_checklist'],
                            "adm" => $row['admfunc_checklist'],
                            "funcativo" => $row['funcativo_checklist']
                        ],
                        "veiculo" => [
                            "idveiculo" => $row['idveiculo'],
                            "placa" => $row['placa'],
                            "tipo" => $row['tipo'],
                            "tara" => $row['tara'],
                            "funcionario" => [
                                "idfuncionario" => $row['idfunc_veiculo'],
                                "nomeFunc" => $row['nomefunc_veiculo'],
                                "cpf" => $row['cpffunc_veiculo'],
                                "adm" => $row['admfunc_veiculo'],
                                "funcativo" => $row['funcativo_veiculo']
                            ]
                        ],
                        "motoristas" => [
                            "idmotoristas" => $row['idmotoristas'],
                            "nomeMotorista" => $row['nomeMotorista'],
                            "cpfMot" => $row['cpf'],
                            "cnh" => $row['cnh'],
                            "dataVencimentoCnh" => $row['dataVencimentoCnh'],
                            "categoriaCnh" => $row['categoriaCnh'],
                            "telefone" => $row['telefone'],
                            "funcionario" => [
                                "idfuncionario" => $row['idfunc_motorista'],
                                "nomeFunc" => $row['nomefunc_motorista'],
                                "cpf" => $row['cpffunc_motorista'],
                                "adm" => $row['admfunc_motorista'],
                                "funcativo" => $row['funcativo_motorista']
                            ]
                        ],
                        "transportadora" => [
                            "idTransportadora" => $row['idtransportadora'],
                            "nomeTransportadora" => $row['nomeTransportadora'],
                            "cnpj" => $row['cnpj'],
                            "antt" => $row['antt'],
                            "email" => $row['email'],
                            "funcionario" => [
                                "idfuncionario" => $row['idfunc_transportadora'],
                                "nomeFunc" => $row['nomefunc_transportadora'],
                                "cpf" => $row['cpffunc_transportadora'],
                                "adm" => $row['admfunc_transportadora'],
                                "funcativo" => $row['funcativo_transportadora']
                            ]
                        ]

                    ];
                }
            }
            return array_values($checks);
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

            $required = ['numPed', 'dataEmissao', 'idveiculo', 'idmotoristas', 'idfuncionario'];

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

            $idCheckCriado = $pdo->lastInsertId();

            $sql2 = "UPDATE pedido SET idcheckList = ?, statusPed = 'carregado' WHERE numPed = ?";
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->execute([
                                $idCheckCriado, 
                                $data['numPed']
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

        public static function darBaixa($pdo, $idCheck){

            try{
                $stmt = $pdo->prepare("UPDATE checklist SET emFila = 'nao' WHERE idcheckList = ?");
                $stmt->execute([$idCheck]);

                http_response_code(200);
                return
                [
                    "Sucess" => true
                ];
            }catch(PDOException $e){
                http_response_code(500);
                return[
                    "Sucess" => false
                ];
            }
        }

    }

?>