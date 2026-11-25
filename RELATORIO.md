# Relatório de entrega do NOX GYM

## Funcionalidades concluídas

| Área | Entrega |
| --- | --- |
| Administração | Cadastro, consulta, edição e desativação de instrutores. Cadastro, consulta, edição, desativação e bloqueio de alunos. Gestão de planos e exercícios. Cadastro, consulta, vencimento e cancelamento de assinaturas. Criação do primeiro treino, consulta dos detalhes e alteração de status. Gestão de mensagens e das cinco chaves de conteúdo. Auditoria das alterações administrativas. |
| Instrutor | Login por CPF, validação dos dígitos verificadores, dashboard, logout e verificação de status em cada acesso. Consulta dos alunos e treinos vinculados, criação e edição de treinos e gerenciamento de exercícios, incluindo remoção do vínculo por POST. |
| Aluno | Login, logout, perfil editável, foto, resumo e histórico de assinaturas e consulta aos próprios treinos publicados com exercícios ordenados. |
| Site público | Planos ativos do banco, conteúdos dinâmicos nas páginas correspondentes, formulário de contato que grava no banco, cadastro e cálculo de IMC no servidor. |
| Segurança | Consultas preparadas para parâmetros externos, validação e escape das saídas, sessões por perfil, regeneração no login, CSRF, uploads de imagens validados, transações em gravações com auditoria e consulta dos vínculos antes de exibir ou editar os treinos. |

O primeiro treino cadastrado pela administração estabelece o vínculo entre aluno e instrutor. A assinatura guarda o preço contratado. O cálculo automático considera o início como o primeiro dia e o fim como o último dia de validade.

## Pendências e limitações

- As imagens originais referenciadas para algumas modalidades, o banner, o vestiário e três produtos estavam ausentes no repositório. As páginas usam fotos existentes da academia como alternativa quando aplicável. Os cards da loja mantêm o espaço reservado para a imagem até a reposição dos arquivos originais.
- A navegação e os módulos foram validados em ambiente PHP CLI com MySQL 8. O esquema remoto via Tailscale foi consultado em modo de leitura. Os testes de escrita e autenticação usaram uma cópia temporária apontada para um MySQL isolado e contas fictícias. A instalação via Apache/XAMPP e o login com contas reais ficaram fora da validação.
- Os módulos do escopo estão integrados. A reposição das imagens originais depende dos arquivos de origem.

## Testes executados e resultados

| Teste | Resultado |
| --- | --- |
| `php -l` em todos os 84 arquivos PHP | Sem erros de sintaxe. |
| `git diff --check` | Sem erros de espaços em branco no diff. |
| Importação do esquema em MySQL 8.4 com PHP 8.4 e formulários HTTP | Aprovação nos fluxos de autenticação, logout, cadastro, edição, bloqueio, planos, assinaturas, exercícios, treinos, contatos, conteúdo e upload. |
| Validação negativa | Bloqueios de CSRF e acesso a detalhes de terceiros, rejeição de CPF inválido ou duplicado, ordem duplicada, e-mail duplicado e imagem inválida. |
| Integridade | O valor contratado permaneceu ao editar o preço do plano. Cancelamento e vencimento apareceram na área do aluno. Treinos em rascunho ficaram fora da área do aluno. Contas desativadas perderam acesso. |
| Navegação e responsividade | Links e recursos locais nas páginas públicas responderam. O Chrome em modo headless verificou nove cenários a 375 px e 1280 px sem estouro horizontal ou imagens renderizadas com erro. O menu móvel apareceu nas páginas administrativas testadas. |

## Banco e integridade

Nenhuma alteração estrutural é necessária no banco instalado. O arquivo `nox_gym.sql` contém o esquema das 11 tabelas e a inclusão das cinco chaves públicas para instalação em um banco vazio. O SQL de instalação foi importado somente no ambiente de teste. Nenhum registro existente do ambiente configurado foi excluído ou atualizado.

O arquivo `config/conexao.php` ficou byte a byte igual ao encontrado no início, confirmado pelo SHA-256. A modificação que o Git já indicava nesse arquivo pertencia ao estado inicial do workspace.

O projeto manteve a organização original, sua paleta, tipografia, componentes e layouts. As telas novas reutilizam os padrões existentes. Os ajustes visuais foram pontuais para navegação em telas pequenas, controles novos e alternativas para imagens ausentes. A implementação usa PHP Vanilla, MySQL e PDO, com formulários GET e POST. Nenhum framework ou AJAX foi usado.

## Inventário

### Pastas criadas

- `admin/alunos/`, `admin/assinaturas/`, `admin/exercicios/`, `admin/planos/`, `admin/treinos/` e `instrutor/`.

### Arquivos criados

| Pasta | Arquivos |
| --- | --- |
| Raiz | `README.md`, `RELATORIO.md`, `nox_gym.sql`, `editar_perfil.php`, `meus_treinos.php`, `minha_assinatura.php` |
| `admin/` | `formulario.php`, `inicio.php`, `listagem.php` |
| `admin/alunos/`, `admin/assinaturas/`, `admin/exercicios/` e `admin/planos/` | `index.php`, `criar.php`, `editar.php` e `formulario.php` em cada pasta |
| `admin/instrutores/` | `formulario.php` |
| `admin/treinos/` | `index.php`, `criar.php`, `visualizar.php` |
| `includes/` | `aluno_cabecalho.php`, `aluno_rodape.php`, `assinaturas_dados.php`, `conteudos_publicos.php`, `formulario_painel.php`, `formularios.php`, `imagem_publica.php`, `menu_painel.php`, `menu_painel_mobile.php`, `painel.php`, `painel_fim.php`, `sair.php`, `treino_detalhes.php`, `treinos_dados.php` |
| `instrutor/` | `aluno.php`, `alunos.php`, `editar_treino.php`, `exercicio.php`, `index.php`, `inicio.php`, `login.php`, `logout.php`, `treino.php`, `treinos.php` |

### Arquivos modificados

| Pasta | Arquivos |
| --- | --- |
| Raiz | `cadastro.php`, `calculadora.php`, `contato.php`, `estrutura.php`, `index.php`, `login.php`, `logout.php`, `loja.php`, `perfil.php`, `planos.php`, `sobre.php`, `treinos.php` |
| `admin/` | `dashboard.php`, `login.php`, `logout.php` |
| `admin/administradores/` e `admin/instrutores/` | `criar.php`, `editar.php`, `excluir.php` e `index.php` em cada pasta |
| `admin/conteudos/` | `editar.php`, `index.php` |
| `admin/mensagens/` | `atualizar.php`, `index.php`, `viasualizar.php` |
| `assets/css/` | `admin/admin.css`, `login.css`, `perfil.css`, `style.css` |
| `assets/js/` | `main.js` |
| `includes/` | `autenticacao.php`, `autenticacao_admin.php`, `footer.php`, `funcoes.php`, `header.php` |

## Execução

Em instalação nova, crie `nox_gym` e importe `nox_gym.sql`. Confira o ambiente local ou remoto já configurado e use um administrador ativo. Na raiz, execute `php -S localhost:8000` e abra `http://localhost:8000/`. O guia inclui os passos para Apache/XAMPP, criação do primeiro administrador e roteiro de demonstração. [README](README.md)
