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
    <title>MedPass- Cadastro</title>
</head>

<body>
    <main>
        <div class="container-1">
            <!-- Botão para voltar pra página anterior :) -->
            <a href="btn_cadastro.php" class="botaoVoltar">&larr; Voltar</a>

            <img src="https://i.postimg.cc/VSSzvwD8/Med-Pass-Logo-(resolucao-maior).png" alt="Logo MedPass"></img>
            <h1>Cadastro de Médico</h1>
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
                    <label for="cpf">CPF</label>
                    <input name="cpf" type="text" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" maxlength="14" placeholder="Digite seu CPF aqui" required>

                    <label for="crm">CRM</label>
                    <input name="crm" maxlength="12" type="number" placeholder="Digite seu CRM aqui" required>

                    <label for="telefone">Telefone</label>
                    <input type="tel" name="telefone" required placeholder="Digite seu telefone">

                    <label for="especialidade">Especialidade Médica:</label>
                    <select name="especialidade" id="especialidade">

                        <option value="">Selecione</option>

                        <optgroup label="Clínicas">
                            <option value="clinico_geral">Clínico Geral</option>
                            <option value="cardiologista">Cardiologista</option>
                            <option value="dermatologista">Dermatologista</option>
                            <option value="endocrinologista">Endocrinologista</option>
                            <option value="gastroenterologista">Gastroenterologista</option>
                            <option value="hematologista">Hematologista</option>
                            <option value="infectologista">Infectologista</option>
                            <option value="nefrologista">Nefrologista</option>
                            <option value="neurologista">Neurologista</option>
                            <option value="oncologista">Oncologista</option>
                            <option value="pneumologista">Pneumologista</option>
                            <option value="reumatologista">Reumatologista</option>
                        </optgroup>

                        <optgroup label="Faixa Etária">
                            <option value="pediatra">Pediatra</option>
                            <option value="geriatra">Geriatra</option>
                            <option value="hebiatra">Hebiatra</option>
                        </optgroup>

                        <optgroup label="Cirúrgicas">
                            <option value="cirurgiao_geral">Cirurgião Geral</option>
                            <option value="cirurgiao_cardiovascular">Cirurgião Cardiovascular</option>
                            <option value="neurocirurgiao">Neurocirurgião</option>
                            <option value="cirurgiao_plastico">Cirurgião Plástico</option>
                            <option value="cirurgiao_toracico">Cirurgião Torácico</option>
                            <option value="cirurgiao_vascular">Cirurgião Vascular</option>
                        </optgroup>

                        <optgroup label="Outras Especialidades">
                            <option value="psiquiatra">Psiquiatra</option>
                            <option value="oftalmologista">Oftalmologista</option>
                            <option value="otorrinolaringologista">Otorrinolaringologista</option>
                            <option value="urologista">Urologista</option>
                            <option value="ginecologista_obstetra">Ginecologista / Obstetra</option>
                            <option value="radiologista">Radiologista</option>
                            <option value="anestesiologista">Anestesiologista</option>
                            <option value="patologista">Patologista</option>
                            <option value="medicina_trabalho">Medicina do Trabalho</option>
                            <option value="medicina_esportiva">Medicina Esportiva</option>
                            <option value="medicina_emergencia">Medicina de Emergência</option>
                        </optgroup>

                    </select>

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