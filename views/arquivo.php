<?php
require_once '../controllers/UserControll.php';
require_once './components/UserComponents.php';
//$titulo = ucwords($_GET['tipo']);
?>
<!--  
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?=  $titulo; ?></title>


<style>
body {
    margin: 0;
    font-family: Arial;
    background: #f1f5f9;
}


.header {
    background: #0f766e;
    color: white;
    padding: 15px;
    text-align: center;
}


.topo {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    background: #e2e8f0;
}


.viewer {
    width: 90%;
    height: 80vh;
    margin: 20px auto;
    background: white;
    border-radius: 10px;
    overflow: hidden;
}


iframe {
    width: 100%;
    height: 100%;
    border: none;
}
</style>
</head>


<body>


<div class="header">MedPass</div>


<div class="topo">
    <a href="javascript:history.back()">← Voltar</a>
    <strong><?=  $titulo ?></strong>
    <div></div>
</div>
<?php mensagemErro();?>


<div class="viewer">
    <?php arquivoIframe(); ?>
</div>


</body>
</html>
-->