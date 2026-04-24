<?php
require_once '../controllers/UserControll.php';
require_once './components/UserComponents.php';
mudarSenha();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Recuperar senha</title>
</head>
<body>
<h2>Recuperar senha</h2>
<?php mensagemSucesso() ?>
<?php mensagemErro() ?>

<form method="POST">

    <label>Digite seu CPF</label>
    <input name="cpf" type="text" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" maxlength="14" placeholder="Digite seu CPF aqui" required>

    <button type="submit">Recuperar senha</button>

</form>

</body>
</html>