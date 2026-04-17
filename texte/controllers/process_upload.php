<?php

declare(strict_types=1);

/**
 * process_upload.php
 *
 * Recebe o formulário de cadastro de nota fiscal, valida o usuário,
 * faz a inserção inicial no banco e delega o envio do arquivo ao UploadService.
 */

require_once __DIR__ . '/../servico/UploadService.php';
require_once __DIR__ . '/../models/NotaFiscalRepository.php';
require_once __DIR__ . '/../config/conexao.php';

// ---------------------------------------------------------------------------
// Validação básica da requisição
// ---------------------------------------------------------------------------
// Formulário de nota fiscal deve ser enviado via POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json; charset=utf-8');
    exit(json_encode(['error' => 'Método não permitido.']));
}

// Inicia sessão para validar usuário autenticado.
session_start();
header('Content-Type: application/json; charset=utf-8');

// Validação de CSRF para evitar envios forjados.
if (
    empty($_POST['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])
) {
    http_response_code(403);
    exit(json_encode(['error' => 'Token CSRF inválido.']));
}

try {
    $pdo = getPdo();
} catch (PDOException $e) {
    http_response_code(500);
    exit(json_encode(['error' => 'Falha na conexão com o banco de dados.']));
}

// ---------------------------------------------------------------------------
// Dados do usuário autenticado (da sessão) e do formulário
// ---------------------------------------------------------------------------

// Recupera usuário autenticado da sessão.
$userId        = (int) ($_SESSION['user_id']         ?? 0);
$userFirstName =       $_SESSION['user_first_name']  ?? '';

if ($userId === 0 || empty($userFirstName)) {
    http_response_code(401);
    exit(json_encode(['error' => 'Usuário não autenticado.']));
}

// Verifica se o arquivo foi enviado no formulário.
if (!isset($_FILES['arquivo'])) {
    http_response_code(422);
    exit(json_encode(['error' => 'Arquivo não foi enviado.']));
}

// Sanitiza os campos do formulário de nota fiscal.
$nomeArquivo = trim($_POST['nome_arquivo']  ?? '');
$descricao   = trim($_POST['descricao']     ?? '');
$dataEmissao = trim($_POST['data_emissao']  ?? '');
$valor       = filter_input(INPUT_POST, 'valor', FILTER_VALIDATE_FLOAT);

// Verifica que todos os campos obrigatórios foram preenchidos corretamente.
if (empty($nomeArquivo) || empty($descricao) || empty($dataEmissao) || $valor === false) {
    http_response_code(422);
    exit(json_encode(['error' => 'Todos os campos são obrigatórios.']));
}

// Valida o formato da data para evitar valores inválidos.
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataEmissao)) {
    http_response_code(422);
    exit(json_encode(['error' => 'Data de emissão inválida.']));
}

$baseUploadDir = __DIR__ . '/../uploads/notas';

$repository    = new NotaFiscalRepository($pdo);
$uploadService = new UploadService($baseUploadDir);
$notaId        = null;

try {
    $pdo->beginTransaction();

    // Cria o registro da nota fiscal no banco antes de efetuar o upload.
    $notaId = $repository->insert($userId, $descricao, $dataEmissao, (float) $valor);

    // Grava o PDF e retorna o caminho relativo para armazenar no banco.
    $filePath = $uploadService->handleUpload(
        $_FILES['arquivo'],
        $userFirstName,
        $userId,
        $nomeArquivo,
        $notaId
    );

    // Atualiza o registro com o caminho final do arquivo.
    $repository->updateFilePath($notaId, $filePath);
    $pdo->commit();

    http_response_code(201);
    echo json_encode([
        'success'  => true,
        'nota_id'  => $notaId,
        'arquivo'  => $filePath,
        'mensagem' => 'Nota fiscal cadastrada com sucesso.',
    ]);

} catch (RuntimeException $e) {
    // Erros de validação ou upload recebem rollback e exclusão do registro.
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    if ($notaId !== null) {
        $repository->delete($notaId);
    }

    http_response_code(422);
    echo json_encode(['error' => $e->getMessage()]);

} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log('[NotaFiscal] Erro inesperado: ' . $e->getMessage());

    http_response_code(500);
    echo json_encode(['error' => 'Erro interno. Tente novamente mais tarde.']);
}
