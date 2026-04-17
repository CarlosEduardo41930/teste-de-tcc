<?php

declare(strict_types=1);

/**
 * process_medical_upload.php
 *
 * Recebe o formulário de documento médico enviado pelo médico autenticado.
 * Faz validação de CSRF, verifica paciente pelo CPF, insere o registro no banco
 * e utiliza UploadService para gravar o PDF na pasta do usuário.
 */

require_once __DIR__ . '/UserControll.php';
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../servico/UploadService.php';
require_once __DIR__ . '/../models/DocumentRepository.php';
require_once __DIR__ . '/../models/UserModel.php';

// Garante que somente médico autenticado pode acessar este processamento.
requireMedico();

// O envio do formulário deve ser via POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/pgMedico.php');
    exit();
}

// Validação básica de segurança: verifica token CSRF.
if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    addFlash('Token CSRF inválido.');
    header('Location: ../views/pgMedico.php');
    exit();
}

// Lê e sanitiza os dados do formulário.
$patientCpf = preg_replace('/\D/', '', $_POST['patient_cpf'] ?? '');
$descricao = trim((string) ($_POST['descricao'] ?? ''));
$tipo = trim((string) ($_POST['tipo'] ?? ''));
date_default_timezone_set('America/Sao_Paulo');
$dataEmissao = trim((string) ($_POST['data_emissao'] ?? ''));
$dataValidade = trim((string) ($_POST['data_validade'] ?? ''));
$status = 'ativo';

// Verifica campos obrigatórios do formulário.
if ($patientCpf === '' || $descricao === '' || $tipo === '' || $dataEmissao === '') {
    addFlash('Preencha todos os campos obrigatórios.');
    header('Location: ../views/pgMedico.php');
    exit();
}

// Verifica se o arquivo foi enviado no campo correto.
if (!isset($_FILES['arquivo'])) {
    addFlash('Arquivo não enviado.');
    header('Location: ../views/pgMedico.php');
    exit();
}

try {
    $pdo = getPdo();
    $pdo->beginTransaction();

    $userModel = new UserModel($pdo);

    // Busca o paciente pelo CPF informado no formulário.
    $patientRow = $userModel->getPacienteByCpf($patientCpf);

    if (!$patientRow) {
        throw new RuntimeException('Paciente não encontrado. Verifique o CPF informado.');
    }

    // Busca o médico logado pelo id de usuário da sessão.
    $doctorRow = $userModel->getMedicoByUserId($_SESSION['user_id']);

    if (!$doctorRow) {
        throw new RuntimeException('Médico não encontrado no sistema. Faça login novamente.');
    }

    // Extrai o nome do arquivo sem extensão para salvar como título do documento.
    $originalFileName = pathinfo($_FILES['arquivo']['name'] ?? '', PATHINFO_FILENAME);
    if ($originalFileName === '') {
        $originalFileName = 'documento-medico';
    }

    // Insere o registro do documento com caminho vazio para obter o ID.
    $repository = new DocumentRepository($pdo);
    $documentId = $repository->insert(
        (int) $doctorRow['id'],
        (int) $patientRow['paciente_id'],
        $originalFileName,
        $descricao,
        $tipo,
        $dataEmissao,
        $dataValidade !== '' ? $dataValidade : null,
        $status
    );

    // Extrai o primeiro nome do paciente para criar a pasta.
    $patientFirstName = explode(' ', trim($patientRow['paciente_nome']))[0] ?? 'paciente';

    // Grava o arquivo PDF na pasta do paciente e obtém o caminho relativo.
    $uploadService = new UploadService(__DIR__ . '/../uploads/documentos');
    $filePath = $uploadService->handleUpload(
        $_FILES['arquivo'],
        $patientFirstName,
        (int) $patientRow['paciente_id'],
        $originalFileName,
        $documentId
    );

    // Atualiza o registro no banco com o caminho final do arquivo.
    $repository->updateFilePath($documentId, $filePath);
    $pdo->commit();

    $_SESSION['success'] = 'Documento médico enviado com sucesso.';
    header('Location: ../views/pgMedico.php');
    exit();
} catch (Throwable $e) {
    // Se algo falhar, desfaz todas as alterações no banco.
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Remove o registro criado no banco para não deixar registro órfão.
    if (isset($documentId) && is_int($documentId)) {
        $repository->delete($documentId);
    }

    // Coloca a mensagem de erro na sessão e retorna para a página do médico.
    addFlash($e->getMessage());
    header('Location: ../views/pgMedico.php');
    exit();
}
