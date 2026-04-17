<?php

declare(strict_types=1);

/**
 * authenticate.php
 *
 * Processa o formulário de login do médico.
 * Recebe CPF e senha, valida o usuário no banco e inicia a sessão.
 * Se o usuário não for médico, redireciona de volta para o login.
 */

require_once __DIR__ . '/UserControll.php';
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../models/UserModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/login.php');
    exit();
}

// Limpa o CPF deixando apenas números e remove espaços da senha.
$cpf = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
$senha = trim((string) ($_POST['senha'] ?? ''));

// Verifica campos obrigatórios antes de acessar o banco.
if ($cpf === '' || $senha === '') {
    $_SESSION['error'] = 'CPF e senha são obrigatórios.';
    header('Location: ../views/login.php');
    exit();
}

try {
    $pdo = getPdo();
    $userModel = new UserModel($pdo);

    // Autentica o usuário.
    $usuario = $userModel->authenticateUser($cpf, $senha);

    if (!$usuario) {
        $_SESSION['error'] = 'CPF ou senha incorretos.';
        header('Location: ../views/login.php');
        exit();
    }

    // Salva dados essenciais na sessão para autorizar o acesso.
    $_SESSION['user_id'] = (int) $usuario['id'];
    $_SESSION['user_nivel'] = $usuario['nivel'];
    $_SESSION['user_first_name'] = explode(' ', trim($usuario['nome']))[0] ?? '';

    // Apenas médicos podem continuar; caso contrário, encerra a sessão.
    if ($usuario['nivel'] !== 'medico') {
        session_destroy();
        session_start();
        $_SESSION['error'] = 'Acesso negado: somente médicos podem entrar aqui.';
        header('Location: ../views/login.php');
        exit();
    }

    // Redireciona para a página de upload de documentos médicos.
    header('Location: ../views/pgMedico.php');
    exit();
} catch (Throwable $e) {
    $_SESSION['error'] = 'Erro ao processar o login. Tente novamente.';
    header('Location: ../views/login.php');
    exit();
}
