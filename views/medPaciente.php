<?php
require_once '../controllers/UserControll.php';

sessionPaciente();
verificarTipo(['medico']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="adicionarDocumento.php">Adicionar Documento</a>
    <a href="repositorio.php?tipo=receitas&mansagem=receita">receita</a>
</body>
</html>