<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
    $_SESSION['erro'] = [];
}

require __DIR__ . "/../models/UserModel.php";
require __DIR__ . '/../config/conexao.php';
require __DIR__ . '/security.php';
require_once __DIR__ . '/../servico/chamados/guardar_arquivo.php';
require_once __DIR__ . '/../servico/chamados/enviar_email.php';

// function verificarConexao(){

//     }

function verificarTipo($niveisPermitidos)
{
    if (!isset($_SESSION['id_usuario'])) { // se o id tiver nulo, manda devolta pro login
        header('Location: login.php');
        exit();
    }
    if (!in_array($_SESSION['nivel'], $niveisPermitidos)) { //verifica se o nivel é permitido, se nao for, vai para acesso_negado
        header('Location: acesso_negado.php');
        exit();
    }
}

function verificarLogadoTipo()
{


    if (!isset($_SESSION['id_usuario'])) {
        return;
    }


    $tipo = $_SESSION['nivel'] ?? 'default';

    switch ($tipo) {
        case 'medico':
            header("Location: pgMedico.php");
            break;

        case 'paciente':
            header("Location: pgPaciente.php");
            break;
    }
    exit;
}


function getUser()
{

    global $pdo;

    if (isset($_SESSION['nivel']) && $_SESSION['nivel'] == 'paciente') {
        $nivel = $_SESSION['nivel'];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $nome = sanitizar($_POST['nome'] ?? '', 'nome');
            $email = sanitizar($_POST['email'] ?? '', 'email');
            $senha = trim($_POST['senha']);
            $confirmar_senha = trim($_POST['confirmar_senha']);
            $cpf = trim($_POST['cpf']);
            $telefone = sanitizar($_POST['telefone'] ?? '', 'inteiro');
            $genero = sanitizar($_POST['genero'] ?? '', 'texto');
            $bDate = trim($_POST['bDate']);

            validarSenha($senha);
            confirmarSenha($senha, $confirmar_senha);


            if (empty($_SESSION['erro'])) {
                setPaciente($pdo, $nome, $email, $senha, $confirmar_senha, $cpf, $telefone, $genero, $nivel, $bDate);
            }
        }
    } elseif (isset($_SESSION['nivel']) && $_SESSION['nivel'] == 'medico') {

        $nivel = $_SESSION['nivel'];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $nome = sanitizar($_POST['nome'] ?? '', 'nome');
            $email = sanitizar($_POST['email'] ?? '', 'email');
            $senha = trim($_POST['senha']);
            $confirmar_senha = trim($_POST['confirmar_senha']);
            $cpf = trim($_POST['cpf']);
            $crm = trim($_POST['crm']);   // corrigido
            $telefone = trim($_POST['telefone']);
            $especialidade = trim($_POST['especialidade']);
            $genero = trim($_POST['genero']);

            validarSenha($senha);
            confirmarSenha($senha, $confirmar_senha);

            if (empty($_SESSION['erro'])) {
                setMedico($pdo, $nome, $email, $senha, $confirmar_senha, $cpf, $crm, $telefone, $especialidade, $genero, $nivel);
            }
        }
    } else {

        header("Location: btn_cadastro.php");
        exit();
    }
}

function validateUser()
{
    global $pdo;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $senha = trim($_POST['senha']);
        $cpf = trim($_POST['cpf']);

        if (empty($_SESSION['erro'])) {
            validar($pdo, $senha, $cpf);
        }
    }
}

function medicamento($id, $tipo)
{
    global $pdo;
    if($tipo === 'paciente'){
        return getMedicamentoPaciente($pdo, $id);
    }elseif ($tipo === 'medico') {
        return getMedicamentoMedico($pdo, $id);
    }
    else {
        $_SESSION['erro'][] = "usuário não encontrado";
    }
    
}


function getTelEmergencia()
{
    global $pdo;
    if (!isset($_SESSION['id_usuario'])) {
        die("Usuário não autenticado");
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $telefone = $_POST['telefone'] ?? null;
        $paciente_id = $_SESSION['id_usuario'];
        if ($telefone) {
            setTelEmergencia($pdo, $telefone, $paciente_id);
        }
    }
}

function showTelEmergencia()
{
    global $pdo;

    $paciente_id = $_SESSION['id_usuario'];
    $telefoneDeEmergencia = getTelEmergenciaDataBase($pdo, $paciente_id);
}

function receitas($id)
{
    global $pdo;
    return getReceitasMedicas($pdo, $id);
}

function arquivo()
{
    global $pdo;
    $id = trim($_GET['arquivo']);
    $dados = getArquivo($pdo, $id);
    $caminhoRelativo = str_replace('\\', '/', $dados['caminho']);
    $arquivo = realpath(__DIR__ . '/../documento/' . $caminhoRelativo);

    // Segurança
    if (!$arquivo || !file_exists($arquivo)) {
        http_response_code(404);
        $_SESSION['erro'][] = "Arquivo não encontrado.";
    }

    // Cabeçalhos corretos para exibir no navegador
    header("Content-Type: application/pdf");
    header("Content-Disposition: inline; filename=\"documento.pdf\"");
    header("Content-Length: " . filesize($arquivo));

    // Evita bloqueio em iframe
    header("X-Frame-Options: SAMEORIGIN");

    readfile($arquivo);
    exit;
}

