<?php

class UploadService 
{
    private const ALLOWED_MIME_TYPES = ['application/pdf'];
    private const ALLOWED_EXTENSION  = 'pdf';
    private const PDF_MAGIC_BYTES    = '%PDF';
    private const MAX_FILE_SIZE      = 10 * 1024 * 1024;

    private string $baseUploadDir;

    public function __construct(string $baseUploadDir)
    {
        // Idealmente fora do document root: '/var/uploads/notas'
        $this->baseUploadDir = rtrim($baseUploadDir, DIRECTORY_SEPARATOR);
    }

    /**
     * 
     *
     * @param array  $uploadedFile  $_FILES['arquivo']
     * @param string $userFirstName Primeiro nome do usuário
     * @param int    $userId        ID do usuário
     * @param string $userFileName  Nome digitado pelo usuário no formulário
     * @param int    $notaFiscalId  ID gerado após inserção no banco
     *
     * @return string Caminho relativo salvo no banco  ex: joao42/nota-janeiro_128.pdf
     * @throws RuntimeException em qualquer falha de validação ou gravação
     */
    public function handleUpload(
        array  $uploadedFile,
        string $userFirstName,
        int    $userId,
        string $userFileName,
        int    $notaFiscalId
    ): string {
        // Verifica se o PHP relatou erro no upload.
        $this->validateUploadError($uploadedFile['error']);

        // Limita o tamanho para evitar arquivos muito grandes.
        $this->validateFileSize($uploadedFile['size']);

        // Verifica se é realmente um PDF pelo MIME type.
        $this->validateMimeType($uploadedFile['tmp_name']);

        // Verifica os primeiros bytes do arquivo para garantir que é PDF.
        $this->validateMagicBytes($uploadedFile['tmp_name']);

        // Cria/obtém a pasta do usuário (primeiroNome+id).
        $userDir  = $this->resolveUserDirectory($userFirstName, $userId);

        // Monta nome de arquivo seguro usando o ID do registro.
        $fileName = $this->buildSafeFileName($userFileName, $notaFiscalId);
        $destPath = $userDir . DIRECTORY_SEPARATOR . $fileName;

        // Move do temporário para o destino definitivo.
        $this->moveUploadedFile($uploadedFile['tmp_name'], $destPath);

        // Ajusta permissões para deixar o arquivo seguro.
        $this->setSecurePermissions($destPath);

        // Retorna o caminho relativo para armazenar no banco.
        return $this->relativePath($destPath);
    }

    // -------------------------------------------------------------------------
    // Validações
    // -------------------------------------------------------------------------

    private function validateUploadError(int $error): void
    {
        // Converte o código de erro do PHP em mensagem amigável.
        $messages = [
            UPLOAD_ERR_INI_SIZE   => 'Arquivo excede o limite definido no servidor.',
            UPLOAD_ERR_FORM_SIZE  => 'Arquivo excede o limite definido no formulário.',
            UPLOAD_ERR_PARTIAL    => 'Upload foi feito parcialmente.',
            UPLOAD_ERR_NO_FILE    => 'Nenhum arquivo enviado.',
            UPLOAD_ERR_NO_TMP_DIR => 'Pasta temporária não encontrada.',
            UPLOAD_ERR_CANT_WRITE => 'Falha ao gravar arquivo no disco.',
            UPLOAD_ERR_EXTENSION  => 'Upload bloqueado por extensão do servidor.',
        ];

        if ($error !== UPLOAD_ERR_OK) {
            throw new RuntimeException($messages[$error] ?? 'Erro desconhecido no upload.');
        }
    }

    private function validateFileSize(int $size): void
    {
        // Rejeita arquivos maiores que o limite definido.
        if ($size > self::MAX_FILE_SIZE) {
            throw new RuntimeException('Arquivo excede o tamanho máximo permitido de 10 MB.');
        }

        // Rejeita uploads vazios.
        if ($size === 0) {
            throw new RuntimeException('Arquivo enviado está vazio.');
        }
    }

