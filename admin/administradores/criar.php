
<?php

require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../includes/autenticacao_admin.php';

exigirAdmin();

if (!function_exists('escapar')) {
    function escapar($valor): string
    {
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }
}

$erro = '';
$nome = '';
$email = '';
$status = 'ativo';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';
    $status = $_POST['status'] ?? 'ativo';

    if ($nome === '' || $email === '' || $senha === '' || $confirmarSenha === '') {
        $erro = 'Preencha todos os campos obrigatórios.';

    } elseif (mb_strlen($nome) > 120) {
        $erro = 'O nome deve ter no máximo 120 caracteres.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Informe um endereço de e-mail válido.';

    } elseif (strlen($email) > 150) {
        $erro = 'O e-mail deve ter no máximo 150 caracteres.';

    } elseif (strlen($senha) < 8) {
        $erro = 'A senha deve possuir pelo menos 8 caracteres.';

    } elseif ($senha !== $confirmarSenha) {
        $erro = 'As senhas não coincidem.';

    } elseif (!in_array($status, ['ativo', 'inativo'], true)) {
        $erro = 'Selecione um status válido.';

    } else {

        try {

            $consulta = $pdo->prepare(
                "SELECT id FROM administradores WHERE email = ? LIMIT 1"
            );

            $consulta->execute([$email]);

            if ($consulta->fetch()) {

                $erro = 'Este e-mail já está cadastrado.';

            } else {

                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                $sql = $pdo->prepare(
                    "INSERT INTO administradores
                    (nome, email, senha, status, criado_em, atualizado_em)
                    VALUES (?, ?, ?, ?, NOW(), NOW())"
                );

                $sql->execute([
                    $nome,
                    $email,
                    $senhaHash,
                    $status
                ]);

                header('Location: index.php?sucesso=criado');
                exit;
            }

        } catch (PDOException $e) {

            error_log($e->getMessage());
            $erro = 'Não foi possível cadastrar o administrador.';
        }
    }
}

$nomeAdmin = $_SESSION['admin_nome'] ?? 'Administrador';
$primeiroNome = explode(' ', trim($nomeAdmin))[0];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Novo Administrador | Nox Gym</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../assets/css/admin/admin.css">

</head>

<body>

