# Jornal do Grêmio Estudantil

Este projeto é uma aplicação web em PHP desenvolvida para representar o jornal digital do grêmio estudantil, com páginas públicas para divulgação de conteúdo e um painel administrativo para gestão de notícias e membros.

## Visão Geral

A aplicação foi construída com PHP puro, sem uso de framework, oferecendo uma estrutura simples, objetiva e fácil de manter. O sistema conta com:

- páginas institucionais e informativas;
- gerenciamento interno de notícias;
- cadastro e atualização de membros do grêmio;
- fluxo de contato por e-mail para comunicação com os visitantes.

## Funcionalidades

- Página inicial com conteúdo editorial e institucional;
- Seções para notícias, membros, esportes e projetos;
- Página individual de visualização de notícias;
- Formulário de contato para envio de mensagens;
- Painel administrativo para criar, editar e excluir:
  - notícias;
  - membros do grêmio.

## Arquitetura

O projeto organiza seu código em diretórios específicos para:

- controle de rotas e fluxo de navegação;
- autenticação e sessão administrativa;
- manipulação de dados e persistência;
- views e assets públicos.

## Requisitos

- PHP 7.4 ou superior;
- servidor web compatível com PHP;
- permissões de escrita nas pastas de dados e uploads.

## Instalação

1. Clone o repositório ou copie os arquivos para o diretório do servidor web.
2. Garanta que as pastas de armazenamento existam e tenham permissão de escrita.
3. Configure as variáveis de ambiente para o envio de e-mails, se estiver utilizando o formulário de contato.

## Execução Local

No terminal, a partir da raiz do projeto, execute:

```bash
php -S localhost:8000 -t src/public
```

Em seguida, acesse:

```text
http://localhost:8000/
```

## Objetivo

O projeto tem como finalidade centralizar as principais informações do grêmio estudantil em um ambiente digital organizado, moderno e de fácil manutenção, promovendo maior visibilidade das ações e iniciativas da instituição.
