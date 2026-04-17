<?php

require_once "config_banco_uso.php";

$conn = new mysqli($host, $user, $password, $banco);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
$mensagens = [];

$mensagens[] = "Conectado ao banco";

// Desativar verificação de chaves estrangeiras
$conn->query("SET FOREIGN_KEY_CHECKS = 0");

// Buscar todas as tabelas do banco
$result = $conn->query("SHOW TABLES");

while ($row = $result->fetch_array()) {

    $tabela = $row[0];

    $conn->query("TRUNCATE TABLE $tabela");

    $mensagens[] = "Tabela limpa: $tabela";
}

// Reativar chaves estrangeiras
$conn->query("SET FOREIGN_KEY_CHECKS = 1");

$mensagens[] = "Banco resetado com sucesso.";

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