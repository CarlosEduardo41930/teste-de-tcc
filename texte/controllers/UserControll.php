<?php

declare(strict_types=1);

/**
 * UserControll.php
 *
 * Contém funções de autenticação e autorização comuns entre as páginas.
 *
 * - requireLogin(): exige sessão ativa
 * - requireMedico(): exige que o usuário seja médico
 * - addFlash() / clearFlash(): armazenam mensagens de erro/sucesso temporárias
 */

session_start();

function requireLogin(): void
{
    // Verifica se a sessão contém usuário válido.
    if (empty($_SESSION['user_id']) || empty($_SESSION['user_nivel'])) {
        $_SESSION['error'] = 'Por favor, faça login antes de acessar esta página.';
        header('Location: ../views/login.php');
        exit();
    }
}

function requireMedico(): void
{
    // Primeiro garante que há login.
    requireLogin();

    // Verifica se o usuário logado tem nível de médico.
    if ($_SESSION['user_nivel'] !== 'medico') {
        $_SESSION['error'] = 'Acesso negado: somente médicos podem acessar esta área.';
        header('Location: ../views/login.php');
        exit();
    }
}

function addFlash(string $message): void
{
    // Armazena mensagem de erro para ser exibida na próxima página.
    $_SESSION['error'] = $message;
}

function clearFlash(): void
{
    // Remove mensagens temporárias da sessão.
    unset($_SESSION['error'], $_SESSION['success']);
}
