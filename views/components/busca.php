<?php
require_once '../../controllers/UserControll.php';
$_SESSION['id_paciente'] = '';
$item = $_GET['termo'] ?? null;
$id = $_GET['medico'] ?? null;
if($item && !empty($item)){
    $dado = buscarPaciente($item);
}else{
    $dado = pacienteMedicos($id);
}
foreach ($dado as $paciente){
    $valor = htmlspecialchars($paciente['tipo'], ENT_QUOTES, 'UTF-8');
    $tipo = [
         1 => 'leve',
         2 => 'normal',
         3 => 'medio',
         4 => 'grave'
    ];
        echo " <a class='card " . $tipo[$valor] . "' href='medPaciente.php?paciente=" . htmlspecialchars($paciente['id'], ENT_QUOTES, 'UTF-8') . "'>
      <h1 class='card-name'>". htmlspecialchars($paciente['nome'], ENT_QUOTES, 'UTF-8') . "</h1>
      <div class='card-body'>
        <p><strong>Data nascimento:</strong> " . htmlspecialchars(traduz_data_para_exibir($paciente['data_nascimento']), ENT_QUOTES, 'UTF-8') . "</p>
        <p class='card-severity'>" . $tipo[$valor] . "</p>
        <p class='card-severity'>Cartão de saúde: " . htmlspecialchars($paciente['numero_de_carteirinha'], ENT_QUOTES, 'UTF-8') . "</p>
      </div>
    </a>";
    }
?>
