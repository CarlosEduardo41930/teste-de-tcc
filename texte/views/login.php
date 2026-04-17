<?php

declare(strict_types=1);

require_once __DIR__ . '/../controllers/UserControll.php';

if (!empty($_SESSION['user_id']) && !empty($_SESSION['user_nivel']) && $_SESSION['user_nivel'] === 'medico') {
    header('Location: pgMedico.php');
    exit();
}

$errorMessage = $_SESSION['error'] ?? '';
clearFlash();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Texte</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: Arial, sans-serif; background: #eef2ff; }
        .card { width: 100%; max-width: 420px; background: #ffffff; padding: 32px; border-radius: 16px; box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12); }
        h1 { font-size: 1.8rem; color: #1f2937; margin-bottom: 24px; text-align: center; }
        label { display: block; font-size: .9rem; color: #374151; margin-bottom: 8px; }
        input { width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; margin-bottom: 18px; font-size: 1rem; }
        button { width: 100%; padding: 14px; border: none; border-radius: 10px; background: #4338ca; color: #fff; font-size: 1rem; cursor: pointer; }
        button:hover { background: #3730a3; }
        .message { margin-bottom: 18px; padding: 12px 14px; border-radius: 10px; color: #b91c1c; background: #fee2e2; border: 1px solid #fca5a5; }
        .footer { margin-top: 16px; font-size: .875rem; text-align: center; color: #4b5563; }
        .footer a { color: #4338ca; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Entrar no Texte</h1>
        <?php if (!empty($errorMessage)): ?>
            <div class="message"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <form action="../controllers/authenticate.php" method="post">
            <label for="cpf">CPF</label>
            <input type="text" id="cpf" name="cpf" placeholder="Digite seu CPF" required>

            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>

            <button type="submit">Entrar</button>
        </form>
        <div class="footer">
            Acesso apenas para médicos. Se não for médico, fale com o administrador do sistema.
        </div>
    </div>
</body>
</html>
