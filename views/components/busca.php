<?php
require_once '../../controllers/UserControll.php';
$_SESSION['id_paciente'] = '';
$item = $_GET['termo'] ?? null;
$id = $_GET['medico'] ?? null;
if($item && !empty($item)){
    $dado = buscarPaciente($item);
foreach ($dado as $paciente) {
    echo "<a href='medPaciente.php?paciente=" . htmlspecialchars($paciente['id'], ENT_QUOTES, 'UTF-8') . "'>
        <div>
        <p>" . htmlspecialchars($paciente['nome'], ENT_QUOTES, 'UTF-8') . "</p>
        <p>" . htmlspecialchars(traduz_data_para_exibir($paciente['data_nascimento']), ENT_QUOTES, 'UTF-8') . "</p>
        <p>" . htmlspecialchars($paciente['tipo'], ENT_QUOTES, 'UTF-8') . "</p>
        <p>" . htmlspecialchars($paciente['numero_de_carteirinha'], ENT_QUOTES, 'UTF-8') . "</p>
        </div> 
        </a>";
};
}elseif($id){
        
    $dado = pacienteMedicos($id);
    foreach ($dado as $paciente){
        echo "<a href='medPaciente.php?paciente=" . htmlspecialchars($paciente['id'], ENT_QUOTES, 'UTF-8') . "'>
        <div>
        <p>" . htmlspecialchars($paciente['nome'], ENT_QUOTES, 'UTF-8') . "</p>
        <p>" . htmlspecialchars(traduz_data_para_exibir($paciente['data_nascimento']), ENT_QUOTES, 'UTF-8') . "</p>
        <p>" . htmlspecialchars($paciente['tipo'], ENT_QUOTES, 'UTF-8') . "</p>
        <p>" . htmlspecialchars($paciente['numero_de_carteirinha'], ENT_QUOTES, 'UTF-8') . "</p>
        </div> 
        </a>";
    }
}

