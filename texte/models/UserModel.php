<?php

declare(strict_types=1);

/**
 * UserModel
 *
 * Gerencia operações relacionadas a usuários, pacientes e médicos.
 */
class UserModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Busca paciente pelo CPF.
     *
     * @param string $cpf CPF do paciente (apenas números).
     * @return array|null Dados do paciente ou null se não encontrado.
     */
    public function getPacienteByCpf(string $cpf): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT p.id AS paciente_id, u.nome AS paciente_nome
             FROM usuarios u
             INNER JOIN paciente p ON p.fk_usuario_id = u.id
             WHERE u.cpf = ?'
        );
        $stmt->execute([$cpf]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Autentica usuário pelo CPF e senha.
     *
     * @param string $cpf CPF do usuário.
     * @param string $senha Senha em texto plano.
     * @return array|null Dados do usuário se autenticado, null caso contrário.
     */
    public function authenticateUser(string $cpf, string $senha): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, nome, senha, nivel FROM usuarios WHERE cpf = ? LIMIT 1');
        $stmt->execute([$cpf]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario || !password_verify($senha, $usuario['senha'])) {
            return null;
        }

        return $usuario;
    }

    /**
     * Busca médico pelo ID do usuário.
     *
     * @param int $userId ID do usuário logado.
     * @return array|null Dados do médico ou null se não encontrado.
     */
    public function getMedicoByUserId(int $userId): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id FROM medico WHERE fk_usuario_id = ? LIMIT 1');
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}