function buscarPaciente($item)
{
    global $pdo;
    if (isset($item) || $item !== '' || $item !== null) {
        return getBusca($pdo, $item);
    }
    return;
}
function pacienteMedicos($id)
{
    global $pdo;
    return getPacienteMedicos($pdo, $id);
}

function sessionPaciente()
{
    global $pdo;
    $id = $_GET['paciente'] ?? '';
    $_SESSION['id_paciente'] = $id;
    $nome = getinformacaoPaciente($pdo, $id);
    $_SESSION['nome_paciente'] = $nome['nome'] ?? 'paciente';
}
function informacaoMedica()
{
    global $pdo;

    $usuarioId = (int)($_SESSION['id_usuario'] ?? 0);
    if (!$usuarioId) {
        unset($_SESSION['id_medico'], $_SESSION['nome_medico']);
        return false;
    }

    $medico = getMedicoIdByUsuarioId($pdo, $_SESSION['id_usuario']);
    if (!$medico || empty($medico['id'])) {
        return false;
    }

    

    return true;
}
function uploadArquivoI()
{
    global $pdo;

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return;
    }

    try {
        $nome = sanitizar($_POST['nome'] ?? '', 'nome');
        $descricao = sanitizar($_POST['descricao'] ?? '', 'texto');
        $data_emissao = trim($_POST['data_emissao'] ?? '');
        $data_validade = trim($_POST['data_validade'] ?? '');
        $tipo = trim($_POST['tipo'] ?? '');
        $usuarioMedicoId = (int)($_SESSION['id_usuario'] ?? 0);
        $paciente = (int)($_SESSION['id_paciente'] ?? 0);

        if (!$usuarioMedicoId || !$paciente) {
            throw new RuntimeException('Sessão inválida. Faça login novamente.');
        }

        if ($nome === '' || $descricao === '' || $data_emissao === '' || $tipo === '') {
            throw new RuntimeException('Preencha todos os campos obrigatórios.');
        }

        if (!isset($_FILES['arquivo'])) {
            throw new RuntimeException('Arquivo não enviado.');
        }

        if (empty($_SESSION['id_medico']) && !informacaoMedica()) {
            throw new RuntimeException('Médico não encontrado no sistema. Faça login novamente.');
        }

        $medico = (int)($_SESSION['id_medico'] ?? 0);
        if (!$medico) {
            throw new RuntimeException('Médico não encontrado no sistema. Faça login novamente.');
        }


        $pdo->beginTransaction();

        $id = setArquivo($pdo, $nome, $descricao, $data_emissao, $data_validade, $tipo, 'ativo', $medico, $paciente);
        if (!$id) {
            throw new RuntimeException('Falha ao cadastrar arquivo no banco de dados.');
        }
        $id = (int)$id;

        $nameArquivo = pathinfo($_FILES['arquivo']['name'] ?? '', PATHINFO_FILENAME);
        if ($nameArquivo === '') {
            $nameArquivo = 'documento-medico';
        }

        $patientName = explode(' ', trim($_SESSION['nome_paciente'] ?? 'paciente'))[0] ?? 'paciente';
        $uploadService = new UploadService(__DIR__ . '/../documento');
        $filePath = $uploadService->handleUpload(
            $_FILES['arquivo'],
            $patientName,
            $paciente,
            $nameArquivo,
            $id
        );


        if (!updateArquivo($pdo, $id, $filePath)) {
            throw new RuntimeException('Erro ao atualizar o caminho do arquivo no banco de dados.');
        }

        $pdo->commit();

        $_SESSION['success'] = 'Documento médico enviado com sucesso.';
        header('Location: ../views/medPaciente.php?paciente=' . $_SESSION['id_paciente']);
        exit();
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        } elseif (isset($id) && is_int($id)) {
            deleteArquivo($pdo, $id);
        }

        $_SESSION['erro'][] = $e->getMessage();
        header('Location: ../views/adicionarDocumento.php');
        exit();
    }
}

function mudarSenha(){
    global $pdo;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = trim($_POST['cpf']);
    $usuario = getinformacaoUsuario($pdo, $cpf);
    $novaSenha = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
    $resultado = updateUsuario($pdo, $usuario['id'], $hash);
    if ($resultado === true) {
        $_SESSION['sucesso'] = "Um email de recuperação de senha foi enviado para o seu endereço de email cadastrado.";
    }
    enviarEmail($usuario['email'], $novaSenha);
    }
    

}

function repositorio($id, $tipo){
    global $pdo;
    return getRepositorio($pdo, $id, $tipo);
}

function excluirPorId(){
    global $pdo;
    $id = $_GET['id'] ?? '';
    $nivel = $_SESSION['nivel'] ?? '';

    if ($nivel !== 'medico'){
        $_SESSION['erro'][] = "Nivel não permitido.";
        return false;
    }
    $dado = deletePorId($pdo, $id);

    if($dado){
        header("Location: ../views/medicamento_uso.php");
        exit();
    }


}