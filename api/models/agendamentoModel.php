<?php

    class agendamentoModel {
        
    //GET methods
        public static function listarAgendamento ($pdo){
            $sql = "SELECT 
                        -- Dados do Checklist
                        a.idagendamento, a.dataPrevista,
                        
                        -- Funcionário do agendamento
                        func_agenda.idfuncionario AS idfunc_agenda, 
                        func_agenda.nomeFunc AS nomefunc_agenda, 
                        func_agenda.cpf AS cpffunc_agenda, 
                        func_agenda.adm AS admfunc_agenda, 
                        func_agenda.ativo AS funcativo_agenda,

                        -- Dados do Veículo
                        veiculo.idveiculo, veiculo.placa, veiculo.tipo, veiculo.tara,
                        -- Funcionário do Veículo
                        func_vei.idfuncionario AS idfunc_veiculo, 
                        func_vei.nomeFunc AS nomefunc_veiculo,
                        func_vei.cpf AS cpffunc_veiculo,
                        func_vei.adm AS admfunc_veiculo,
                        func_vei.ativo AS funcativo_veiculo,

                         vagao.idvagao, vagao.comprimento, vagao.largura, vagao.altura,

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
                        func_trans.ativo AS funcativo_transportadora,
                        
                        pedido.idpedidos,
                        pedido.numPed,
                        pedido.nomeCliente,
                        pedido.cidadeCliente,
                        pedido.numSacos,
                        pedido.numPaletes,
                        pedido.numRomaneio,
                        pedido.transportadora AS transpPedido,
                        pedido.statusPed,
                        pedido.numPaleteRomaneio

                    FROM agendamento a
                    INNER JOIN veiculo ON a.veiculo_idveiculo = veiculo.idveiculo
                    LEFT JOIN vagao ON vagao.idveiculo = veiculo.idveiculo AND vagao.ativo = 'ativo'
                    INNER JOIN motorista ON a.motorista_idmotoristas = motorista.idmotoristas
                    INNER JOIN transportadora ON a.transportadora_idtransportadora = transportadora.idtransportadora
                    INNER JOIN pedido ON a.pedido_idpedidos = pedido.idpedidos

                    -- Múltiplos JOINs na tabela funcionário, cada um com um Alias diferente
                    INNER JOIN funcionario func_agenda ON a.funcionario_idfuncionario = func_agenda.idfuncionario
                    INNER JOIN funcionario func_vei ON veiculo.idfuncionario = func_vei.idfuncionario
                    INNER JOIN funcionario func_mot ON motorista.idfuncionario = func_mot.idfuncionario
                    INNER JOIN funcionario func_trans ON transportadora.idfuncionario = func_trans.idfuncionario

                    WHERE a.ativo = 'ativo'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $agendados = [];
            foreach($dados as $row){

                $id = $row['idagendamento'];

                if(!isset($agendados[$id])){
                    $agendados[$id] = [
                        "idAgendamento" => $row["idagendamento"],
                        "dataPrevista" => $row["dataPrevista"],
                        "funcionario" => [
                            "idfuncionario" => $row['idfunc_agenda'],
                            "nomeFunc" => $row['nomefunc_agenda'],
                            "cpf" => $row['cpffunc_agenda'],
                            "adm" => $row['admfunc_agenda'],
                            "funcativo" => $row['funcativo_agenda']
                        ],
                        "veiculo" => [
                            "idveiculo" => $row['idveiculo'],
                            "placa" => $row['placa'],
                            "tipo" => $row['tipo'],
                            "tara" => $row['tara'],
                            "vagoes" => [],
                            "funcionario" => [
                                "idfuncionario" => $row['idfunc_veiculo'],
                                "nomeFunc" => $row['nomefunc_veiculo'],
                                "cpf" => $row['cpffunc_veiculo'],
                                "adm" => $row['admfunc_veiculo'],
                                "funcativo" => $row['funcativo_veiculo']
                            ]
                        ],
                        "motorista" => [
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
                            "idtransportadora" => $row['idtransportadora'],
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
                        ],
                        "pedido" => [
                            "idpedidos" => $row['idpedidos'],
                            "numPed" => $row['numPed'],
                            "nomeCliente" => $row['nomeCliente'],
                            "cidadeCliente" => $row['cidadeCliente'],
                            "numSacos" => $row['numSacos'],
                            "numPaletes" => $row['numPaletes'],
                            "numRomaneio" => $row['numRomaneio'],
                            "transportadora" => $row['transpPedido'],
                            "statusPed" => $row['statusPed'],
                            "numPaleteRomaneio" => $row['numPaleteRomaneio']

                        ]
                    ];
                }

                if ($row['idvagao'] != null) {

                    $agendados[$id]["veiculo"]["vagoes"][] = [
                        "comprimento" => $row['comprimento'],
                        "largura" => $row['largura'],
                        "altura" => $row['altura']
                    ];
                }

            }
            return array_values($agendados);

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

            $required = ['dataPrevista', 'idpedido'];

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
                $data['idmotorista'],
                $data['idfuncionario'],
                $data['idpedido'],
                $data['idtransportadora'],
                $data['idveiculo']
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
                    $data['idmotorista'],
                    $data['idfuncionario'],
                    $data['idpedido'],
                    $data['idtransportadora'],
                    $data['idveiculo'],
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