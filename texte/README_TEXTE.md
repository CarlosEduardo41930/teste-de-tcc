# Arquitetura do projeto `texte`

Este arquivo explica como os arquivos das pastas `controllers`, `models` e `servico` funcionam juntos.

## Estrutura principal

- `controllers/`
  - Contém os scripts que respondem a requisições HTTP.
  - Fazem validações, iniciam transações e orquestram a lógica entre banco e upload.

- `models/`
  - Contém classes que acessam e modificam o banco de dados.
  - Modelos representam as tabelas `arquivos` e `notas_fiscais`.

- `servico/`
  - Contém a classe `UploadService`.
  - Responsável por validação, sanitização, criação de pasta de usuário e gravação segura do arquivo.

## Como os arquivos trabalham juntos

### 1. Login do médico

- `views/login.php`
  - Exibe o formulário de login.
  - Envia CPF e senha para `controllers/authenticate.php`.

- `controllers/authenticate.php`
  - Recebe os dados do formulário.
  - Valida CPF e senha no banco usando `config/conexao.php`.
  - Se o usuário for médico, cria a sessão e redireciona para `views/pgMedico.php`.
  - Se não for médico, redireciona para o login com mensagem de erro.

### 2. Logout

- `controllers/logout.php`
  - Encerra a sessão do usuário.
  - Redireciona para `views/login.php`.

### 3. Cadastro de documento médico

- `views/pgMedico.php`
  - Exibe o formulário para o médico criar um documento médico.
  - O médico informa CPF do paciente, tipo de documento, descrição, datas e faz upload de PDF.

- `controllers/process_medical_upload.php`
  - Verifica CSRF e se o médico está autenticado.
  - Busca o paciente pelo CPF informado em `usuarios` + `paciente`.
  - Busca o médico pela sessão em `medico`.
  - Cria o registro do documento no banco via `models/DocumentRepository.php`.
  - Chama `servico/UploadService.php` para salvar o PDF com nome seguro.
  - Atualiza o caminho do arquivo no banco após o upload.

### 4. Upload de nota fiscal

- `views/upload_form.php`
  - Exibe um formulário de cadastro de nota fiscal tradicional.
  - Envia os dados para `controllers/process_upload.php`.

- `controllers/process_upload.php`
  - Valida CSRF e sessão do usuário.
  - Insere o registro da nota fiscal no banco via `models/NotaFiscalRepository.php`.
  - Chama `servico/UploadService.php` para gravar o arquivo PDF.
  - Atualiza o caminho do arquivo no banco após o upload.

## Responsabilidades dos arquivos

### controllers/authenticate.php

- Valida método de requisição POST.
- Lê CPF e senha.
- Busca usuário no banco.
- Define sessão do médico.
- Redireciona para `views/pgMedico.php`.

### controllers/logout.php

- Finaliza sessão.
- Redireciona para a tela de login.

### controllers/process_medical_upload.php

- Valida CSRF.
- Verifica dados obrigatórios.
- Busca paciente e médico no banco.
- Insere documento médico na tabela `arquivos`.
- Chama `UploadService` para salvar o PDF.
- Atualiza caminho do arquivo no registro.

### controllers/process_upload.php

- Valida CSRF.
- Verifica usuário autenticado.
- Insere nota fiscal em `notas_fiscais`.
- Faz o upload do PDF.
- Atualiza o campo `caminho_arquivo`.

### models/DocumentRepository.php

- Insere documentos médicos com caminho temporário vazio.
- Atualiza `caminho` depois do upload.
- Remove registro quando o upload falha.

### models/NotaFiscalRepository.php

- Insere nota fiscal com caminho inicial nulo.
- Atualiza caminho quando o upload é concluído.
- Remove registro em caso de falha.

### servico/UploadService.php

- Valida tipo/MIME e magic bytes para garantir PDF.
- Cria pasta de usuário com padrão `primeiroNome + idUsuario`.
- Sanitiza nomes de pasta e arquivo.
- Gera nome final `<nome-sanitizado>_<id>.pdf`.
- Move arquivo com `move_uploaded_file()`.
- Aplica permissões seguras e cria `.htaccess`.

## Comportamento geral

- O usuário (médico) deve fazer login antes de acessar `pgMedico.php`.
- O `UploadService` só grava PDFs e evita uploads maliciosos.
- Os repositórios apenas falam com o banco; não movem arquivos.
- Os controllers orquestram a operação completa.

## Observações importantes

- Mantenha `config/conexao.php` com os dados do MySQL corretos.
- O caminho `uploads/` deve existir e ter permissão de escrita pelo servidor.
- `views/` contém apenas a interface e não deve executar lógica de negócio.
