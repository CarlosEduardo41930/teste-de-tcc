<?php
require_once '../controllers/UserControll.php';
require_once './components/UserComponents.php';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MedPass – Receitas Médicas</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="./styles/receitas.css"/>
</head>
<body>

<!-- TOP BAR -->
  <div class="topbar">
    <a href="#" class="topbar-icon">
      <i class="fa-solid fa-house"></i>
    </a>
    <img src="https://i.postimg.cc/VSSzvwD8/Med-Pass-Logo-(resolucao-maior).png" alt="Logo MedPass" class="logo-img" />
    <a href="#" class="topbar-icon">
      <i class="fa-solid fa-bars"></i>
    </a>
  </div>

<!-- SUBHEADER -->
<div class="subheader">
  <a class="back-btn" href="#">
    <i class="fa-solid fa-chevron-left"></i> Voltar
  </a>
  <div class="subheader-title">Receitas médicas</div>
  <div class="subheader-spacer"></div>
</div>

<!-- MAIN CONTENT -->
<main>
  <div class="cards-grid">

     <?php showReceitas() ?>

  </div>

<!-- BOTTOM ROW -->
<div class="bottom-row">
  <div class="nav-cards">
    
    <div class="nav-card active">
      <i class="fa-solid fa-pills"></i>
      <span class="nav-card-label">Medicamentos<br>em Uso</span>
    
    </div>
    <div class="nav-card">
      <i class="fa-solid fa-phone-flip"></i>
      <span class="nav-card-label">Contato de<br>Emergência</span>
    </div>

    <div class="nav-card">
      <i class="fa-solid fa-clock-rotate-left"></i>
      <span class="nav-card-label">Histórico<br>Familiar</span>
    </div>
  </div>

  <div class="nav-number-badge">
    <p>Número da carterinha:</p>
    <span>12345678-0</span>
  </div>

</div>
</main>
</body>
</html>