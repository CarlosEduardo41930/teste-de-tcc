<?php
require_once '../controllers/UserControll.php';

sessionPaciente();
verificarTipo(['medico']);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Criar novo</title>
  <link rel="icon" type="image/svg+xml" href="https://i.postimg.cc/xkk98Qgh/Med-Pass-Icon.png" alt="Med-Pass-Icon" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="./styles/medpaciente.css">
</head>
<body>

  <!-- ── HEADER ── -->
  <div class="topbar">
    <a href="#" class="topbar-icon">
      <i class="fa-solid fa-house"></i>
    </a>
    <img src="https://i.postimg.cc/VSSzvwD8/Med-Pass-Logo-(resolucao-maior).png" alt="Logo MedPass" class="logo-img" />
     <a href="#" class="topbar-icon">
      <i class="fa-solid fa-bars"></i>
    </a>
  </div>

  <!-- ── SUBHEADER / VOLTAR ── -->
  <div class="subheader">
    <a class="back-btn" href="#">
      <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
      Voltar
    </a>
    <span class="subheader-title">Criar novo....</span>
  </div>

  <!-- ── MAIN ── -->
  <main>
    <div class="action-grid">

      <!-- Adicionar Documento -->
      <a class="action-card" href="adicionar-documento.html">
        <span class="action-plus">+</span>
        <span class="action-label">Adicionar<br>Documento</span>
      </a>

      <!-- Adicionar Medicamento em uso -->
      <a class="action-card" href="adicionar-medicamento.html">
        <span class="action-plus">+</span>
        <span class="action-label">Adicionar<br>Medicamento em<br>uso</span>
      </a>

    </div>
  </main>

  <!-- ── HAMBURGUER ── -->
   <nav id="sidebar">
        <div class="contSidebar">
            <h1>Ana Beatriz Pereira Costa</h1> <!-- nome do usuário: back tem q jogar aqui -->
        </div>

        <a href="" class="opcao">Prontuário</a> <!-- opções do sidebar q ta no figma -->
        <a href="" class="opcao">Cirurgia</a>
        <a href="" class="opcao">Exames</a>
        <a href="" class="opcao">Atestados/Declaração</a>
        <a href="" class="opcao">Laudo Médico</a>

        <div class="contSidebar">
            <a href="" class="config"><h3>Ajuda</h3></a>
            <a href="" class="config"><h3>Configurações</h3></a>
        </div>
    </nav>

  <!-- ── PATIENT BADGE ── -->
  <div class="patient-badge">
    <div class="badge-label">Paciente:</div>
    <div class="badge-name">Ana Beatriz Pereira Costa</div>
    <div class="badge-severity">Comorbidade grave</div>
  </div>

  <script src="./scripts/menu.js"></script>
</body>
</html>
<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="adicionarDocumento.php">Adicionar Documento</a>
    <a href="repositorio.php?tipo=receitas&mensagem=receita">receita</a>
    <a href="medicamento_uso.php">medicamento uso</a>
</body>
</html> -->