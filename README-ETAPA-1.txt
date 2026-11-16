NOX GYM — ETAPA 1 (LAYOUT MODULAR)

REQUISITOS:
- XAMPP com Apache ativo.
- PHP 8 ou superior recomendado.
- Navegador atualizado.

INSTALAÇÃO:
1. Extraia a pasta "nox-gym-etapa1" dentro de C:\xampp\htdocs\
2. Inicie o Apache no XAMPP.
3. Acesse: http://localhost/nox-gym-etapa1/

PÁGINAS:
index.php, planos.php, treinos.php, loja.php, estrutura.php,
calculadora.php, sobre.php e contato.php.

LOGIN:
Acesse http://localhost/nox-gym-etapa1/login.php
A animação de alternância entre login e cadastro foi preservada e adaptada
à identidade visual Nox Gym. A autenticação real será ligada ao MySQL na etapa 2.

OBSERVAÇÕES:
- O formulário de contato ainda não grava no banco nesta etapa.
- Planos, produtos e conteúdos são demonstrativos nesta etapa.
- Não há painel administrativo nesta etapa.
- O formulário de IMC funciona no navegador e apresenta resultado informativo.
- Imagens do projeto Titan-Fit foram reaproveitadas como material-base.
- A conexão MySQL e as operações de backend serão desenvolvidas nas próximas etapas.

ESTRUTURA MODULAR:
includes/header.php e includes/footer.php são carregados por require_once
em todas as oito páginas institucionais.