<div class="admin-layout">

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">

        <a href="../dashboard.php" class="admin-brand">
            <div class="brand-symbol">N</div>

            <div class="brand-text">
                <strong>NOX<span>GYM</span></strong>
                <small>ADMINISTRATION</small>
            </div>
        </a>

        <div class="sidebar-section-label">MENU PRINCIPAL</div>

        <nav class="sidebar-nav">

            <a href="../dashboard.php" class="nav-item">
                <span class="nav-icon">▦</span>
                <span>Dashboard</span>
            </a>

            <a href="index.php" class="nav-item active">
                <span class="nav-icon">♙</span>
                <span>Administradores</span>
            </a>

            <a href="../instrutores/index.php" class="nav-item">
                <span class="nav-icon">♟</span>
                <span>Instrutores</span>
            </a>

            <a href="../mensagens/index.php" class="nav-item">
                <span class="nav-icon">✉</span>
                <span>Mensagens</span>
            </a>

            <a href="../conteudos/index.php" class="nav-item">
                <span class="nav-icon">▤</span>
                <span>Conteúdos</span>
            </a>

        </nav>

        <div class="sidebar-section-label management-label">GESTÃO</div>

        <nav class="sidebar-nav">
            <a href="../../index.php" class="nav-item">
                <span class="nav-icon">↗</span>
                <span>Visualizar site</span>
            </a>
        </nav>

        <div class="sidebar-bottom">

            <div class="sidebar-user">

                <div class="user-avatar">
                    <?= strtoupper(substr($primeiroNome, 0, 1)) ?>
                </div>

                <div class="user-details">
                    <strong><?= escapar($nomeAdmin) ?></strong>
                    <small>Administrador</small>
                </div>

                <a href="../logout.php" class="logout-button" title="Sair">↪</a>

            </div>

            <div class="sidebar-version">
                NOX GYM SYSTEM <span>•</span> V1.0
            </div>

        </div>

    </aside>

    <!-- MAIN -->
    <main class="admin-main">

        <header class="admin-topbar">

            <div class="topbar-left">
                <span class="breadcrumb-muted">Administradores</span>
                <span class="breadcrumb-separator">/</span>
                <strong>Novo administrador</strong>
            </div>

            <div class="topbar-right">

                <div class="system-status">
                    <span class="status-dot"></span>
                    Sistema operacional
                </div>

                <div class="topbar-divider"></div>

                <div class="topbar-date">
                    <span class="date-icon">◷</span>
                    <?= date('d/m/Y') ?>
                </div>

                <div class="topbar-avatar">
                    <?= strtoupper(substr($primeiroNome, 0, 1)) ?>
                </div>

            </div>

        </header>

        <div class="dashboard-content">

            <!-- PAGE HEADER -->
            <section class="management-header">

                <div>
                    <span class="section-overline">CONTROLE DE ACESSO</span>

                    <h1>Novo administrador</h1>

                    <p>
                        Cadastre um novo membro para a equipe administrativa da Nox Gym.
                    </p>
                </div>

                <a href="index.php" class="management-back-button">
                    ← Voltar
                </a>

            </section>

            <!-- FORM -->
            <section class="admin-form-layout">

                <div class="admin-form-card">

                    <div class="admin-form-heading">

                        <div class="form-heading-icon">♙</div>

                        <div>
                            <span class="section-overline">DADOS DE ACESSO</span>
                            <h2>Informações do administrador</h2>
                            <p>Preencha os dados para criar uma nova conta.</p>
                        </div>

                    </div>

                    <?php if ($erro !== ''): ?>
                        <div class="form-error-message">
                            <span>!</span>
                            <?= escapar($erro) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="admin-create-form">

                        <div class="form-field">

                            <label for="nome">Nome completo *</label>

                            <input
                                type="text"
                                id="nome"
                                name="nome"
                                maxlength="120"
                                placeholder="Ex.: Gabrielli Rech"
                                value="<?= escapar($nome) ?>"
                                required
                            >

                        </div>

                        <div class="form-field">

                            <label for="email">E-mail *</label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                maxlength="150"
                                placeholder="administrador@noxgym.com"
                                value="<?= escapar($email) ?>"
                                required
                            >

                        </div>

                        <div class="form-fields-grid">

                            <div class="form-field">

                                <label for="senha">Senha *</label>

                                <input
                                    type="password"
                                    id="senha"
                                    name="senha"
                                    minlength="8"
                                    placeholder="Mínimo de 8 caracteres"
                                    required
                                >

                            </div>

                            <div class="form-field">

                                <label for="confirmar_senha">Confirmar senha *</label>

                                <input
                                    type="password"
                                    id="confirmar_senha"
                                    name="confirmar_senha"
                                    minlength="8"
                                    placeholder="Digite a senha novamente"
                                    required
                                >

                            </div>

                        </div>

                        <div class="form-field">

                            <label for="status">Status da conta *</label>

                            <select id="status" name="status" required>

                                <option value="ativo" <?= $status === 'ativo' ? 'selected' : '' ?>>
                                    Ativo — Acesso permitido
                                </option>

                                <option value="inativo" <?= $status === 'inativo' ? 'selected' : '' ?>>
                                    Inativo — Acesso bloqueado
                                </option>

                            </select>

                        </div>

                        <div class="form-security-note">
                            <span>◈</span>

                            <div>
                                <strong>Segurança da conta</strong>

                                <p>
                                    A senha será armazenada utilizando hash seguro.
                                    Ela não ficará visível no banco de dados.
                                </p>
                            </div>
                        </div>

                        <div class="form-actions">

                            <a href="index.php" class="form-cancel-button">
                                Cancelar
                            </a>

                            <button type="submit" class="form-submit-button">
                                <span>+</span>
                                Cadastrar administrador
                            </button>

                        </div>

                    </form>

                </div>

                <!-- SIDE INFORMATION -->
                <aside class="admin-form-info">

                    <div class="form-info-decoration">N</div>

                    <span class="section-overline">NOX GYM SYSTEM</span>

                    <h2>Uma equipe forte começa com uma boa gestão.</h2>

                    <p>
                        Os administradores possuem acesso ao painel de controle
                        e às ferramentas de gerenciamento da academia.
                    </p>

                    <div class="form-info-divider"></div>

                    <div class="form-info-item">
                        <span>01</span>
                        <div>
                            <strong>Acesso individual</strong>
                            <small>Cada administrador possui suas próprias credenciais.</small>
                        </div>
                    </div>

                    <div class="form-info-item">
                        <span>02</span>
                        <div>
                            <strong>Senhas protegidas</strong>
                            <small>As senhas são armazenadas em formato criptográfico de hash.</small>
                        </div>
                    </div>

                    <div class="form-info-item">
                        <span>03</span>
                        <div>
                            <strong>Controle de acesso</strong>
                            <small>Contas inativas não conseguem acessar o painel.</small>
                        </div>
                    </div>

                </aside>

            </section>

            <footer class="dashboard-footer">
                <span>© <?= date('Y') ?> NOX GYM</span>
                <span>DISCIPLINA. FORÇA. EVOLUÇÃO.</span>
            </footer>

        </div>

    </main>

</div>

</body>
</html>
