<?php
require_once '../controllers/UserControll.php';
require_once './components/UserComponents.php';
verificarTipo(['medico']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    voce chegou ao arquivo do medico 
    <br><br>
   <a href="../controllers/logout.php">sair</a>
   <input type="text" id="busca" placeholder="Buscar problema de saúde...">

<div id="resultado"></div>

<?php //showPacienteMedicos() ?> 
<script>
document.getElementById("busca").addEventListener("keyup", function() {
    let termo = this.value;
    let medico = <?php echo $_SESSION['id_usuario']; ?>;

    if(termo.length > 0){
        fetch("components/busca.php?termo=" + termo)
        .then(response => response.text())
        .then(data => {
            document.getElementById("resultado").innerHTML = data;
        });
    }else{
        fetch("components/busca.php?medico=" + medico)
        .then(response => response.text())
        .then(data => {
            document.getElementById("resultado").innerHTML = data;
        });
    }
});

window.onload = function() {
    let medico = <?php echo $_SESSION['id_usuario']; ?>;
    fetch("components/busca.php?medico=" + medico)
    .then(response => response.text())
    .then(data => {
        document.getElementById("resultado").innerHTML = data;
    });
};
</script>
   
  

</body>
</html>