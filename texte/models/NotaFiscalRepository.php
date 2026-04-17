<?php

declare(strict_types=1);

/**
 * NotaFiscalRepository
 *
 * Gerencia os registros de notas fiscais na tabela notas_fiscais.
 * A inserção inicial cria o registro e o caminho do PDF é atualizado
 * somente após o upload ser concluído.
 */
class NotaFiscalRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Insere o registro da nota fiscal e retorna o ID gerado.
     * O caminho do arquivo é salvo em uma segunda etapa (updateFilePath)
     * porque o nome do arquivo depende do ID do registro.
     *
     * @param int    $userId      ID do usuário dono da nota
     * @param string $descricao   Descrição digitada no formulário
     * @param string $dataEmissao Data de emissão (Y-m-d)
     * @param float  $valor       Valor da nota fiscal
     *
     * @return int ID do registro recém-inserido
     */
    public function insert(
        int    $userId,
        string $descricao,
        string $dataEmissao,
        float  $valor
    ): int {
        // Insere a nota fiscal sem caminho de arquivo, apenas com dados principais.
        // O caminho do PDF só será registrado após o upload ser concluído.
        $sql = '
            INSERT INTO notas_fiscais (usuario_id, descricao, data_emissao, valor, caminho_arquivo, criado_em)
            VALUES (:usuario_id, :descricao, :data_emissao, :valor, NULL, NOW())
        ';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':usuario_id'   => $userId,
            ':descricao'    => $descricao,
            ':data_emissao' => $dataEmissao,
            ':valor'        => $valor,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Atualiza o caminho do arquivo após o upload ser concluído.
     */
    public function updateFilePath(int $notaId, string $filePath): void
    {
        // Armazena o caminho final do PDF no registro da nota fiscal.
        $stmt = $this->pdo->prepare('
            UPDATE notas_fiscais SET caminho_arquivo = :caminho WHERE id = :id
        ');

        $stmt->execute([
            ':caminho' => $filePath,
            ':id'      => $notaId,
        ]);
    }

    /**
     * Remove o registro do banco em caso de falha no upload
     * para evitar registros órfãos sem arquivo.
     */
    public function delete(int $notaId): void
    {
        // Remove a nota fiscal se o upload falhar para não deixar registro sem arquivo.
        $stmt = $this->pdo->prepare('DELETE FROM notas_fiscais WHERE id = :id');
        $stmt->execute([':id' => $notaId]);
    }
}