    /**
     * Valida o MIME type real usando finfo — não confia na extensão nem no
     * tipo informado pelo browser, que podem ser facilmente falsificados.
     */
    private function validateMimeType(string $tmpPath): void
    {
        // Garante que o arquivo veio de um upload legítimo do PHP.
        if (!is_uploaded_file($tmpPath)) {
            throw new RuntimeException('Arquivo inválido: não é um upload legítimo.');
        }

        // Usa finfo para validar o tipo real do conteúdo do arquivo.
        $finfo    = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($tmpPath);

        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
            throw new RuntimeException(
                "Tipo de arquivo não permitido ({$mimeType}). Apenas PDF é aceito."
            );
        }
    }

    /**
     * Verifica os magic bytes do arquivo (%PDF no início).
     * Dupla camada de proteção contra PDFs falsificados.
     */
    private function validateMagicBytes(string $tmpPath): void
    {
        // Verifica os primeiros bytes do arquivo: PDF sempre começa com %PDF.
        $handle = fopen($tmpPath, 'rb');
        if ($handle === false) {
            throw new RuntimeException('Não foi possível ler o arquivo para validação.');
        }

        $header = fread($handle, 4);
        fclose($handle);

        if ($header !== self::PDF_MAGIC_BYTES) {
            throw new RuntimeException('Arquivo não é um PDF válido (magic bytes inválidos).');
        }
    }

    // -------------------------------------------------------------------------
    // Pasta do usuário
    // -------------------------------------------------------------------------

    /**
     * Garante que a pasta do usuário existe e retorna o caminho absoluto.
     * Padrão: primeiroNome + idUsuario  →  joao42
     */
    /**
     * Garante que a pasta do usuário exista.
     * Se ainda não existir, cria com permissões seguras e .htaccess.
     */
    private function resolveUserDirectory(string $userFirstName, int $userId): string
    {
        // Cria uma pasta única do usuário usando primeiro nome + id.
        $folderName = $this->sanitizeFolderName($userFirstName) . $userId;
        $dirPath    = $this->baseUploadDir . DIRECTORY_SEPARATOR . $folderName;

        if (!is_dir($dirPath)) {
            if (!mkdir($dirPath, 0750, true)) {
                throw new RuntimeException("Não foi possível criar a pasta do usuário: {$folderName}");
            }

            // Cria .htaccess na pasta para aumentar a segurança.
            $this->writeHtaccess($dirPath);
        }

        return $dirPath;
    }

    /**
     * Sanitiza o nome da pasta: mantém apenas letras (sem acentos) e números.
     */
    private function sanitizeFolderName(string $name): string
    {
        // Remove acentos e caracteres especiais antes de montar o nome da pasta.
        $name = $this->normalizeText($name);
        return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name));
    }

    private function normalizeText(string $text): string
    {
        if (function_exists('transliterator_transliterate')) {
            $converted = transliterator_transliterate('Any-Latin; Latin-ASCII', $text);
            if ($converted !== null) {
                return $converted;
            }
        }

        $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        if ($converted !== false) {
            return $converted;
        }

        $map = [
            'Á'=>'A','À'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','á'=>'a','à'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a',
            'É'=>'E','È'=>'E','Ê'=>'E','Ë'=>'E','é'=>'e','è'=>'e','ê'=>'e','ë'=>'e',
            'Í'=>'I','Ì'=>'I','Î'=>'I','Ï'=>'I','í'=>'i','ì'=>'i','î'=>'i','ï'=>'i',
            'Ó'=>'O','Ò'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','ó'=>'o','ò'=>'o','ô'=>'o','õ'=>'o','ö'=>'o',
            'Ú'=>'U','Ù'=>'U','Û'=>'U','Ü'=>'U','ú'=>'u','ù'=>'u','û'=>'u','ü'=>'u',
            'Ç'=>'C','ç'=>'c','Ñ'=>'N','ñ'=>'n'
        ];

        return strtr($text, $map);
    }

    // -------------------------------------------------------------------------
    // Nome do arquivo
    // -------------------------------------------------------------------------

    /**
     * Gera um nome de arquivo seguro: <nome-sanitizado>_<id>.pdf
     * Ex: nota-janeiro_128.pdf
     */
    private function buildSafeFileName(string $userFileName, int $fileId): string
    {
        // Constrói nome de arquivo seguro usando base sanitizada e ID.
        $sanitized = $this->sanitizeFileName($userFileName);

        if (empty($sanitized)) {
            $sanitized = 'nota-fiscal';
        }

        return "{$sanitized}_{$fileId}." . self::ALLOWED_EXTENSION;
    }

    /**
     * Sanitiza o nome de arquivo fornecido pelo usuário:
     * - Remove path traversal (../, ..\, /, \)
     * - Remove tags HTML/scripts
     * - Mantém apenas letras, números, hífen e underscore
     * - Limita o tamanho
     */
    private function sanitizeFileName(string $name): string
    {
        // Remove a extensão caso o usuário tenha incluído.
        $name = pathinfo($name, PATHINFO_FILENAME);

        // Remove tentativas de path traversal e caracteres nulos.
        $name = str_replace(['../', '.\\', '/', '\\', "\0"], '', $name);

        // Remove tags HTML e scripts.
        $name = strip_tags($name);

        // Converte acentos para ASCII para evitar nomes estranhos.
        $name = $this->normalizeText($name);

        // Substitui caracteres não seguros por hífen.
        $name = preg_replace('/[^a-zA-Z0-9\-_]/', '-', $name);

        // Remove hífens/underscores repetidos ou no começo/fim.
        $name = preg_replace('/[-_]{2,}/', '-', $name);
        $name = trim($name, '-_');

        // Limita o tamanho do nome para evitar problemas no sistema de arquivos.
        return substr($name, 0, 100);
    }

    // -------------------------------------------------------------------------
    // Gravação e permissões
    // -------------------------------------------------------------------------

    private function moveUploadedFile(string $tmpPath, string $destination): void
    {
        // Move do diretório temporário do PHP para a pasta definitiva.
        if (!move_uploaded_file($tmpPath, $destination)) {
            throw new RuntimeException('Falha ao mover o arquivo para o destino final.');
        }
    }

    /**
     * Define permissões restritivas: apenas leitura/escrita pelo dono do processo
     * PHP (0640). O arquivo não pode ser executado por nenhum usuário.
     */
    private function setSecurePermissions(string $filePath): void
    {
        // Ajusta permissões para que apenas o dono do processo PHP possa ler/escrever.
        chmod($filePath, 0640);
    }

    /**
     * Cria um .htaccess na pasta do usuário que:
     * - Bloqueia acesso direto via HTTP a qualquer arquivo
     * - Impede execução de scripts PHP dentro da pasta
     */
    private function writeHtaccess(string $dirPath): void
    {
        $htaccess = <<<'HTACCESS'
# Bloqueia acesso direto via URL a todos os arquivos desta pasta
Require all denied

# Impede execução de PHP nesta pasta (Apache + mod_php)
<FilesMatch "\.php$">
    Require all denied
</FilesMatch>

# Remove o handler PHP para qualquer arquivo (FastCGI / FPM)
RemoveHandler .php .phtml .php3 .php4 .php5 .php7 .php8
RemoveType application/x-httpd-php .php

Options -Indexes -ExecCGI
HTACCESS;

        file_put_contents($dirPath . DIRECTORY_SEPARATOR . '.htaccess', $htaccess);
        chmod($dirPath . DIRECTORY_SEPARATOR . '.htaccess', 0640);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function relativePath(string $absolutePath): string
    {
        // Converte caminho absoluto em caminho relativo para salvar no banco.
        return ltrim(
            str_replace($this->baseUploadDir, '', $absolutePath),
            DIRECTORY_SEPARATOR
        );
    }
}
