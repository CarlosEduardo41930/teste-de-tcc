<?php
require_once '../controllers/UserControll.php';
require_once './components/UserComponents.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        <div>
            <label for="nome">Nome: </label>
            <input type="text" name="nome">
            <label for="descricao">Descrição: </label>
            <input type="text" name="descricao">
        </div>

        <div>
                <label for="arquivo">Arquivo PDF</label>
                <input type="file" id="arquivo" name="arquivo" accept=".pdf,application/pdf" required>
                <small style="color:#6b7280;">Somente PDF. Tamanho máximo: 10 MB.</small>
            </div>
    </form>

    <p>tool</p>
</body>
</html>