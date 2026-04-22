<?php

function setPaciente($pdo, $nome, $email, $senha, $confirmar_senha, $cpf, $telefone, $genero, $nivel, $bdate)
{

    if (!empty($email)) {

        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['erro'][] = "Este e-mail já está cadastrado.";

            header("Location: login.php");
            exit();
        } else {

            if (empty($nome) || empty($email) || empty($senha) || empty($bdate) || empty($cpf) || empty($telefone) || empty($genero) || empty($confirmar_senha)) {

                $_SESSION['erro'][] = "Preencha todos os campos.";
            } else {

                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                try {

                    $pdo->beginTransaction();

                    // 1️⃣ Insere usuário
                    $sql = "INSERT INTO usuarios (nome, genero, email, cpf, senha, nivel, telefone) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)";

                    $stmt = $pdo->prepare($sql);

                    $stmt->execute([
                        $nome,
                        $genero,
                        $email,
                        $cpf,
                        $senhaHash,
                        $nivel,
                        $telefone
                    ]);

                    //  pega o ID do usuário criado
                    $usuario_id = $pdo->lastInsertId();

                    // insere na tabela paciente
                    $sql = "INSERT INTO paciente (fk_usuario_id, data_nascimento) VALUES (?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$usuario_id, $bdate]);

                    $pdo->commit();

                    header('Location: ../views/login.php');
                    exit();
                } catch (Exception $e) {

                    $pdo->rollBack();
                    $_SESSION['erro'][] = "Erro ao cadastrar: " . $e->getMessage();
                }
            }
        }
    }
}






function setMedico($pdo, $nome, $email, $senha, $confirmar_senha, $cpf, $crm, $telefone, $especialidade, $genero, $nivel)
{
    if (!empty($email)) {

        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['erro'][] = "Este e-mail já está cadastrado.";

            header("Location: ../views/login.php");
            exit();
        } else {

            if (empty($nome) || empty($email) || empty($senha) || empty($cpf) || empty($crm) || empty($telefone) || empty($especialidade) || empty($genero) || $confirmar_senha !== $senha) {

                $_SESSION['erro'][] = "Campo vazio ou senha incoerente";
            } else {

                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                try {

                    $pdo->beginTransaction();

                    // Insere usuário
                    $sql = "INSERT INTO usuarios (nome, genero, email, cpf, senha, nivel, telefone) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)";

                    $stmt = $pdo->prepare($sql);

                    $stmt->execute([
                        $nome,
                        $genero,
                        $email,
                        $cpf,
                        $senhaHash,
                        $nivel,
                        $telefone
                    ]);

                    //  pega o ID do usuário criado
                    $usuario_id = $pdo->lastInsertId();

                    // insere na tabela medico
                    $sql = "INSERT INTO medico (fk_usuario_id, crm, especialidade) VALUES (?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$usuario_id, $crm, $especialidade]);

                    $pdo->commit();

                    header('Location: ../views/login.php');
                    exit();
                } catch (Exception $e) {

                    $pdo->rollBack();
                    $_SESSION['erro'][] = "Erro ao cadastrar: " . $e->getMessage();
                }
            }
        }
    }
}



function validar($pdo, $senha, $cpf)
{

    $sql = "SELECT senha, nivel, id FROM usuarios WHERE cpf = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cpf]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {

        $_SESSION['nivel'] = $usuario['nivel'];
        $_SESSION['id_usuario'] = $usuario['id'];

        if ($usuario['nivel'] == 'medico') {

            header("Location: ../views/pgMedico.php");
            //echo 'deu certo!';
            exit();
        } elseif ($usuario['nivel'] == 'paciente') {

            header("Location: ../views/pgPaciente.php");
            exit();
        }
    } else {

        $_SESSION['erro'][] = "Usuário ou senha incorretos!";
    }
}

function getMedicamentoPaciente($pdo, $idPaciente)
{
    $sql = "SELECT nome, dosagem, frequencia FROM medicamento_em_uso WHERE fk_paciente_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idPaciente]);
    return $stmt->fetchAll();
}




function setTelEmergencia($pdo, $telefone, $paciente_id)
{
    $sql = "UPDATE paciente SET contato_emergencia = ? WHERE fk_usuario_id = ?";

    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$telefone, $paciente_id])) {
        echo "Contato adicionado com sucesso!";
    } else {
        echo "Erro ao adicionar contato";
    }
}

