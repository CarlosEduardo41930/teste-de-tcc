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
    <?php mensagemErro() ?>
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
            <div class="row">
                <div class="field">
                    <label for="data_emissao">Data de emissão</label>
                    <input type="date" id="data_emissao" name="data_emissao" required>
                </div>
                <div class="field">
                    <label for="data_validade">Data de validade</label>
                    <input type="date" id="data_validade" name="data_validade">
                </div>
            </div>
            <div class="field">
                    <label for="tipo">Tipo de documento</label>
                    <select id="tipo" name="tipo" required>
                        <option value="receita">Receita</option>
                        <option value="relatorio">Relatório Médico</option>
                        <option value="laudo">Laudo</option>
                    </select>
                </div>
                <button type="submit">Enviar documento</button>
    </form>

</body>
</html>