# Jornal Grêmio

Projeto PHP simples com páginas públicas e painel administrativo para gerenciar notícias e membros do grêmio estudantil.

## Visão geral

- Frontend e backend em PHP sem framework.
- Rotas internas gerenciadas em `src/controller/RouterController.php`.
- Autenticação de admin baseada em sessão (`src/controller/AuthMiddleware.php`).
- Dados persistidos em JSON:
  - `src/data/news.json`
  - `src/data/member.json`

## Funcionalidades

- Página inicial com conteúdo do jornal
- Seções:
  - Notícias
  - Membros do grêmio
  - Esportes
  - Projetos
- Página de visualização de notícia individual
- Painel administrativo para:
  - criar, editar e excluir notícias
  - criar, editar e excluir membros

## Requisitos

- PHP 7.4+ (ou versão compatível)
- Servidor web com suporte a PHP
- Permissão de escrita nas pastas:
  - `src/data/`
  - `src/public/assets/uploads/`

## Instalação

1. Clone o repositório ou copie os arquivos para a pasta do servidor.
2. Garanta que `src/data/` exista e seja gravável.
3. Garanta que `src/public/assets/uploads/` exista e seja gravável.

## Como rodar localmente

No terminal, a partir do diretório raiz do projeto:

```bash
php -S localhost:8000 -t src/public
