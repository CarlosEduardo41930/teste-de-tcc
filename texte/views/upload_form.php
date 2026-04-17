<?php
session_start();

// Gera token CSRF se ainda não existir
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Nota Fiscal</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: system-ui, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 40px 16px;
            min-height: 100vh;
            margin: 0;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,.1);
            padding: 36px 40px;
            width: 100%;
            max-width: 520px;
        }

        h1 { font-size: 1.4rem; margin: 0 0 28px; color: #1a1a2e; }

        .field { margin-bottom: 20px; }

        label {
            display: block;
            font-size: .875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        input, textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: .95rem;
            transition: border-color .2s;
            outline: none;
        }

        input:focus, textarea:focus { border-color: #4f46e5; }

        .hint { font-size: .78rem; color: #6b7280; margin-top: 4px; }

        button {
            width: 100%;
            padding: 12px;
            background: #4f46e5;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s;
        }

        button:hover:not(:disabled) { background: #4338ca; }
        button:disabled { opacity: .6; cursor: not-allowed; }

        #feedback {
            margin-top: 20px;
            padding: 12px 16px;
            border-radius: 6px;
            font-size: .9rem;
            display: none;
        }

        #feedback.success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        #feedback.error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    </style>
</head>
<body>
<div class="card">
    <h1>📄 Cadastrar Nota Fiscal</h1>

    <form id="notaForm" enctype="multipart/form-data" novalidate>

        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <div class="field">
            <label for="nome_arquivo">Nome do arquivo</label>
            <input
                type="text"
                id="nome_arquivo"
                name="nome_arquivo"
                placeholder="ex: nota-janeiro"
                maxlength="100"
                required
            >
            <p class="hint">O sistema adicionará automaticamente o ID ao nome. Não inclua extensão.</p>
        </div>

        <div class="field">
            <label for="descricao">Descrição</label>
            <textarea
                id="descricao"
                name="descricao"
                rows="3"
                placeholder="Descrição da nota fiscal..."
                maxlength="500"
                required
            ></textarea>
        </div>

        <div class="field">
            <label for="data_emissao">Data de emissão</label>
            <input
                type="date"
                id="data_emissao"
                name="data_emissao"
                required
            >
        </div>

        <div class="field">
            <label for="valor">Valor (R$)</label>
            <input
                type="number"
                id="valor"
                name="valor"
                step="0.01"
                min="0.01"
                placeholder="0,00"
                required
            >
        </div>

        <div class="field">
            <label for="arquivo">Arquivo PDF</label>
            <input
                type="file"
                id="arquivo"
                name="arquivo"
                accept=".pdf,application/pdf"
                required
            >
            <p class="hint">Somente arquivos PDF. Tamanho máximo: 10 MB.</p>
        </div>

        <button type="submit" id="submitBtn">Cadastrar Nota Fiscal</button>
    </form>

    <div id="feedback"></div>
</div>

<script>
document.getElementById('notaForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const feedback  = document.getElementById('feedback');
    const submitBtn = document.getElementById('submitBtn');
    const fileInput = document.getElementById('arquivo');
    const file      = fileInput.files[0];

    feedback.style.display = 'none';

    // Validação client-side do tipo de arquivo (complementar — a real é no servidor)
    if (file && file.type !== 'application/pdf') {
        showFeedback('error', 'Somente arquivos PDF são aceitos.');
        return;
    }

    // Validação de tamanho (10 MB)
    if (file && file.size > 10 * 1024 * 1024) {
        showFeedback('error', 'O arquivo excede o limite de 10 MB.');
        return;
    }

    submitBtn.disabled    = true;
    submitBtn.textContent = 'Enviando…';

    try {
        const formData = new FormData(this);
        const response = await fetch('../controllers/process_upload.php', {
            method: 'POST',
            body: formData,
        });

        const result = await response.json();

        if (response.ok && result.success) {
            showFeedback('success', `✅ ${result.mensagem} (ID: ${result.nota_id})`);
            this.reset();
        } else {
            showFeedback('error', `❌ ${result.error ?? 'Erro ao cadastrar nota.'}`);
        }
    } catch {
        showFeedback('error', '❌ Erro de conexão. Tente novamente.');
    } finally {
        submitBtn.disabled    = false;
        submitBtn.textContent = 'Cadastrar Nota Fiscal';
    }
});

function showFeedback(type, message) {
    const el   = document.getElementById('feedback');
    el.className     = type;
    el.textContent   = message;
    el.style.display = 'block';
    el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}
</script>
</body>
</html>
