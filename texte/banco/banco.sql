-- ============================================================
-- Tabela: notas_fiscais
-- ============================================================
CREATE TABLE IF NOT EXISTS notas_fiscais (
    id              INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    usuario_id      INT UNSIGNED     NOT NULL,
    descricao       VARCHAR(500)     NOT NULL,
    data_emissao    DATE             NOT NULL,
    valor           DECIMAL(15, 2)   NOT NULL,
    caminho_arquivo VARCHAR(512)     DEFAULT NULL COMMENT 'Caminho relativo a partir da pasta base de uploads',
    criado_em       DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    INDEX idx_usuario   (usuario_id),
    INDEX idx_emissao   (data_emissao),

    CONSTRAINT fk_nf_usuario
        FOREIGN KEY (usuario_id)
        REFERENCES usuarios (id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;
