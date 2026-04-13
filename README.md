# TechConf 2026 - Sistema de Inscrições

Sistema completo de inscrições para o TechConf 2026 com painel administrativo.

## 🚀 Funcionalidades

### Para Participantes
- ✅ Formulário de inscrição completo
- ✅ Validação de dados em tempo real
- ✅ Seleção de interesses e tipo de participante
- ✅ Campos específicos para empreendedores

### Para Administradores
- ✅ **Login seguro** com autenticação
- ✅ **Dashboard** com estatísticas
- ✅ **Gerenciar inscrições** (visualizar, editar, excluir)
- ✅ **Filtros e busca** avançada
- ✅ **Paginação** para grandes volumes
- ✅ **Exportação** de dados
- ✅ **Controle de permissões** (admin/editor)

## 📋 Requisitos

- **XAMPP** (ou similar com Apache + MySQL + PHP)
- **PHP 7.4+**
- **MySQL 5.7+**
- **Navegador moderno**

## 🛠️ Instalação

### 1. Configurar XAMPP
1. Instale o XAMPP
2. Inicie **Apache** e **MySQL**
3. Copie o projeto para `C:\xampp\htdocs\login-project\`

### 2. Acessar o Sistema
- **Site público:** http://localhost/login-project/
- **Painel admin:** http://localhost/login-project/admin-login.php

### 3. Primeiro Acesso Admin
- **Usuário:** admin
- **Senha:** admin123

## 📊 Estrutura do Banco

### Tabela `inscricoes`
```sql
CREATE TABLE inscricoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    cidade VARCHAR(50) NOT NULL,
    tipo_participante VARCHAR(50) NOT NULL,
    interesses JSON,
    mensagem TEXT,
    data_inscricao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Tabela `usuarios`
```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    nivel ENUM('admin', 'editor') DEFAULT 'editor',
    ativo BOOLEAN DEFAULT TRUE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 🔐 Níveis de Acesso

- **admin:** Acesso total (incluindo exclusão)
- **editor:** Visualizar e editar inscrições

## 📁 Estrutura do Projeto

```
login-project/
├── index.php              # Página principal de inscrição
├── admin-login.php        # Página de login admin
├── config.php             # Configurações do banco
├── debug.php              # Página de debug (remover em produção)
├── teste-simples.php      # Teste simples (remover em produção)
├── admin/                 # Painel administrativo
│   ├── auth.php           # Controle de autenticação
│   ├── dashboard.php      # Dashboard principal
│   ├── inscricoes.php     # Lista de inscrições
│   ├── ver-inscricao.php  # Ver detalhes
│   ├── editar-inscricao.php # Editar inscrição
│   ├── excluir-inscricao.php # Excluir inscrição
│   └── logout.php         # Logout
├── assets/
│   ├── css/
│   │   └── style.css      # Estilos CSS
│   └── js/
│       └── script.js      # JavaScript do formulário
└── README.md
```

## 🎯 Como Usar

### Para Participantes
1. Acesse http://localhost/login-project/
2. Preencha o formulário completo
3. Selecione pelo menos 1 interesse
4. Aceite os termos
5. Clique em "Enviar Inscrição"

### Para Administradores
1. Acesse http://localhost/login-project/admin-login.php
2. Use usuário: `admin` / senha: `admin123`
3. No dashboard, veja estatísticas
4. Gerencie inscrições através do menu

## 🔧 Desenvolvimento

### Adicionar Novos Usuários
Execute no phpMyAdmin:
```sql
INSERT INTO usuarios (usuario, senha, nome, email, nivel)
VALUES ('novo_usuario', '$2y$10$...', 'Nome Completo', 'email@exemplo.com', 'editor');
```

### Personalizar Campos
- Edite `index.php` para alterar campos do formulário
- Atualize `config.php` para modificar estrutura do banco
- Ajuste `admin/editar-inscricao.php` para novos campos

## 🚨 Segurança

- ✅ Senhas criptografadas com `password_hash()`
- ✅ Prepared statements contra SQL injection
- ✅ Sessões seguras
- ✅ Controle de permissões
- ✅ Validação de entrada

## 📞 Suporte

Para dúvidas ou problemas:
1. Verifique se Apache e MySQL estão rodando
2. Confirme que as tabelas foram criadas
3. Use `debug.php` para diagnosticar problemas
4. Verifique logs do XAMPP

---

**TechConf 2026** - Transformando tecnologia em conhecimento! 🚀
├── assets/
│   ├── css/
│   │   └── style.css        # Estilos customizados
│   └── js/
│       └── script.js         # Interações JavaScript
└── README.md                 # Este arquivo
```

