<?php

session_start();

if (isset($_POST['nivel']) && $_POST['nivel'] == 'medico') {

    $_SESSION['nivel'] = $_POST['nivel'];

    header('Location: ../views/cadastro_medico.php');
    exit();

} elseif (isset($_POST['nivel']) && $_POST['nivel'] == 'paciente') {

    $_SESSION['nivel'] = $_POST['nivel'];

    header('Location: ../views/cadastro_paciente.php');
    exit();
}
?>