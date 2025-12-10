# 🛍️ Sistema de Autenticação - Loja Online

## 📋 Instruções de Instalação

### Passo 1: Criar a Tabela de Usuários
1. Abra seu navegador e acesse: `http://localhost/xampp/htdocs/loja_online/sistema/criar_tabela_usuarios.php`
2. A tabela será criada automaticamente
3. Um usuário administrador padrão será criado

### Passo 2: Credenciais Padrão
- **Email:** admin@loja.com
- **Senha:** admin123
- ⚠️ **IMPORTANTE:** Altere a senha do administrador após o primeiro acesso!

### Passo 3: Acessar o Sistema
1. Abra seu navegador e acesse: `http://localhost/xampp/htdocs/loja_online/sistema/login.php`
2. Faça login com as credenciais do administrador
3. Você terá acesso ao painel completo

---

## 👥 Níveis de Acesso

### Administrador
- Acesso total ao painel de controle
- Pode gerenciar produtos, lojas, características e relatórios
- Pode cadastrar novos usuários (futura implementação)

### Cliente
- Acesso limitado (pode ser expandido para visualizar catálogo)
- Não pode acessar o painel administrativo

---

## 🔐 Segurança

- As senhas são criptografadas usando **bcrypt**
- Sessões são gerenciadas de forma segura
- Proteção contra acesso não autorizado com `verificar_autenticacao.php`

---

## 📁 Arquivos Criados

| Arquivo | Descrição |
|---------|-----------|
| `login.php` | Página de login e cadastro de usuários |
| `logout.php` | Script para fazer logout |
| `criar_tabela_usuarios.php` | Script para criar a tabela de usuários (execute uma vez) |
| `verificar_autenticacao.php` | Arquivo para verificar se o usuário está autenticado |
| `acesso_negado.php` | Página exibida quando usuário tenta acessar área restrita |

---

## 🚀 Como Adicionar Autenticação a Outras Páginas

Para proteger qualquer página, adicione no topo do arquivo PHP:

```php
<?php
require_once 'verificar_autenticacao.php';
verificar_admin(); // Apenas administradores
?>
```

---

## 🔧 Funcionalidades Implementadas

✅ Login com email e senha  
✅ Cadastro de novos usuários  
✅ Autenticação por sessão  
✅ Proteção de páginas administrativas  
✅ Sistema de roles (cliente/administrador)  
✅ Criptografia de senhas com bcrypt  
✅ Logout seguro  

---

## 📝 Próximas Melhorias

- Recuperação de senha por email
- Validação de email
- Gerenciamento de usuários no painel
- Sistema de permissões mais granular
- Histórico de atividades

