INSERT INTO usuario (nome, login, senha, situacao) VALUES
('Administrador', 'admin', '123456', 'ATIVO');

INSERT INTO lancamento (descricao, data_lancamento, valor, tipo_lancamento, situacao) VALUES
('Salário Mensal',        '2026-04-01', 5000.00, 'RECEITA', 'ATIVO'),
('Aluguel',               '2026-04-02', 1200.00, 'DESPESA', 'ATIVO'),
('Conta de Luz',          '2026-04-03', 250.75,  'DESPESA', 'ATIVO'),
('Freelance',             '2026-04-04', 800.00,  'RECEITA', 'ATIVO'),
('Internet',              '2026-04-05', 120.00,  'DESPESA', 'ATIVO'),
('Supermercado',          '2026-04-06', 450.30,  'DESPESA', 'ATIVO'),
('Venda de Equipamento',  '2026-04-07', 1500.00, 'RECEITA', 'ATIVO'),
('Transporte',            '2026-04-08', 180.00,  'DESPESA', 'ATIVO'),
('Academia',              '2026-04-09', 90.00,   'DESPESA', 'ATIVO'),
('Bônus',                 '2026-04-10', 1000.00, 'RECEITA', 'ATIVO');
