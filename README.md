# FutureTech 2026 - Sistema de Inscrição

Este repositório abriga uma aplicação de inscrição para a FutureTech 2026. O objetivo do projeto é oferecer um fluxo de cadastro claro, acessível e seguro para participantes do evento, desde a apresentação inicial até a confirmação do registro.

## Visão Geral do Processo

1. O visitante acessa a página principal e encontra a apresentação do evento, com informações sobre datas, local e público-alvo.
2. O formulário reúne dados essenciais do participante: nome, e-mail, telefone, cidade, tipo de participante e interesses.
3. A validação do formulário ocorre em duas camadas:
   - no navegador, por meio de `assets/js/script.js`, para melhorar a experiência do usuário com mensagens diretas e controle de limites;
   - no servidor, em `index.php`, para garantir que apenas dados completos e válidos sejam enviados ao banco de dados.
4. Os dados são persistidos em um banco de dados MySQL usando `config.php` para a conexão.
5. O usuário recebe um retorno visual imediato: mensagem de sucesso quando a inscrição é registrada ou mensagem de erro quando há inconsistências.

## Componentes Principais

- `index.php`
  - Controla a renderização da página e o processamento do formulário.
  - Carrega as listas de cidades, tipos de participantes e temas de interesse.
  - Executa a validação básica do formulário e gravar os dados no banco de dados.
  - Retorna feedback ao usuário por meio de alertas visuais.

- `config.php`
  - Contém as configurações de conexão com o banco de dados MySQL.
  - Centraliza credenciais e parâmetros de acesso ao servidor de banco de dados.

- `assets/js/script.js`
  - Faz a validação interativa do formulário no lado do cliente.
  - Limita a seleção de interesses a no máximo três opções.
  - Atualiza a contagem de caracteres do campo de mensagem.
  - Exibe um modal com resumo da inscrição antes do envio final.

- `assets/css/style.css`
  - Define o estilo visual da aplicação, com foco em legibilidade, consistência e usabilidade.
  - Ajusta botões, formulários, cards e layout para uma apresentação profissional.

## Estrutura de Dados e Banco de Dados

O projeto espera um banco de dados instalado e acessível pelo servidor local. A tabela principal registra as inscrições do evento e deve ser preparada conforme necessário.

### Configuração recomendada

- `servername`: `localhost`
- `username`: `root`
- `password`: `` (senha vazia, padrão do XAMPP)
- `dbname`: `tela_login`

> Ajuste `config.php` caso o ambiente local utilize credenciais diferentes.

## Como Instalar e Executar

1. Copie todos os arquivos para a pasta do servidor local, por exemplo, `C:\xampp\htdocs\incri-project`.
2. Crie o banco de dados MySQL e a tabela de inscrições.
3. Atualize `config.php` com os dados corretos de conexão.
4. Acesse `http://localhost/incri-project` no navegador.
5. Preencha o formulário e envie a inscrição.

## Experiência do Usuário

A aplicação foi projetada para ser acessível e acolhedora. O usuário recebe orientações claras em cada etapa, como:

- campos obrigatórios marcados com asterisco;
- seleção de cidade e tipo de participante com opções predefinidas;
- limite visualizado de interesses selecionados;
- contagem de caracteres do campo de mensagem;
- confirmação de inscrição com resposta direta e amigável.

## Qualidade e Segurança

O sistema adota práticas básicas de segurança e qualidade:

- uso de prepared statements em `index.php` para evitar injeção de SQL;
- sanitização e `trim()` dos dados antes do processamento;
- feedback de erro claro para problemas de conexão ou inserção no banco de dados.

## Considerações Finais

O projeto demonstra um fluxo completo de inscrição que integra front-end e back-end de forma coesa. Ele enfatiza a clareza para o participante, a confiabilidade no processamento e a organização do código para facilitar manutenção futura.

## Minha Logo

Minha identidade visual:

<p align="center">
  <img src="assets/img/logo1.png" alt="Logo FutureTech 2026" width="200" />
</p>
