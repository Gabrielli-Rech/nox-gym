<?php

require_once __DIR__ . '/../inicio.php';

if (!function_exists('escapar')) {
    function escapar($valor): string
    {
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header('Location: index.php?erro=id_invalido');
    exit;
}

$consulta = $pdo->prepare(
    "SELECT id, nome, email, status
     FROM administradores
     WHERE id = ?
     LIMIT 1"
);

$consulta->execute([$id]);
$administrador = $consulta->fetch();

if (!$administrador) {
    header('Location: index.php?erro=nao_encontrado');
    exit;
}

if (empty($_SESSION['csrf_admin'])) {
    $_SESSION['csrf_admin'] = bin2hex(random_bytes(32));
}

$erro = '';
$nome = $administrador['nome'];
$email = $administrador['email'];
$status = $administrador['status'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = $_POST['csrf_token'] ?? '';

    if (!is_string($token) || !hash_equals($_SESSION['csrf_admin'], $token)) {

        $erro = 'Sua sessão de segurança expirou. Atualize a página e tente novamente.';

    } else {

        $nome = trim(entrada($_POST, 'nome'));
        $email = trim(entrada($_POST, 'email'));
        $senha = entrada($_POST, 'senha');
        $confirmarSenha = entrada($_POST, 'confirmar_senha');
        $status = entrada($_POST, 'status');

        if ($nome === '' || $email === '') {
            $erro = 'Preencha o nome e o e-mail.';

        } elseif (mb_strlen($nome) > 120) {
            $erro = 'O nome deve ter no máximo 120 caracteres.';

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erro = 'Informe um endereço de e-mail válido.';

        } elseif (strlen($email) > 150) {
            $erro = 'O e-mail deve ter no máximo 150 caracteres.';

        } elseif (!in_array($status, ['ativo', 'inativo'], true)) {
            $erro = 'Selecione um status válido.';

        } elseif ($senha !== '' && (strlen($senha) < 8 || strlen($senha) > 72 || str_contains($senha, "\0"))) {
            $erro = 'A nova senha deve possuir pelo menos 8 caracteres.';

        } elseif ($senha !== $confirmarSenha) {
            $erro = 'As senhas não coincidem.';

        } elseif (
            (int) $_SESSION['admin_id'] === (int) $id
            && $status !== 'ativo'
        ) {
            $erro = 'Você não pode desativar sua própria conta.';

        } else {

            try {

                $duplicado = $pdo->prepare(
                    "SELECT id
                     FROM administradores
                     WHERE email = ?
                     AND id <> ?
                     LIMIT 1"
                );

                $duplicado->execute([$email, $id]);

                if ($duplicado->fetch()) {

                    $erro = 'Este e-mail já está sendo utilizado por outro administrador.';

                } else {

                    $pdo->beginTransaction();

                    // Impede que o sistema fique sem administrador ativo.
                    if ($administrador['status'] === 'ativo' && $status === 'inativo') {

                        $consultaAtivos = $pdo->query(
                            "SELECT COUNT(*)
                             FROM administradores
                             WHERE status = 'ativo'
                             FOR UPDATE"
                        );

                        $totalAtivos = (int) $consultaAtivos->fetchColumn();

                        if ($totalAtivos <= 1) {
                            $pdo->rollBack();
                            $erro = 'Não é possível desativar o último administrador ativo.';
                        }
                    }

                    if ($erro === '') {

                        if ($senha !== '') {

                            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                            $atualizar = $pdo->prepare(
                                "UPDATE administradores
                                 SET nome = ?,
                                     email = ?,
                                     senha = ?,
                                     status = ?,
                                     atualizado_em = NOW()
                                 WHERE id = ?"
                            );

                            $atualizar->execute([
                                $nome,
                                $email,
                                $senhaHash,
                                $status,
                                $id
                            ]);

                        } else {

                            $atualizar = $pdo->prepare(
                                "UPDATE administradores
                                 SET nome = ?,
                                     email = ?,
                                     status = ?,
                                     atualizado_em = NOW()
                                 WHERE id = ?"
                            );

                            $atualizar->execute([
                                $nome,
                                $email,
                                $status,
                                $id
                            ]);
                        }

                        logAdmin($pdo, 'administrador_editado', (int) $id);
                        $pdo->commit();

                        // Atualiza os dados da sessão se o administrador
                        // editado for o próprio usuário conectado.
                        if ((int) $_SESSION['admin_id'] === (int) $id) {
                            $_SESSION['admin_nome'] = $nome;
                            $_SESSION['admin_email'] = $email;
                        }

                        header('Location: index.php?sucesso=editado');
                        exit;
                    }
                }

                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }

            } catch (PDOException $e) {

                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }

                error_log('NOX GYM: falha em operação administrativa.');
                $erro = 'Não foi possível atualizar o administrador.';
            }
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

    <title>Editar Administrador | Nox Gym</title>

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

        <div class="sidebar-section-label management-label">
            GESTÃO
        </div>

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

                <form method="post" action="../logout.php">
                    <?php campoCsrf(); ?>
                    <button class="logout-button" type="submit" aria-label="Sair">↪</button>
                </form>

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
                <strong>Editar administrador</strong>
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

        <?php require __DIR__ . '/../../includes/menu_painel_mobile.php'; ?>

        <div class="dashboard-content">

            <section class="management-header">

                <div>
                    <span class="section-overline">CONTROLE DE ACESSO</span>

                    <h1>Editar administrador</h1>

                    <p>
                        Atualize os dados e as permissões da conta selecionada.
                    </p>
                </div>

                <a href="index.php" class="management-back-button">
                    ← Voltar
                </a>

            </section>

            <section class="admin-form-layout">

                <div class="admin-form-card">

                    <div class="admin-form-heading">

                        <div class="form-heading-icon">✎</div>

                        <div>
                            <span class="section-overline">EDIÇÃO DE CONTA</span>
                            <h2>Dados do administrador</h2>
                            <p>Administrador ID #<?= (int) $id ?></p>
                        </div>

                    </div>

                    <?php if ($erro !== ''): ?>
                        <div class="form-error-message">
                            <span>!</span>
                            <?= escapar($erro) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="admin-create-form">

                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?= escapar($_SESSION['csrf_admin']) ?>"
                        >

                        <div class="form-field">

                            <label for="nome">Nome completo *</label>

                            <input
                                type="text"
                                id="nome"
                                name="nome"
                                maxlength="120"
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
                                value="<?= escapar($email) ?>"
                                required
                            >

                        </div>

                        <div class="form-fields-grid">

                            <div class="form-field">

                                <label for="senha">Nova senha</label>

                                <input
                                    type="password"
                                    id="senha"
                                    name="senha"
                                    minlength="8"
                                    placeholder="Deixe em branco para manter"
                                >

                            </div>

                            <div class="form-field">

                                <label for="confirmar_senha">Confirmar nova senha</label>

                                <input
                                    type="password"
                                    id="confirmar_senha"
                                    name="confirmar_senha"
                                    minlength="8"
                                    placeholder="Repita a nova senha"
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
                                <strong>Alteração de senha opcional</strong>

                                <p>
                                    Se você não preencher os campos de senha,
                                    a senha atual será mantida. Uma nova senha
                                    será armazenada com password_hash().
                                </p>
                            </div>

                        </div>

                        <div class="form-actions">

                            <a href="index.php" class="form-cancel-button">
                                Cancelar
                            </a>

                            <button type="submit" class="form-submit-button">
                                ✓ Salvar alterações
                            </button>

                        </div>

                    </form>

                </div>

                <aside class="admin-form-info">

                    <div class="form-info-decoration">N</div>

                    <span class="section-overline">NOX GYM SYSTEM</span>

                    <h2>Controle administrativo com responsabilidade.</h2>

                    <p>
                        As alterações realizadas nesta conta serão aplicadas
                        imediatamente após a confirmação.
                    </p>

                    <div class="form-info-divider"></div>

                    <div class="form-info-item">
                        <span>01</span>
                        <div>
                            <strong>Dados atualizados</strong>
                            <small>Nome e e-mail podem ser modificados.</small>
                        </div>
                    </div>

                    <div class="form-info-item">
                        <span>02</span>
                        <div>
                            <strong>Senha protegida</strong>
                            <small>A senha anterior permanece se nenhuma nova for informada.</small>
                        </div>
                    </div>

                    <div class="form-info-item">
                        <span>03</span>
                        <div>
                            <strong>Acesso controlado</strong>
                            <small>Contas inativas não podem acessar o painel.</small>
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