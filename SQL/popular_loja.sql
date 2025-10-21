INSERT INTO Loja (Nome, Endereco, Telefone) VALUES
('Tech Store', 'Rua A, 123', '(47) 99999-0001'),
('Digital Center', 'Av. B, 456', '(47) 99999-0002'),
('Eletron Mais', 'Rua C, 789', '(47) 99999-0003'),
('InfoPoint', 'Av. D, 321', '(47) 99999-0004'),
('EletroFast', 'Rua E, 654', '(47) 99999-0005');

INSERT INTO Produtos (Nome, Preco, Descricao, Categoria, Data_de_Lancamento, Desconto) VALUES
('Notebook Acer', 3500.00, 'Novo', 'Informatica', '2025-01-10', 0.00),
('Smartphone Samsung', 2500.00, 'Promocao', 'Telefonia', '2025-02-05', 0.00),
('Geladeira Brastemp', 3800.00, 'Liquidacao', 'Eletrodomesticos', '2024-11-20', 0.00),
('Mouse Logitech', 150.00, 'Novo', 'Acessorios', '2025-05-15', 0.00),
('Fone Bluetooth', 200.00, 'Usado', 'Acessorios', '2024-09-10', 20.00),
('Impressora HP', 750.00, 'Novo', 'Informatica', '2025-04-01', 0.00),
('TV LG 50"', 2900.00, 'Novo', 'Eletronico', '2024-10-01', 0.00),
('Tablet Xiaomi', 1200.00, 'Promocao', 'Telefonia', '2025-03-12', 0.00),
('Aspirador Robô', 1800.00, 'Novo', 'Eletrodomesticos', '2025-06-08', 0.00),
('Carregador Turbo', 80.00, 'Usado', 'Acessorios', '2024-08-01', 10.00);

INSERT INTO Caracteristica (Nome, Descricao) VALUES
('Cor', 'Preto'),
('Tamanho', 'Médio'),
('Peso', 'Leve'),
('Material', 'Plástico ABS'),
('Bateria', '4000mAh'),
('Conectividade', 'Wi-Fi'),
('Bluetooth', 'Versão 5.0'),
('Garantia', '12 meses'),
('Voltagem', 'Bivolt'),
('Processador', 'Intel i5'),
('Memória RAM', '8GB'),
('Armazenamento', '256GB SSD'),
('Resolução', 'Full HD'),
('Câmera', '12MP'),
('Sistema Operacional', 'Android 13');

INSERT INTO Produtos_Caracteristica (Produtos, Caracteristica) VALUES
(1, 10), (1, 11), (1, 12),
(2, 14), (2, 15),
(3, 9), (3, 8),
(4, 1),
(5, 5), (5, 7),
(6, 10), (6, 11),
(7, 13), (7, 9),
(8, 15),
(9, 2), (9, 3),
(10, 5), (10, 1);

INSERT INTO Estoque (Loja, Produtos, Quantidade) VALUES
(1, 1, 10), (1, 2, 8),
(2, 3, 5), (2, 4, 7),
(3, 5, 3), (3, 6, 6),
(4, 7, 4), (4, 8, 9),
(5, 9, 2), (5, 10, 11),
(1, 5, 1), (2, 6, 2), (3, 1, 3), (4, 2, 5), (5, 3, 4);