function getTelEmergenciaDataBase($pdo, $paciente_id) { // as outras funções dessa pagina podem seguir os mesmos padrões, apenas mudando os dados do banco e nome de variavel
    $sql = "SELECT contato_emergencia FROM paciente WHERE fk_usuario_id = ?";

    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$paciente_id])) {
       $TelEmergencia = $stmt->fetch(PDO::FETCH_ASSOC);

        return $TelEmergencia['contato_emergencia']; // variavel que deve ser usada no front para mostrar o telefone
    } else {
        echo "Erro ao adicionar contato";
    }

}

function getReceitasMedicas($pdo, $id){
        $sql = "SELECT a.id_arquivos as id, a.descricao as descricao, a.data_emissao as data, usua.nome as medico FROM arquivos a LEFT JOIN medico me ON a.fk_medico_id = me.id LEFT JOIN usuarios usua ON me.fk_usuario_id = usua.id WHERE a.fk_paciente_id = ? and a.tipo = 'receitas' ORDER BY a.data_emissao;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

function getArquivo($pdo, $idArquivo)
{
    $sql = "SELECT caminho FROM arquivos WHERE id_arquivos = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idArquivo]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getPacienteMedicos($pdo, $idMedico)
{
    $sql = "SELECT DADOSPACIENTE.nome,
DADOSPACIENTE.data_nascimento,
DADOSPACIENTE.tipo,
DADOSPACIENTE.numero_de_carteirinha,
DADOSPACIENTE.id
FROM medico INNER JOIN usuarios on medico.fk_usuario_id = usuarios.id
            INNER JOIN (SELECT problema_de_saude.fk_medico, usuarios.nome, paciente.data_nascimento, paciente.id, paciente.numero_de_carteirinha,
                        MAX(CASE
                            WHEN problema_de_saude.tipo = 'grave' THEN 4
                            WHEN problema_de_saude.tipo = 'medio' THEN 3
                            WHEN problema_de_saude.tipo = 'normal' THEN 2
                            WHEN problema_de_saude.tipo = 'leve' THEN 1
                            END) as tipo
                        FROM usuarios INNER JOIN paciente on usuarios.id = paciente.fk_usuario_id
                                      INNER JOIN problema_de_saude on paciente.id = problema_de_saude.fk_paciente
                        GROUP BY problema_de_saude.fk_medico, usuarios.nome, paciente.data_nascimento, paciente.numero_de_carteirinha,paciente.id) as DADOSPACIENTE
                        on DADOSPACIENTE.fk_medico = medico.id
WHERE usuarios.id = ?
ORDER BY DADOSPACIENTE.nome";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idMedico]);
    return $stmt->fetchAll();
}

function getBusca($pdo, $item){
$pesquisa = "%$item%";
    $sql ="SELECT paciente.id as id, usuarios.nome as nome, paciente.data_nascimento as data_nascimento, paciente.numero_de_carteirinha as numero_de_carteirinha,  MAX(CASE
                            WHEN problema_de_saude.tipo = 'grave' THEN 4
                            WHEN problema_de_saude.tipo = 'medio' THEN 3
                            WHEN problema_de_saude.tipo = 'normal' THEN 2
                            WHEN problema_de_saude.tipo = 'leve' THEN 1
                            END) as tipo
FROM paciente INNER JOIN usuarios on paciente.fk_usuario_id = usuarios.id
			  INNER JOIN problema_de_saude on paciente.id = problema_de_saude.fk_paciente
              WHERE (usuarios.nome LIKE ?
                     OR paciente.numero_de_carteirinha LIKE ?)
              GROUP BY  paciente.id, usuarios.nome, paciente.data_nascimento, paciente.numero_de_carteirinha
              
              ORDER BY usuarios.nome;
              ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pesquisa, $pesquisa]);
    return $stmt->fetchAll();
}

function setArquivo($pdo, $nome, $descricao, $data_emissao, $data_validade, $tipo, $status, $medico, $paciente){
    try{
    $sql = "INSERT INTO arquivos (nome, descricao, data_emissao, data_validade, tipo, status, fk_medico_id, fk_paciente_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $sucesso = $stmt->execute([$nome, $descricao, $data_emissao, $data_validade, $tipo, $status, $medico, $paciente]);
if(!$sucesso){
    throw new Exception("Erro ao cadastrar arquivo.");
}
$id = $pdo->lastInsertId();
return $id;

    } catch (Exception $e) {
       $_SESSION['erro'][] = "Erro ao cadastrar arquivo: " . $e->getMessage();
    }
}
