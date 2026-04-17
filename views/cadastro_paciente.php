<?php
require_once '../controllers/UserControll.php';
require_once './components/UserComponents.php';

getUser();
verificarLogadoTipo();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="./styles/style.css">
    <link rel="icon" type="image/svg+xml" href="https://i.postimg.cc/xkk98Qgh/Med-Pass-Icon.png" alt="Med-Pass-Icon" />
    <title>MedPass- Cadastro paciente</title>
</head>
<body>
<main>
    <div class="erro">   <!-- fronte meher  +++++++++++++++++++++++++++++++++++++++++++ -->
        <?php mensagemErro(); ?> 
    </div>
        <div class="container-1">
            <!-- Botão para voltar pra página anterior :) -->
            <a href="btn_cadastro.php" class="botaoVoltar">&larr; Voltar</a>
    
            <img src="https://i.postimg.cc/VSSzvwD8/Med-Pass-Logo-(resolucao-maior).png" alt="Logo MedPass"></img>
            <h1>Cadastro de Paciente</h1>
            <form method="post" class="form-grid">
                <!-- Divisão em colunas pra conseguir fazer igual no protótipo -->
                <div class="coluna">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" placeholder="Digite seu nome aqui" required>
    
                    <label for="email">E-mail</label>
                    <input type="email" name="email" placeholder="Digite seu e-mail aqui" required>
    
                    <label for="senha">Senha</label>
                    <input type="password" name="senha" placeholder="Digite sua senha" required>
    
                    <label for="confirmar_senha">Confirmar Senha</label>
                    <input type="password" name="confirmar_senha" placeholder="Confirmar sua senha" required>
                    <p>Já possui uma conta? <strong><a href="#" class="ajuda">Faça login aqui</a></strong></p>
                    <p>Precisa de ajuda? <strong><a href="#" class="ajuda">Clique aqui!</a></strong></p>
                </div>

                <div class="coluna">
                    <label for="bDate">Data de nascimento</label> <!-- Adicionar nascimento pq ta no figma -->
                    <input type="date" name="bDate" required>

                    <label for="cpf">CPF</label>
                    <input name="cpf" type="text" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" maxlength="14" placeholder="Digite seu CPF aqui" required>
    
                    <label for="telefone">Telefone</label>
                    <input type="tel" name="telefone" required placeholder="Digite seu telefone">
    
                    <label for="genero">Gênero</label>
                    <select name="genero" required>
                        <option value="">Selecione seu gênero</option>
                        <option value="m">Masculino</option>
                        <option value="f">Feminino</option>
                        <option value="i">Indefinido</option>
                    </select>
    
                    <button class="btn" type="submit">Cadastrar</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>

