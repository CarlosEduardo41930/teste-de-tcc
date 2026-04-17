<?php

declare(strict_types=1);

require_once __DIR__ . '/../controllers/UserControll.php';
require_once __DIR__ . '/../config/conexao.php';

requireMedico();

$csrfToken = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrfToken;

$errorMessage = $_SESSION['error'] ?? '';
$successMessage = $_SESSION['success'] ?? '';
clearFlash();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Documento Médico</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; font-family: Arial, sans-serif; background: #f3f4f6; display: flex; justify-content: center; align-items: center; padding: 24px; }
        .card { width: 100%; max-width: 700px; background: #fff; border-radius: 20px; padding: 32px; box-shadow: 0 24px 80px rgba(15, 23, 42, 0.12); }
        h1 { margin-bottom: 18px; color: #111827; }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
        .field { margin-bottom: 18px; }
        label { display: block; font-size: .9rem; color: #374151; margin-bottom: 8px; }
        input, textarea, select { width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 12px; font-size: 1rem; }
        textarea { resize: vertical; min-height: 120px; }
        button { appearance: none; border: none; border-radius: 12px; padding: 14px 18px; background: #4338ca; color: #fff; font-size: 1rem; cursor: pointer; width: 100%; }
        button:hover { background: #312e81; }
        .message { margin-bottom: 18px; padding: 14px 16px; border-radius: 12px; font-size: .95rem; }
        .message.error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .message.success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .top-bar { display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 24px; }
        .top-bar a { color: #4338ca; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="card">
        <div class="top-bar">
            <div>
                <p>Olá, <strong><?= htmlspecialchars($_SESSION['user_first_name'] ?? 'Médico', ENT_QUOTES, 'UTF-8') ?></strong></p>
                <p style="color:#6b7280;">Preencha os dados e envie o PDF do documento médico.</p>
            </div>
            <a href="../controllers/logout.php">Sair</a>
        </div>

        <h1>Criar documento médico</h1>

        <?php if ($errorMessage): ?>
            <div class="message error"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <?php if ($successMessage): ?>
            <div class="message success"><?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <form action="../controllers/process_medical_upload.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

            <div class="row">
                <div class="field">
                    <label for="patient_cpf">CPF do paciente</label>
                    <input type="text" id="patient_cpf" name="patient_cpf" placeholder="CPF do paciente" required>
                </div>
                <div class="field">
                    <label for="tipo">Tipo de documento</label>
                    <select id="tipo" name="tipo" required>
                        <option value="receita">Receita</option>
                        <option value="relatorio">Relatório Médico</option>
                        <option value="laudo">Laudo</option>
                    </select>
                </div>
            </div>

            <div class="field">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" placeholder="Descrição do documento médico" maxlength="500" required></textarea>
            </div>

            <div class="row">
                <div class="field">
                    <label for="data_emissao">Data de emissão</label>
                    <input type="date" id="data_emissao" name="data_emissao" required>
                </div>
                <div class="field">
                    <label for="data_validade">Data de validade</label>
                    <input type="date" id="data_validade" name="data_validade">
                </div>
            </div>

            <div class="field">
                <label for="arquivo">Arquivo PDF</label>
                <input type="file" id="arquivo" name="arquivo" accept=".pdf,application/pdf" required>
                <small style="color:#6b7280;">Somente PDF. Tamanho máximo: 10 MB.</small>
            </div>

            <button type="submit">Enviar documento</button>
        </form>
    </div>
</body>
</html>