---

## 🚀 Como Executar

### Pré-requisitos

- **PHP 7.4+** instalado
- **Servidor Web** (Apache, Nginx, etc.) ou usar servidor embutido do PHP
- **Navegador Web** moderno (Chrome, Firefox, Edge, Safari)

### Opção 1: Usar Servidor Embutido do PHP (Recomendado para Testes)

1. Clone ou baixe o projeto
2. Abra o terminal na pasta do projeto
3. Execute:

```bash
php -S localhost:8000
```

4. Abra seu navegador e acesse: `http://localhost:8000`

### Opção 2: Usar com Apache

1. Copie a pasta do projeto para a raiz web (geralmente `htdocs` no XAMPP)
2. Acesse via: `http://localhost/login-project`

### Opção 3: Usar com Nginx

Configure um virtual host apontando para a pasta do projeto e acesse conforme configurado.

---

## 📝 Campos do Formulário

| Campo | Tipo | Obrigatório | Descrição |
|-------|------|-------------|-----------|
| Nome Completo | Text | ✅ | Nome do participante |
| E-mail | Email | ✅ | E-mail de contato |
| Telefone | Tel | ✅ | Telefone com máscara |
| Cidade | Select | ✅ | Seleção de 7 cidades |
| Tipo de Participante | Select | ✅ | Estudante, Profissional, Empreendedor ou Entusiasta |
| Interesses | Checkbox (até 3) | ✅ | Áreas de interesse no evento |
| Nome da Empresa | Text | Se Empreendedor | Dinamicamente obrigatório |
| Área de Atuação | Text | Se Empreendedor | Dinamicamente obrigatório |
| Mensagem/Observações | Textarea | ❌ | Até 500 caracteres |
| Aceitar Termos | Checkbox | ✅ | Aceite dos termos e condições |

---

## 🎯 Interações JavaScript Implementadas

### 1. **Seleção de Tipo de Participante**
- Ao selecionar "Empreendedor", campos adicionais aparecem com animação
- Campos extras: Nome da Empresa e Área de Atuação
- Validação automática desses campos se empreendedor selecionado

### 2. **Seleção de Interesses Limitada**
- Máximo de 3 interesses selecionáveis
- Checkbox desativa automaticamente ao atingir limite
- Mensagem de feedback ao usuário
- Contador visual do progresso (0/3)

### 3. **Contador de Caracteres**
- Monitoramento em tempo real do campo de mensagem
- Limite máximo de 500 caracteres
- Visual feedback (cor vermelha quando perto do limite)
- Impede digitação além do limite

### 4. **Visualizar Resumo**
- Botão "Visualizar Resumo" exibe modal com dados preenchidos
- Resumo formatado e organizado por seções
- Sem envio real do formulário
- Permite editar novamente

### 5. **Validação em Tempo Real**
- Validação HTML5 nativa
- Validação customizada em JavaScript
- Feedbacks visuais (cores, mensagens)
- Suporte a requiredinterpretação de campos dinâmicos

---

## 🔄 Fluxo de Uso

1. Usuário acessa a página
2. Lê a apresentação do evento
3. Preenche o formulário de inscrição
4. Sistema exibe feedback em tempo real
5. Clica em "Visualizar Resumo"
6. Modal exibe todos os dados para confirmação
7. Pode editar ou finalizar

---

## 🎨 Customização

### Alterar Cores Primárias

Edite `assets/css/style.css`:

