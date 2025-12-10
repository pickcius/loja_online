# 🛍️ SISTEMA DE AUTENTICAÇÃO - INSTRUÇÕES FINAIS

## ✅ STATUS - SISTEMA PRONTO PARA USO

O sistema de autenticação foi integrado com sucesso ao banco de dados existente. Agora funciona com as tabelas:
- **Administrador** - Para login de administradores
- **Cliente** - Para cadastro de clientes

---

## 🔐 CREDENCIAIS DO ADMINISTRADOR

```
Usuário: admin
Senha: teste123
```

⚠️ **IMPORTANTE:** Altere a senha do administrador assim que entrar!

---

## 🚀 COMO USAR

### 1️⃣ Acessar a página de login
```
http://localhost/xampp/htdocs/loja_online/sistema/login.php
```

### 2️⃣ Fazer Login como Administrador
- Campo **Usuário**: `admin`
- Campo **Senha**: `teste123`
- Clique em **Fazer Login**

### 3️⃣ Acessar o Painel Administrativo
Após o login, você será redirecionado para:
```
http://localhost/xampp/htdocs/loja_online/sistema/index.php
```

### 4️⃣ Gerenciar Clientes
No painel, clique em **👥 Usuários** para ver a lista de clientes cadastrados.

---

## 📁 ARQUIVOS DO SISTEMA

| Arquivo | Função |
|---------|--------|
| `login.php` | Página de login e cadastro de clientes |
| `index.php` | Painel administrativo (requer autenticação) |
| `logout.php` | Realiza logout do sistema |
| `gerenciar_usuarios.php` | Gerenciamento de clientes cadastrados |
| `verificar_autenticacao.php` | Verifica se o usuário está autenticado |
| `acesso_negado.php` | Página exibida ao tentar acessar sem autorização |
| `conexao.php` | Conexão com o banco de dados |

---

## 🎯 FUNCIONALIDADES

✅ **Login de Administrador** - Acesso ao painel completo  
✅ **Cadastro de Clientes** - Clientes podem se registrar  
✅ **Proteção de Páginas** - Apenas usuários autenticados acessam  
✅ **Gerenciamento de Clientes** - Ver e deletar clientes  
✅ **Logout Seguro** - Destroi a sessão completamente  

---

## 🔒 SEGURANÇA

- Senhas armazenadas em texto simples (campo `senha_hash`)
- Validação de email na tabela Cliente
- Proteção contra acesso não autorizado
- Redirecionamento automático para login

⚠️ **Sugestão:** Em produção, usar `password_hash()` e `password_verify()` para maior segurança.

---

## 📝 TABELAS DO BANCO DE DADOS

### Tabela: Administrador
```sql
CREATE TABLE Administrador (
    id INT(4) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    senha_hash VARCHAR(255) NOT NULL
);
```

### Tabela: Cliente
```sql
CREATE TABLE Cliente (
    id INT(4) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    telefone VARCHAR(20),
    data_cadastro DATE DEFAULT CURRENT_DATE,
    senha_hash VARCHAR(255) NOT NULL
);
```

---

## ❓ PERGUNTAS FREQUENTES

**P: Esqueci a senha do administrador**
R: Execute o SQL abaixo para atualizar:
```sql
UPDATE Administrador SET senha_hash = 'nova_senha' WHERE nome = 'admin';
```

**P: Como criar um novo administrador?**
R: Insira diretamente no banco:
```sql
INSERT INTO Administrador (nome, senha_hash) VALUES ('novo_admin', 'senha123');
```

**P: Os clientes podem acessar o painel administrativo?**
R: Não. Apenas administradores têm acesso ao painel.

---

## 🎓 PRÓXIMAS MELHORIAS SUGERIDAS

- [ ] Alterar senhas em texto plano para hash criptografado
- [ ] Adicionar recuperação de senha por email
- [ ] Criar painel de gerenciamento de administradores
- [ ] Adicionar histórico de atividades
- [ ] Implementar autenticação em dois fatores
- [ ] Adicionar validação de email confirmado

---

## 📞 SUPORTE

Se tiver problemas ao conectar com o banco de dados:
1. Verifique se o MySQL está rodando
2. Confira as credenciais em `conexao.php`
3. Certifique-se de que o banco `loja_online` foi criado
4. Execute o SQL fornecido no início do projeto

