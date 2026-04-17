<?php

require_once "config_banco_uso.php";


$conn = new mysqli($host, $user, $password);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
$mensagens = [];
$mensagens[] = "Conectado ao MySQL";


// Criar banco se não existir
$conn->query("CREATE DATABASE IF NOT EXISTS $banco");

$conn->select_db($banco);


// =============================
// APAGAR TODAS AS TABELAS
// =============================

$conn->query("SET FOREIGN_KEY_CHECKS = 0");

$result = $conn->query("SHOW TABLES");

while ($row = $result->fetch_array()) {

    $tabela = $row[0];

    $conn->query("DROP TABLE $tabela");

    $mensagens[] = "Tabela removida: $tabela";
}

$conn->query("SET FOREIGN_KEY_CHECKS = 1");

$mensagens[] = "Todas as tabelas foram apagadas.";


// =============================
// CRIAR TABELAS NOVAMENTE
// =============================


// usuarios
$conn->query("CREATE TABLE usuarios(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    genero CHAR(1),
    email VARCHAR(50) NOT NULL,
    cpf VARCHAR(15) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    nivel ENUM('paciente','medico') DEFAULT 'paciente',
    telefone numeric(11)
)");


// paciente
$conn->query("CREATE TABLE paciente(
    id INT AUTO_INCREMENT PRIMARY KEY,
    fk_usuario_id INT,
    data_nascimento DATE NOT NULL,
    rua VARCHAR(50),
    numero_casa VARCHAR(10),
    bairro VARCHAR(25),
    cidade VARCHAR(30),
    altura FLOAT,
    peso FLOAT,
    contato_emergencia VARCHAR(11)
)");


// medico
$conn->query("CREATE TABLE medico(
    id INT AUTO_INCREMENT PRIMARY KEY,
    fk_usuario_id INT,
    crm VARCHAR(13) NOT NULL,
    especialidade VARCHAR(50) NOT NULL
)");


// arquivos
$conn->query("CREATE TABLE arquivos(
    id_arquivos INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    caminho VARCHAR(255) NOT NULL,
    descricao TEXT,
    anexo BLOB,
    tipo VARCHAR(25),
    data_emissao DATE NOT NULL,
    data_validade DATE NOT NULL,
    status VARCHAR(50),
    fk_paciente_id INT,
    fk_medico_id INT
)");


// medicamentos
$conn->query("CREATE TABLE medicamento_em_uso(
    id_medicamento INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    dosagem VARCHAR (20) NOT NULL,
    frequencia VARCHAR (20) NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE,
    observacao VARCHAR(50),
    fk_medico_id INT NOT NULL,
    fk_paciente_id INT NOT NULL
)");


// =============================
// FOREIGN KEYS
// =============================

$conn->query("ALTER TABLE paciente
ADD CONSTRAINT fk_paciente_usuario
FOREIGN KEY (fk_usuario_id)
REFERENCES usuarios(id)
ON DELETE CASCADE
ON UPDATE CASCADE");

$conn->query("ALTER TABLE medico
ADD CONSTRAINT fk_medico_usuario
FOREIGN KEY (fk_usuario_id)
REFERENCES usuarios(id)
ON DELETE CASCADE
ON UPDATE CASCADE");

$conn->query("ALTER TABLE arquivos
ADD CONSTRAINT fk_arquivos_paciente
FOREIGN KEY (fk_paciente_id)
REFERENCES paciente(id)
ON DELETE CASCADE
ON UPDATE CASCADE");

$conn->query("ALTER TABLE arquivos
ADD CONSTRAINT fk_arquivos_medico
FOREIGN KEY (fk_medico_id)
REFERENCES medico(id)
ON DELETE CASCADE
ON UPDATE CASCADE");

$conn->query("ALTER TABLE medicamento_em_uso
ADD CONSTRAINT fk_medicamento_paciente
FOREIGN KEY (fk_paciente_id)
REFERENCES paciente(id)
ON DELETE CASCADE
ON UPDATE CASCADE");

$conn->query("ALTER TABLE medicamento_em_uso
ADD CONSTRAINT fk_medicamento_medico
FOREIGN KEY (fk_medico_id)
REFERENCES medico(id)
ON DELETE CASCADE
ON UPDATE CASCADE");


$mensagens[] = "Tabelas recriadas com sucesso.";

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