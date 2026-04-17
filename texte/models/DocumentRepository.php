<?php

declare(strict_types=1);

/**
 * DocumentRepository
 *
 * Gerencia os registros de documentos médicos na tabela arquivos.
 * O fluxo de upload separa a inserção inicial do caminho do arquivo,
 * pois o nome final depende do ID gerado pelo banco.
 */
class DocumentRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Insere o registro do documento médico com caminho temporário vazio.
     * O caminho será atualizado após o arquivo ser enviado com sucesso.
     */
    public function insert(
        int $medicoId,
        int $pacienteId,
        string $nome,
        string $descricao,
        string $tipo,
        string $dataEmissao,
        ?string $dataValidade,
        string $status
    ): int {
        // Insere o registro do documento médico sem caminho do arquivo.
        // O caminho será atualizado após o upload ser concluído.
        $sql = 'INSERT INTO arquivos (nome, caminho, descricao, tipo, data_emissao, data_validade, status, fk_paciente_id, fk_medico_id)
                VALUES (:nome, :caminho, :descricao, :tipo, :data_emissao, :data_validade, :status, :fk_paciente_id, :fk_medico_id)';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nome'           => $nome,
            ':caminho'        => '',
            ':descricao'      => $descricao,
            ':tipo'           => $tipo,
            ':data_emissao'   => $dataEmissao,
            ':data_validade'  => $dataValidade,
            ':status'         => $status,
            ':fk_paciente_id' => $pacienteId,
            ':fk_medico_id'   => $medicoId,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Atualiza o caminho do PDF após o upload ser concluído.
     */
    public function updateFilePath(int $documentId, string $filePath): void
    {
        // Atualiza o campo de caminho quando o arquivo já foi gravado.
        $stmt = $this->pdo->prepare('UPDATE arquivos SET caminho = :caminho WHERE id_arquivos = :id');
        $stmt->execute([
            ':caminho' => $filePath,
            ':id'      => $documentId,
        ]);
    }

    /**
     * Remove o registro do banco em caso de falha no upload.
     */
    public function delete(int $documentId): void
    {
        // Remove o registro se o upload falhar, evitando registro órfão no banco.
        $stmt = $this->pdo->prepare('DELETE FROM arquivos WHERE id_arquivos = :id');
        $stmt->execute([':id' => $documentId]);
    }
}
