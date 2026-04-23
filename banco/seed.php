<?php

require_once "config_banco_uso.php";

$conn = new mysqli($host, $user, $password, $banco);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$mensagens = [];
$mensagens[] = "Conectado ao banco";

// =============================
// USUARIOS
// =============================

$senha = password_hash("123456", PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios
(nome, genero, email, cpf, senha, nivel, telefone)
VALUES
('Joyce Ramos','F','joyce@email.com','55246582809','$senha','paciente','11981907326'),
('Carlos Silva','M','carlos@email.com','12345678900','$senha','paciente','11987654321'),
('Maria Oliveira','F','maria@email.com','98765432100','$senha','paciente','11976543210'),
('João Santos','M','joao@email.com','45612378900','$senha','medico','11965432109'),
('Erick Moraes','M','erick@email.com','78912345600','$senha','medico','11982436669'),
('Ana Souza','F','ana@email.com','15975345600','$senha','medico','11954321098'),
('Pedro Almeida','M','pedro@email.com','25836914700','$senha','paciente','11981110001'),
('Marina Costa','F','marina@email.com','95135725800','$senha','paciente','11981110002'),
('Lucas Pereira','M','lucas@email.com','35715925800','$senha','paciente','11981110003'),
('Juliana Martins','F','juliana@email.com','65498732100','$senha','paciente','11981110004')
";

$conn->query($sql);

// =============================
// PACIENTES (ATUALIZADO)
// =============================

$sql = "INSERT INTO paciente
(fk_usuario_id, alergias, tipo_sanguineo, numero_de_carteirinha, historico_familiar, data_nascimento, rua, numero_casa, bairro, cidade, altura, peso, contato_emergencia)
VALUES
(1,'Nenhuma','O+','12345','Sem histórico relevante','1999-02-01','Av Covabra','690','Centro','Salto',1.60,65,'11981016027'),
(2,'Poeira','A+','12346','Hipertensão na família','1998-05-10','Rua das Flores','120','Centro','Campinas',1.75,72,'11988887777'),
(3,'Lactose','B+','12347','Diabetes familiar','2001-08-21','Av Brasil','450','Jardim','Indaiatuba',1.65,60,'11977776666'),
(7,'Nenhuma','O-','12348','Sem histórico','1997-03-15','Rua Goiás','55','Centro','Sorocaba',1.72,70,'11981112222'),
(8,'Glúten','AB+','12349','Problemas cardíacos','1996-11-21','Av Paulista','900','Bela Vista','São Paulo',1.62,58,'11983334444'),
(9,'Nenhuma','A-','12350','Sem histórico','1995-07-18','Rua XV','100','Centro','Jundiaí',1.80,85,'11985556666'),
(10,'Medicamento X','B-','12351','Alergias recorrentes','2000-01-05','Av Independência','500','Centro','Ribeirão Preto',1.70,68,'11987778888')
";

$conn->query($sql);

// =============================
// MEDICOS
// =============================

$sql = "INSERT INTO medico
(fk_usuario_id, crm, especialidade)
VALUES
(4,'CRM098765','Dermatologia'),
(5,'CRM123456','Cardiologia'),
(6,'CRM654321','Clínico Geral')
";

$conn->query($sql);

// =============================
// ARQUIVOS
// =============================

$sql = "INSERT INTO arquivos
(nome, caminho, descricao, anexo, tipo, data_emissao, data_validade, status, fk_paciente_id, fk_medico_id)
VALUES
('Exame de Sangue','../../documento/Joyce_Ramos/receitaUm.pdf','Exame de rotina',NULL,'receitas','2025-01-10','2026-01-10','ativo',1,1),
('Raio X','/arquivos/raiox.pdf','Raio X do tórax',NULL,'PDF','2025-02-15','2026-02-15','ativo',2,2),
('Ultrassom','/arquivos/ultrassom.pdf','Ultrassom abdominal',NULL,'PDF','2025-03-20','2026-03-20','ativo',3,3),
('Tomografia','/arquivos/tomografia.pdf','Tomografia craniana',NULL,'PDF','2025-04-10','2026-04-10','ativo',4,1),
('Hemograma','/arquivos/hemograma.pdf','Hemograma completo',NULL,'PDF','2025-04-15','2026-04-15','ativo',5,2)
";

$conn->query($sql);

// =============================
// MEDICAMENTOS
// =============================

$sql = "INSERT INTO medicamento_em_uso
(nome, dosagem, frequencia, data_inicio, data_fim, observacao, fk_medico_id, fk_paciente_id)
VALUES
('Losartana','50 mg','2x ao dia','2025-03-01','2025-09-01','Pressão alta',1,1),
('Paracetamol','1 comprimido','a cada 8 horas','2025-03-05','2025-03-10','Dor de cabeça',2,2),
('Dipirona','20 gotas','a cada 6 horas','2025-03-08','2025-03-24','Febre',3,3),
('Ibuprofeno','400 mg','se necessário','2025-04-01','2025-04-07','Inflamação',1,4),
('Omeprazol','1 cápsula','1x ao dia (jejum)','2025-04-05','2025-05-05','Gastrite',2,5)
";

$conn->query($sql);

// =============================
// PROBLEMAS DE SAÚDE (NOVO)
// =============================

$sql = "INSERT INTO problema_de_saude
(nome, status, tipo, data, fk_paciente, fk_medico)
VALUES
('Hipertensão','controlado','grave','2025-01-10',1,1),
('Gripe','curado','leve','2025-02-15',2,2),
('Diabetes','em tratamento','medio','2025-03-20',3,3),
('Enxaqueca','recorrente','normal','2025-04-01',4,1),
('Gastrite','tratamento','medio','2025-04-05',5,2)
";

$conn->query($sql);

$mensagens[] = "Dados inseridos com sucesso.";

$conn->close();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Códigos do Banco</title>

<style>

body{
    font-family: Arial, Helvetica, sans-serif;
    background: linear-gradient(135deg,#1e3c72,#2a5298);
    margin:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.container{
    background:white;
    padding:40px;
    border-radius:10px;
    width:450px;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

h1{
    text-align:center;
    margin-bottom:20px;
}

.msg{
    background:#f5f5f5;
    padding:10px;
    border-left:4px solid #2a5298;
    margin-bottom:10px;
    border-radius:5px;
}

a{
    display:inline-block;
    margin-top:20px;
    text-decoration:none;
    background:#2a5298;
    color:white;
    padding:10px 20px;
    border-radius:6px;
}

a:hover{
    background:#1e3c72;
}

</style>

</head>
<body>

<div class="container">

<h1>Status do Banco</h1>

<?php
foreach($mensagens as $msg){
    echo "<div class='msg'>$msg</div>";
}
?>

<br>

<a href="index.php">⬅ Voltar</a>

</div>

</body>
</html>