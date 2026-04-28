<?php
require_once '../controllers/UserControll.php';
require_once './components/UserComponents.php';
verificarTipo(['medico']);
informacaoMedica();
echo $_SESSION['id_usuario'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Selecione um Paciente</title>
  <link rel="icon" type="image/svg+xml" href="https://i.postimg.cc/xkk98Qgh/Med-Pass-Icon.png" alt="Med-Pass-Icon" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="./styles/medPaciente.css">
</head>
<body>
    <br><br>
   <a href="../controllers/logout.php">sair</a>
   <div class="search-wrapper">
      <input class="search-input" id="busca" type="text" placeholder="Buscar paciente..." />
      <button class="clear-btn" onclick="document.querySelector('.search-input').value = ''">
        <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>

<div id="resultado" class="grid"></div>



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