```css
:root {
    --primary-color: #0d6efd;      /* Azul principal */
    --secondary-color: #6c757d;    /* Cinza */
    --success-color: #198754;      /* Verde */
    --danger-color: #dc3545;       /* Vermelho */
    --info-color: #0dcaf0;         /* Ciano */
}
```

### Adicionar Mais Cidades

Edite `index.php`, no array `$cidades`:

```php
$cidades = [
    'sao-paulo' => 'São Paulo',
    'rio-janeiro' => 'Rio de Janeiro',
    // Adicione mais cidades aqui
];
```

### Modificar Mensagem de Evento

Edite a seção "Sobre o Evento" no `index.php`.

---

## 💡 Detalhes Técnicos

### PHP

- **Uso de Arrays com foreach**: Renderização de seletores (cidades, tipos de participante, interesses)
- **Segurança**: Nenhum processamento de dados backend (conforme requisito)
- **Estrutura Semântica**: HTML5 com tags semânticas apropriadas

### JavaScript

- **Sem Dependências Externas**: JavaScript puro (Vanilla JS)
- **Event Listeners**: Delegação eficiente de eventos
- **DOM Manipulation**: Controle completo do DOM
- **Validação Customizada**: Além das validações HTML5
- **LocalStorage Ready**: Estrutura pronta para persistência (se necessário)

### CSS

- **Mobile-First**: Abordagem responsiva
- **Flexbox & Grid**: Layouts modernos
- **Media Queries**: Breakpoints definidos

---

## 🧪 Testando Funcionalidades

### Teste 1: Seleção de Empreendedor
1. Selecione "Empreendedor" no dropdown "Tipo de Participante"
2. **Esperado**: Seção azul aparece com campos de empresa

### Teste 2: Limite de Interesses
1. Tente selecionar 4 interesses
2. **Esperado**: 4º checkbox não marca, mensagem de erro aparece

### Teste 3: Contador de Caracteres
1. Digite no campo de mensagem
2. **Esperado**: Contador atualiza, campo fica vermelho perto de 500

### Teste 4: Visualizar Resumo
1. Preencha campos obrigatórios
2. Clique "Visualizar Resumo"
3. **Esperado**: Modal exibe todos os dados formatados

### Teste 5: Responsividade
1. Abra em desktop (1920px)
2. Redimensione para tablet (768px)
3. Redimensione para mobile (375px)
4. **Esperado**: Layout se adapta perfeitamente

---

## 📱 Compatibilidade

| Navegador | Desktop | Mobile |
|-----------|---------|--------|
| Chrome | ✅ | ✅ |
| Firefox | ✅ | ✅ |
| Safari | ✅ | ✅ |
| Edge | ✅ | ✅ |
| Opera | ✅ | ✅ |

---

## 🚫 Limitações Propositais

- ❌ Não salva em banco de dados
- ❌ Não faz envio real de e-mail
- ❌ Não requer autenticação
- ❌ Não integra com APIs externas
- ❌ Dados não persistem após refresh (por design)

---

## 📧 Próximos Passos (Sugestões)

Se fosse um projeto em produção:

1. Implementar backend para salvar dados em banco
2. Adicionar envio de e-mail de confirmação
3. Integrar com sistema de pagamento
4. Implementar autenticação
5. Adicionar dashboard administrativo
6. Implementar confirmação por e-mail
7. Adicionar CAPTCHA para segurança

---

## 👨‍💻 Desenvolvido por

**Seu Nome / Seu GitHub**

---

## 📄 Licença

Este projeto é fornecido como teste técnico e pode ser usado livremente.

---

## 🤝 Suporte

Para dúvidas ou issues:
1. Verifique se o PHP está instalado (`php -v`)
2. Limpe o cache do navegador (Ctrl + Shift + Del)
3. Teste em outro navegador
4. Verifique o console do navegador (F12) para erros JavaScript

---

## ✨ Agradecimentos

Desenvolvido com ❤️ usando as melhores práticas web modernas.

Bootstrap 5 - https://getbootstrap.com
Bootstrap Icons - https://icons.getbootstrap.com

---

**Entrega:** Quarta-feira, 15/04/2026
**Status:** ✅ Completo e Pronto para Uso
