CREATE DATABASE med_pass;

USE med_pass;


CREATE TABLE usuarios(
    id int not null PRIMARY KEY auto_increment,
    nome VARCHAR (50) not null,
    genero char(1) null,
    email varchar(50) not null,
    cpf  VARCHAR (15) not null,
    senha VARCHAR (255) not null,
    nivel enum('paciente','medico') DEFAULT 'paciente',
    telefone numeric(11) not null
);

CREATE TABLE paciente(
    id int not null PRIMARY KEY auto_increment,
    fk_usuario_id int,
    alergias VARCHAR(255),
    tipo_sanguineo VARCHAR(3),
    numero_de_carteirinha INTEGER,
    historico_familiar TEXT,
    data_nascimento DATE not null,
    rua VARCHAR (50) null,
    numero_casa VARCHAR (10) null,
    bairro VARCHAR (25) null,
    cidade VARCHAR (30) null,
    altura FLOAT null,
    peso FLOAT null,
    contato_emergencia numeric(11) null
);

CREATE TABLE medico(
    id int not null PRIMARY KEY auto_increment,
    fk_usuario_id int,
    crm VARCHAR (13) not null,
    especialidade VARCHAR (50) not null
);

CREATE TABLE arquivos(
    id_arquivos int not null PRIMARY KEY auto_increment,
    nome VARCHAR (50) not null,
    caminho VARCHAR(255) not null,
    descricao text null,
    anexo BLOB null,
    tipo VARCHAR (25) null,
    data_emissao DATE not null,
    data_validade DATE not null,
    status VARCHAR (50) null,
    fk_paciente_id int,
    fk_medico_id int
);

CREATE TABLE medicamento_em_uso (
    id_medicamento int not null PRIMARY KEY auto_increment,
    nome VARCHAR (100) not null,
    dosagem VARCHAR (20) not null,
    frequencia VARCHAR (20) not null, 
    data_inicio DATE not null,
    data_fim DATE null,
    observacao VARCHAR (50) null,
    fk_medico_id INT not null,
    fk_paciente_id INT not null
);

CREATE TABLE problema_de_saude (
    ID_problema_de_saude int not null PRIMARY KEY auto_increment,
    nome VARCHAR(100),
    status VARCHAR(50),
    tipo ENUM('grave','leve'),
    fk_paciente INTEGER NOT NULL,
    fk_medico INTEGER NOT NULL
);

ALTER TABLE problema_de_saude
ADD CONSTRAINT fk_problema_paciente
FOREIGN KEY (fk_paciente)
REFERENCES paciente(id)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE problema_de_saude
ADD CONSTRAINT fk_problema_medico
FOREIGN KEY (fk_medico)
REFERENCES medico(id)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE arquivos
ADD CONSTRAINT fk_arquivos_paciente
FOREIGN KEY (fk_paciente_id)
REFERENCES paciente(id)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE arquivos
ADD CONSTRAINT fk_arquivos_medico
FOREIGN KEY (fk_medico_id)
REFERENCES medico(id)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE medicamento_em_uso
ADD CONSTRAINT fk_medicamento_paciente
FOREIGN KEY (fk_paciente_id)
REFERENCES paciente(id)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE medicamento_em_uso
ADD CONSTRAINT fk_medicamento_medico
FOREIGN KEY (fk_medico_id)
REFERENCES medico(id)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE paciente
ADD CONSTRAINT fk_paciente_usuario
FOREIGN KEY (fk_usuario_id)
REFERENCES usuarios(id)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE medico
ADD CONSTRAINT fk_medico_usuario
FOREIGN KEY (fk_usuario_id)
REFERENCES usuarios(id)
ON DELETE CASCADE
ON UPDATE CASCADE;

