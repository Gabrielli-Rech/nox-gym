# NOX GYM

Sistema web de gerenciamento de academia. O projeto acadêmico de Análise e Desenvolvimento de Sistemas aplica autenticação por perfil, formulários tradicionais, consultas SQL e relacionamentos entre alunos, planos, assinaturas, instrutores e treinos.

## Tecnologias

- PHP 8.1 ou superior, com PDO MySQL, sessões e Mbstring.
- MySQL 8, com tabelas InnoDB e codificação UTF-8.
- HTML5, CSS3 e JavaScript Vanilla para interações visuais.

## Como executar

1. Use o Apache do XAMPP com o MySQL local configurado ou o PHP CLI para desenvolvimento.
2. Em uma instalação nova, crie o banco `nox_gym` com codificação `utf8mb4`. Selecione o banco e importe [nox_gym.sql](nox_gym.sql) pelo phpMyAdmin ou cliente MySQL. O arquivo contém a estrutura e as chaves de conteúdo. Para um banco já instalado, use a estrutura e os registros existentes.
3. Confira qual ambiente está selecionado na variável `$ambiente` em `config/conexao.php`. Use a opção `local` para o MySQL usado com o XAMPP. Use `remoto` para o servidor MySQL existente, com o dispositivo já conectado ao Tailscale e autorizado a alcançar o servidor. Solicite ao responsável pelo banco uma conta de acesso se necessário.
4. Para usar o Apache, copie ou vincule a pasta do projeto ao diretório `htdocs` e abra `http://localhost/NOME-DA-PASTA/`. Para usar o servidor de desenvolvimento, abra o terminal na raiz e execute `php -S localhost:8000`. Em seguida, abra `http://localhost:8000/`.
5. Para enviar imagens pela interface, o usuário do PHP precisa de permissão de escrita em `assets/img/`. Também é possível informar o caminho de uma imagem existente nessa pasta. A interface aceita JPG, PNG, GIF e WebP de até 2 MB.

### Primeiro administrador em um banco vazio

O painel usa um administrador ativo em `administradores`. Para um banco novo e vazio, execute o comando abaixo na raiz, em Bash, e preencha os dados solicitados. A senha é lida sem eco e seu hash é gerado no PHP. Em um banco existente, use uma conta administrativa já cadastrada.

```bash
read -r -p 'Nome do administrador: ' NOX_NOME
read -r -p 'E-mail do administrador: ' NOX_EMAIL
read -rs -p 'Senha (8 a 72 bytes): ' NOX_SENHA
printf '\n'
printf '%s' "$NOX_SENHA" | php -r '
    $senha = stream_get_contents(STDIN);
    if (trim($argv[1]) === "" || mb_strlen($argv[1]) > 120
        || !filter_var($argv[2], FILTER_VALIDATE_EMAIL) || strlen($argv[2]) > 150
        || strlen($senha) < 8 || strlen($senha) > 72) {
        exit("Confira o nome, o e-mail e a senha.\n");
    }
    require "config/conexao.php";
    if ($pdo->query("SELECT 1 FROM administradores LIMIT 1")->fetchColumn()) {
        exit("O banco já possui administrador. Use uma conta existente.\n");
    }
    $stmt = $pdo->prepare("INSERT INTO administradores (nome, email, senha) VALUES (?, ?, ?)");
    $stmt->execute([$argv[1], $argv[2], password_hash($senha, PASSWORD_DEFAULT)]);
    echo "Administrador cadastrado.\n";
' "$NOX_NOME" "$NOX_EMAIL"
unset NOX_NOME NOX_EMAIL NOX_SENHA
```

## Áreas e funcionalidades

- **Site público.** Apresentação da academia, catálogo de planos ativos, modalidades, loja demonstrativa, estrutura, calculadora de IMC, cadastro e formulário de contato.
- **Administração** (`admin/login.php`). Gerencia administradores, instrutores, alunos, planos, assinaturas, exercícios, treinos, mensagens e conteúdos. O contato registra a mensagem com status `nova`. A equipe pode marcá-la como lida ou respondida.
- **Instrutor** (`instrutor/login.php`). Entrando com CPF e senha, consulta seus alunos e treinos, cria treinos personalizados e organiza exercícios por ordem, séries, repetições, carga, descanso e observações.
- **Aluno** (`login.php`). Consulta o próprio perfil, a assinatura e os treinos publicados. Pode atualizar seus dados pessoais e a foto.

### Roteiro de apresentação

1. No painel, cadastre um aluno, um instrutor ativo, um plano e um exercício.
2. Cadastre uma assinatura que vincule o aluno ao plano. Se deixar o valor e a data final em branco, o sistema usa o preço e a duração do plano no momento do cadastro. A data de início conta como o primeiro dia. A assinatura fica vencida a partir do dia seguinte ao término.
3. Em **Treinos → Novo treino e vínculo**, crie um treino em rascunho para o aluno e o instrutor escolhidos. Esse primeiro treino estabelece o vínculo. A partir dele, o instrutor pode consultar o aluno e criar outros treinos para ele.
4. Entre como instrutor, adicione um exercício ao treino e mude o status para **Ativo**.
5. Entre como aluno e consulte a assinatura e o treino publicado.
6. Envie uma mensagem pelo site e consulte-a no painel. Para demonstrar conteúdo dinâmico, edite uma chave existente e recarregue a página pública correspondente.

## Estrutura de diretórios

| Caminho | Conteúdo |
| --- | --- |
| Raiz | Páginas públicas, login e área do aluno |
| `admin/` | Painel administrativo e módulos de gestão |
| `instrutor/` | Login, painel, alunos e treinos do instrutor |
| `includes/` | Sessões, formulários, consultas e componentes compartilhados |
| `assets/` | CSS, JavaScript e imagens |
| `config/` | Conexão PDO original |
| `nox_gym.sql` | Esquema para uma instalação nova |

As ações de gestão usam formulários POST, token CSRF e validação no servidor. As áreas autenticadas conferem a conta e os vínculos no banco. As gravações administrativas que também geram auditoria são agrupadas em transações.
