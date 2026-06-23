CREATE TABLE IF NOT EXISTS usuario (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    login VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(100) NOT NULL,
    situacao VARCHAR(20) NOT NULL
);

CREATE TABLE IF NOT EXISTS lancamento (
    id SERIAL PRIMARY KEY,
    descricao VARCHAR(150) NOT NULL,
    data_lancamento DATE NOT NULL,
    valor NUMERIC(10,2) NOT NULL,
    tipo_lancamento VARCHAR(20) NOT NULL,
    situacao VARCHAR(20) NOT NULL
);
