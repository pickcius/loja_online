# 🛍️ Projeto 3: Loja Online

**Disciplina:** Banco de Dados  
**Curso:** Técnico em Desenvolvimento de Sistemas  
**Aluno(a):** [Seu Nome]  
**Turma:** [Sua Turma]  
**Professor:** [Nome do Professor]
**Data de Entrega:** [dd/mm/aaaa]  

---

## 🎯 Objetivo
Desenvolver uma aplicação simples de loja online que integra:
- Um banco de dados relacional (**MySQL**) para lojas, produtos e características.
- Um banco NoSQL (**MongoDB Atlas**) para armazenar **características flexíveis dos produtos** (como cor, tamanho, voltagem, material, etc.) e avaliações.
- Uma interface web em **PHP** que exibe produtos e seus estoques dinâmicos.

Este projeto demonstra como usar diferentes tipos de bancos de dados conforme a natureza dos dados.

---

## 🛠️ Tecnologias Utilizadas
- 💾 **MySQL** – Para dados estruturados e transacionais
- 📦 **MongoDB Atlas** – Para dados flexíveis (avaliações, comentários)
- 💬 **PHP** – Integração e exibição na web
- 🔗 **GitHub** – Versionamento de código
- 🖥️ **XAMPP** – Ambiente local para execução

---

## 🗄️ Banco de Dados Relacional (MySQL)

### Banco: `loja_online`

### Estrutura das Tabelas
```sql
-- Tabela: produto
id (INT, PK), nome (VARCHAR), descricao (VARCHAR), preco (DECIMAL), tipo (ENUM: Novo, Usado, Liquidacao, Promocao, Outros), categoria (SET: Eletronico, Telefonia, Informatica, Eletrodomesticos, Acessorios, Outros), data_de_lancamento (DATE), desconto (DECIMAL)

-- Tabela: caracteristica
id (INT, PK), nome (VARCHAR), descricao (VARCHAR)

-- Tabela: produto_caracteristica
id (INT, PK), id_prodtudo (INT), id_caracteristica (INT)

-- Tabela: loja
id (INT, PK), nome (VARCHAR), telefone (VARCHAR), rua (VARCHAR), numero (INT), bairro (VARCHAR), cep (VARCHAR), complemento (VARCHAR), cidade(VARCHAR)

-- Tabela: estoque
id (INT, PK), id_produto (INT), id_loja (INT), quantidade_disponivel (INT)

---

## 💻 Aplicação PHP
### Funcionalidades
- Recebe o id do aluno pela URL (ex: painel_aluno.php?id=1)
- Busca os dados principais no MySQL
- Busca o histórico no MongoDB
- Exibe tudo integrado em uma página web

### Como Executar
- Inicie o Apache e MySQL no XAMPP.
- Coloque os arquivos de loja_online na pasta htdocs.
- Acesse no navegador: http://localhost/loja_online

# Estrutura da Pasta Sistema

## Organização

A pasta `sistema/` foi reorganizada em subpastas para melhor manutenção e clareza:

### Pastas

- **auth/** - Autenticação e gerenciamento de usuários
  - login.php - Página de login
  - logout.php - Logout de usuários
  - verificar_autenticacao.php - Verificação de sessões
  - acesso_negado.php - Página de acesso negado
  - gerenciar_usuarios.php - Gerenciamento de clientes

- **admin/** - Painel administrativo
  - index.php - Dashboard principal (painel de controle)
  - criar_tabela_usuarios.php - Script de inicialização

- **produtos/** - Gerenciamento de produtos
  - produtos.php - Listagem de produtos
  - adicionar.php - Adicionar novo produto
  - editar.php - Editar produto
  - excluir.php - Excluir produto
  - produtosLoja.php - Produtos por loja

- **lojas/** - Gerenciamento de lojas
  - lojas.php - Listagem de lojas
  - add_loja.php - Adicionar loja
  - editar_loja.php - Editar loja
  - editar_loja_acao.php - Processa edição
  - excluir_loja.php - Excluir loja

- **caracteristicas/** - Tipos, categorias e atributos
  - caracteristicas.php - Listagem
  - adicionar_caracteristica.php - Adicionar
  - editar_caracteristica.php - Editar
  - excluir_caracteristica.php - Excluir
  - atualizar_caracteristica.php - Processa atualização

- **relatorios/** - Relatórios do sistema
  - relProdutosLoja.php - Relatório de produtos por loja
  - relProdutosLoja2.php - Versão alternativa
  - relatorio.php - Relatório de vendas
  - relatorio_analitico.php - Análise detalhada
  - relAnalitico.php - Versão modificada

- **carrinho/** - Carrinho de compras (cliente)
  - carrinho.php - Gerenciamento do carrinho

- **hub/** - Página pública
  - hub.php - Página principal do cliente

- **config/** - Configurações
  - conexao.php - Conexão com banco de dados
  - conterel.php - Configurações de relatório
  - atualizar.php - Processamento de atualizações
  - .htaccess - Configurações do servidor

## Acesso

- **Sistema Admin**: http://localhost/loja_online/sistema/admin/index.php
- **Login**: http://localhost/loja_online/sistema/auth/login.php
- **Hub Cliente**: http://localhost/loja_online/sistema/hub/hub.php
- **Página Inicial**: http://localhost/loja_online/
