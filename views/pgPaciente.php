<?php
require_once '../controllers/UserControll.php';
verificarTipo(['paciente']);
getTelEmergencia();


?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/paciente.css">
    <link rel="icon" type="image/svg+xml" href="https://i.postimg.cc/xkk98Qgh/Med-Pass-Icon.png" alt="Med-Pass-Icon" />
    <title>Página do Paciente- Início</title>
</head>
<body>
    <header>
        <div class="container">
            <a href="#">
                <img src="https://i.postimg.cc/y8hmkJ4J/casa.png" alt="Botão de Início" class="btnCasa">
            </a>
        </div>
        <div class="container">
            <img src="https://i.postimg.cc/VSSzvwD8/Med-Pass-Logo-(resolucao-maior).png" alt="Logo MedPass">
        </div>
        <div class="container">
            <!--Menu hamburguer-->
            <div class="menu-icon" class="menu-icon">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </header>

    <nav id="sidebar">
        <div class="contSidebar">
            <h1>Ana Beatriz Pereira Costa</h1> <!-- nome do usuário: back tem q jogar aqui -->
        </div>

        <a href="" class="opcao">Prontuário</a> <!-- opções do sidebar q ta no figma -->
        <a href="" class="opcao">Cirurgia</a>
        <a href="" class="opcao">Exames</a>
        <a href="" class="opcao">Atestados/Declaração</a>
        <a href="" class="opcao">Laudo Médico</a>

        <div class="contSidebar">
            <a href="" class="config"><h3>Ajuda</h3></a>
            <a href="" class="config"><h3>Configurações</h3></a>
        </div>
    </nav>


    <!-- conteúdo da página -->
    <main>
        <div class="contMain">
            <div class="parte">
                <div class="cardSaude">
                    <div class="titulo">
                        <h1>Cartão Nacional de Saúde</h1>
                    </div>

                    <div class="contTexto">
                        <!-- grid pra fazer as colunas com as informações -->
                        <div class="coluna">

                            <div class="campo">
                                <p style="color: white;"><strong>Nome:</strong></p>
                                <p class="texto">Ana Beatriz Pereira Costa</p> <!-- Nome no banco -->
                                <input class="input hidden" type="text" value="Ana Beatriz Pereira Costa"> <!-- Tem que fazer algum jeito do valor desse input ser o mesmo do nome -->
                                <button class="editar">🖉</button>
                                <button class="salvar hidden">✔</button>
                            </div>

                            <div class="campo">
                                <p style="color: white;"><strong>CPF:</strong></p>
                                <p class="texto">123.456.789-00</p> <!-- Mesma coisa pra todos os campos -->
                                <input class="input hidden" type="text" value="123.456.789-00">
                                <button class="editar">🖉</button>
                                <button class="salvar hidden">✔</button>
                            </div>

                            <div class="campo">
                                <p style="color: white;"><strong>Altura:</strong></p>
                                <p class="texto">1,70m</p> <!-- Mesma coisa pra todos os campos -->
                                <input class="input hidden" type="text" value="1,70">
                                <button class="editar">🖉</button>
                                <button class="salvar hidden">✔</button>
                            </div>

                            <div class="campo">
                                <p style="color: white;"><strong>Alergia:</strong></p>
                                <p class="texto">Amendoim</p> <!-- Mesma coisa pra todos os campos -->
                                <input class="input hidden" type="text" value="Amendoim">
                                <button class="editar">🖉</button>
                                <button class="salvar hidden">✔</button>
                            </div>
                        </div>

                        <div class="coluna">

                            <div class="campo">
                                <p style="color: white;"><strong>Sexo:</strong></p>
                                <p class="texto">Feminino</p> <!-- Mesma coisa pra todos os campos -->
                                <input class="input hidden" type="text" value="Feminino">
                                <button class="editar">🖉</button>
                                <button class="salvar hidden">✔</button>
                            </div>
                            
                            <div class="campo">
                                <p style="color: white;"><strong>Sangue:</strong></p>
                                <p class="texto">A+</p> <!-- Mesma coisa pra todos os campos -->
                                <input class="input hidden" type="text" value="A+">
                                <button class="editar">🖉</button>
                                <button class="salvar hidden">✔</button>
                            </div>

                            <div class="campo">
                                <p style="color: white;"><strong>Peso:</strong></p>
                                <p class="texto">65kg</p> <!-- Mesma coisa pra todos os campos -->
                                <input class="input hidden" type="text" value="65kg">
                                <button class="editar">🖉</button>
                                <button class="salvar hidden">✔</button>
                            </div>
                        </div>

                        <div class="coluna">
                            
                            <div class="campo">
                                <p style="color: white;"><strong>Idade:</strong></p>
                                <p class="texto idade">30</p> <!-- Como idade será calculada à partir da data de nascimento, não é preciso fazer a edição -->
                                <span></span> <!-- ta aq so pro flex jogar o 30 no meio -->
                            </div>

                            <div class="campo">
                                <p style="color: white;"><strong>Nascimento:</strong></p>
                                <p class="texto">11/05/1995</p> <!-- Mesma coisa pra todos os campos -->
                                <input class="input hidden" type="date" value="1995-05-11">
                                <button class="editar">🖉</button>
                                <button class="salvar hidden">✔</button>
                            </div>

                            <div class="campo">
                                <p style="color: white;"><strong>N Carteirinha:</strong></p>
                                <p class="texto">12345678-0</p> <!-- Mesma coisa pra todos os campos -->
                                <input class="input hidden" type="text" value="12345678-0">
                                <button class="editar">🖉</button>
                                <button class="salvar hidden">✔</button>
                            </div>


                            <form method="POST">
                                <label>telefone de emergencia</label>
                                <input type="text" name="telefone" placeholder="telefone de emergencia">
                                <button type="submit">enviar</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
    
            <div class="parte">
                <div class="gridBtn">
                    <a href=""><button class="btnMenu">Medicamento em Uso</button></a>
                    <a href=""><button class="btnMenu">Contato de Emergência</button></a>
                    <a href=""><button class="btnMenu">Receita Médica</button></a>
                    <a href=""><button class="btnMenu">Histório Familiar</button></a>
                </div>
            </div>
        </div>
    </main>

    <script src="./scripts/menu.js"></script>
</body>
</html>