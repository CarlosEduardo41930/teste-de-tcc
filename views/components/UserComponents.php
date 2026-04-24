<?php
//require_once '../../controllers/UserControll.php';

function showMedicamento(){

    $id = $_SESSION['id_usuario'];
    $dado = medicamento($id);

    if (empty($dado)) {
        $dado = [[
            "nome" => "Não tem medicamento",
            "dosagem" => "--------",
            "frequencia" => "--------"
        ]];
    }

    foreach ($dado as $remedio) {
        echo "<tr>
        <td>" . htmlspecialchars($remedio['nome'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($remedio['dosagem'], ENT_QUOTES, 'UTF-8') . "</td>
         <td>" . htmlspecialchars($remedio['frequencia'], ENT_QUOTES, 'UTF-8') . "</td>
         </tr>";
    }
}


function mensagemErro()
{
    if (!empty($_SESSION['erro'])) {
        echo "<ul>";

        foreach ($_SESSION['erro'] as $erro) {
            echo "<li>" . htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') . "</li>";
        }

        echo "</ul>";
        unset($_SESSION['erro']);
    }
}

function showReceitas(){
    $id = $_SESSION['id_usuario'];
    $dado = receitas($id);

    foreach ($dado as $receita) {
        echo "<div class='card'>
      <div class='card-name'>Dr. " . htmlspecialchars($receita['medico'], ENT_QUOTES, 'UTF-8') . "</div>
      <div class='card-meta'>
        <div class='card-date-label'>Data emissão:</div>
        <div class='card-date-value'>" . htmlspecialchars(traduz_data_para_exibir($receita['data']), ENT_QUOTES, 'UTF-8') . "</div>
        <div class='card-desc-label'>Descrição:</div>
        <div class='card-desc-value'>" . htmlspecialchars($receita['descricao'], ENT_QUOTES, 'UTF-8') . "</div>
      </div>
      <a href='arquivo.php?arquivo=" . htmlspecialchars($receita['id'], ENT_QUOTES, 'UTF-8') . "&tipo=minha%20receita' alt='Botão de mostrar o documento médico'
>
      <button  class='card-btn'>Abrir</button>
      </a>
    </div>";
    }

}

function arquivoIframe(){
    if (isset($_GET['arquivo'])) {
        $id = trim($_GET['arquivo']);
        echo "<iframe src='../servico/chamados/user_arquivo.php?arquivo=$id' alt='Mostrando documento médico em PDF'></iframe>";
    } else {
         $_SESSION['erro'][] = "Arquivo não informado";
    }
}



// function showPacienteMedicos(){
//     $_SESSION['id_paciente'] = '';
//     $dado = pacienteMedicos();

//     foreach ($dado as $paciente){
//         echo "<a> href='medPaciente.php?paciente='
//         <div>
//         <p>" . htmlspecialchars($paciente['nome'], ENT_QUOTES, 'UTF-8') . "</p>
//         <p>" . htmlspecialchars(traduz_data_para_exibir($paciente['data_nascimento']), ENT_QUOTES, 'UTF-8') . "</p>
//         <p>" . htmlspecialchars($paciente['tipo'], ENT_QUOTES, 'UTF-8') . "</p>
//         <p>" . htmlspecialchars($paciente['numero_de_carteirinha'], ENT_QUOTES, 'UTF-8') . "</p>
//         </div> 
//         </a>";
//     }
// }


function mensagemSucesso()
{
    if (!empty($_SESSION['sucesso'])) {
        echo "<p>" . htmlspecialchars($_SESSION['sucesso'], ENT_QUOTES, 'UTF-8') . "</p>";
        unset($_SESSION['sucesso']);
    }
}