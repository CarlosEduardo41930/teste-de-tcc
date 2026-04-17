<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Painel do Banco de Dados</title>

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
    width:420px;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

h1{
    text-align:center;
    margin-bottom:30px;
    color:#333;
}

.card{
    background:#f7f7f7;
    padding:15px;
    margin-bottom:15px;
    border-radius:8px;
    border-left:5px solid #2a5298;
}

.card p{
    margin:0 0 10px 0;
    color:#333;
}

.card a{
    display:inline-block;
    text-decoration:none;
    background:#2a5298;
    color:white;
    padding:8px 15px;
    border-radius:5px;
    transition:0.3s;
}

.card a:hover{
    background:#1e3c72;
}

</style>

</head>
<body>

<div class="container">

<h1>Painel do Banco</h1>

<div class="card">
<p>Criar banco de dados e tabelas caso não existam.</p>
<a href="install.php">Criar Banco</a>
</div>

<div class="card">
<p>Inserir dados de teste nas tabelas.</p>
<a href="seed.php">Criar dados de teste</a>
</div>

<div class="card">
<p>Remover todos os dados das tabelas.</p>
<a href="reset_banco.php">Resetar dados</a>
</div>

<div class="card">
<p>Excluir todas as tabelas e recriar novamente.</p>
<a href="recriar_banco.php">Recriar tabelas</a>
</div>

</div>

</body>
</html>