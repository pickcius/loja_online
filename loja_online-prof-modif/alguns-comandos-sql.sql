-- função de soma numeros

DELIMITER //
CREATE FUNCTION soma_numeros(num1 INT, num2 INT)
RETURNS INT 
BEGIN
		DECLARE resultado INT;
        SET resultado = num1 + num2;
        RETURN resultado;
        
END //
DELIMITER ; soma_numeros
    
-- chamar 

SELECT soma_numeros(10 , 20);

-------------------------------------------------------------------------------

-- função calcular preco

DELIMITER //
CREATE FUNCTION calcular_preco_final(desconto DECIMAL, preco DECIMAL)
RETURNS DECIMAL
BEGIN
	DECLARE resultado DECIMAL;
    SET resultado = desconto + preco;
    RETURN resultado;
END //
DELIMITER ;

-- chamar 

SELECT calcular_preco_final(
	(SELECT desconto_usados FROM produto where id = 1),
	(SELECT preco FROM produto where id = 1)
) as "Preço Final";

------------------------------------------------------------------------------

-- função CalculaDesconto da ativide 1 posives nessesidade de correção

DELIMITER //
CREATE FUNCTION CalculaDesconto(preco DECIMAL(10,2), desconto DECIMAL(10,2))
RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    DECLARE preco_final DECIMAL(10,2);

    SET preco_final = preco - desconto;

    RETURN preco_final;
END //
DELIMITER ;

-- chamar

SELECT 
    nome AS nome_produto,
    preco AS preco_original,
    desconto_usados AS desconto_aplicado,
    CalculaDesconto(preco, desconto_usados) AS preco_com_desconto
FROM Produto
WHERE desconto_usados > 0;

-------------------------------------------------------------------------------

-- função ConverteData 

DELIMITER //
CREATE FUNCTION ConverteData(data DATE)
RETURNS VARCHAR(10)
DETERMINISTIC
BEGIN
    DECLARE data_formatada VARCHAR(10);
    
    -- Formatar a data para 'DD/MM/YYYY'
    SET data_formatada = DATE_FORMAT(data, '%d/%m/%Y');
    
    RETURN data_formatada;
END //
DELIMITER ;

-- chamar 

SELECT 
    nome AS nome_produto,
    ConverteData(data_lancamento) AS data_lancamento_formatada
FROM Produto;

---------------------------------------------------------------------------

-- função que CalculaMediaEsto tentativa 2 

DELIMITER //
CREATE FUNCTION CalculaMediaEstoque(id_produto_param INT)
RETURNS DECIMAL(10, 2)
DETERMINISTIC
BEGIN
    DECLARE media_estoque DECIMAL(10, 2);
    
    -- Calcular a média da quantidade disponível do produto em todas as lojas
    SELECT AVG(quantidade_disponivel)
    INTO media_estoque
    FROM Estoque
    WHERE id_produto = id_produto_param;  -- Usando o parâmetro com nome diferente

    RETURN media_estoque;
END //
DELIMITER ;

-- chamar 

SELECT 
    p.nome AS nome_produto,
    CalculaMediaEstoque(p.id) AS media_quantidade_disponivel
FROM Produto p;

-------------------------------------------------------------------------

-- função ContaCaracteristias 

DELIMITER //
CREATE FUNCTION ContaCaracteristicas(id_produto_param INT)
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE numero_caracteristicas INT;
    
    -- Contar o número de características associadas ao produto
    SELECT COUNT(*)
    INTO numero_caracteristicas
    FROM Produto_Caracteristica
    WHERE id_produto = id_produto_param;
    
    RETURN numero_caracteristicas;
END //
DELIMITER ;

-- chamar 

SELECT 
    p.nome AS nome_produto,
    ContaCaracteristicas(p.id) AS numero_caracteristicas
FROM Produto p;

---------------------------------------------------------------------------