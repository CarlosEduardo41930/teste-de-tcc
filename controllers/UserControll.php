<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
    $_SESSION['erro'] = [];
}

require __DIR__ . "/../models/UserModel.php";
require __DIR__ . '/../config/conexao.php';
require __DIR__ . '/security.php';
require_once __DIR__ . '/../servico/chamados/guardar_arquivo.php';

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

function medicamento($id)
{
    global $pdo;
    return getMedicamentoPaciente($pdo, $id);
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
    $arquivo = $dados['caminho'];

    // Segurança
    if (!file_exists($arquivo)) {
        http_response_code(404);
        die("Arquivo não encontrado.");
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
    $_SESSION['nome_paciente'] = getinformacaoPaciente($pdo, $id);
}
function uploadArquivoI()
{
    global $pdo;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = sanitizar($_POST['nome'] ?? '', 'nome');
        $descricao = sanitizar($_POST['descricao'] ?? '', 'texto');
        $data_emissao = trim($_POST['data_emissao']);
        $data_validade = trim($_POST['data_validade']);
        $tipo = trim($_POST['tipo']);
        $status = 'ativo';
        $medico = $_SESSION['id_usuario'];
        $paciente = $_SESSION['id_paciente'];

        if ($nome === '' || $descricao === '' || $data_emissao === '' || $tipo === '' || !isset($_FILES['arquivo'])) {
            $_SESSION['erro'][] = "Preencha todos os campos obrigatórios.";
        }

        if (empty($_SESSION['erro'])) {
            $id = setArquivo($pdo, $nome, $descricao, $data_emissao, $data_validade, $tipo, $status, $medico, $paciente);
        }
        $nameArquivo = pathinfo($_FILES['arquivo']['name'] ?? '', PATHINFO_FILENAME);
        if ($nameArquivo === '') {
            $nameArquivo = 'documento-medico';
        }
        $patientName = explode(' ', trim($_SESSION['nome_paciente']))[0] ?? 'paciente';
        $uploadService = new UploadService(__DIR__ . '/../documento');
        $filePath = $uploadService->handleUpload(
            $_FILES['arquivo'],
            $patientName,
            $_SESSION['nome_paciente'],
            $nameArquivo,
            $id
        );
        
    }
}
