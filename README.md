Esta API controla o banco de dados para o projeto de Logística do TCC. Ela é capaz de fazer operações CRUD, comunicando-se via JSON.

//Em ambiente de desenvolvimento

REQUISIÇÕES GET PARA MOTORISTAS

  Para requisições deste tipo basta 

            localhost/api/motorista

  Assim, todos os motoristas disponíveis serão listados.

  Para motorista específico, seu ID será colocado também

            localhost/api/motorista/50

Requisições POST para motoristas

  Alguns parâmetros são obrigatórios, mas é ideal que todos sejam enviados no corpo da requisição.

            localhost/api/motorista

            {
                "nomeMotorista": "STRING do nome", 
                "cpf": "STRING do cpf", 
                "cnh": "STRING cnh", 
                "dataVencimentoCnh": "YYYY-MM-DD", 
                "categoriaCnh": "X",
                "telefone": "STRING telefone",
                "idfuncionario": 1
            }

  Apenas telefone não é obrigatório.

Requisições PUT para motoristas

  Alguns parâmetros são obrigatórios, mas é ideal que todos sejam enviados no corpo da requisição.
  O ID do motorista que será atualizado deve estar na URI
  
            localhost/api/motorista/25

            {
                "nomeMotorista": "STRING do nome", 
                "cpf": "STRING do cpf", 
                "cnh": "STRING cnh", 
                "dataVencimentoCnh": "YYYY-MM-DD", 
                "categoriaCnh": "X",
                "telefone": "STRING telefone",
                "idfuncionario": 1
            }
  Apenas telefone não é obrigatório.

Requisições DELETE para motoristas
