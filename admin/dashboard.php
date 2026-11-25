<?php

require_once __DIR__ . '/inicio.php';
$prefixo = './';

if (!function_exists('escapar')) {
    function escapar($valor): string {
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }
}

function contarRegistros(PDO $pdo, string $tabela): int {
    $tabelasPermitidas = [
        'usuarios',
        'instrutores',
        'administradores',
        'mensagens_contato',
        'conteudos'
    ];

    if (!in_array($tabela, $tabelasPermitidas, true)) {
        return 0;
    }

    try {
        return (int) $pdo->query("SELECT COUNT(*) FROM `$tabela`")->fetchColumn();
    } catch (PDOException $e) {
        error_log('NOX GYM: falha em operação administrativa.');
        return 0;
    }
}

$totalAlunos = contarRegistros($pdo, 'usuarios');
$totalInstrutores = contarRegistros($pdo, 'instrutores');
$totalAdministradores = contarRegistros($pdo, 'administradores');
$totalMensagens = contarRegistros($pdo, 'mensagens_contato');
$totalConteudos = contarRegistros($pdo, 'conteudos');

$nomeAdmin = $_SESSION['admin_nome'] ?? 'Administrador';
$emailAdmin = $_SESSION['admin_email'] ?? '';

$primeiroNome = explode(' ', trim($nomeAdmin))[0];

$dataAtual = new DateTime('now');
$dataFormatada = $dataAtual->format('d/m/Y');

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Nox Gym Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/admin/admin.css">
</head>

<body>

<div class="admin-layout">

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">

        <a href="dashboard.php" class="admin-brand">
            <div class="brand-symbol">N</div>

            <div class="brand-text">
                <strong>NOX<span>GYM</span></strong>
                <small>ADMINISTRATION</small>
            </div>
        </a>

        <div class="sidebar-section-label">MENU PRINCIPAL</div>

        <nav class="sidebar-nav">

            <a href="dashboard.php" class="nav-item active">
                <span class="nav-icon">▦</span>
                <span>Dashboard</span>
            </a>

            <a href="administradores/index.php" class="nav-item">
                <span class="nav-icon">♙</span>
                <span>Administradores</span>
            </a>

            <a href="instrutores/index.php" class="nav-item">
                <span class="nav-icon">♟</span>
                <span>Instrutores</span>
            </a>

            <a href="mensagens/index.php" class="nav-item">
                <span class="nav-icon">✉</span>
                <span>Mensagens</span>
                <?php if ($totalMensagens > 0): ?>
                    <span class="nav-count"><?= $totalMensagens ?></span>
                <?php endif; ?>
            </a>

            <a href="conteudos/index.php" class="nav-item">
                <span class="nav-icon">▤</span>
                <span>Conteúdos</span>
            </a>

        </nav>

        <div class="sidebar-section-label management-label">
            GESTÃO
        </div>

        <nav class="sidebar-nav">
            <a href="alunos/index.php" class="nav-item"><span class="nav-icon">▤</span><span>Alunos</span></a>
            <a href="planos/index.php" class="nav-item"><span class="nav-icon">▤</span><span>Planos</span></a>
            <a href="assinaturas/index.php" class="nav-item"><span class="nav-icon">▤</span><span>Assinaturas</span></a>
            <a href="exercicios/index.php" class="nav-item"><span class="nav-icon">▤</span><span>Exercícios</span></a>
            <a href="treinos/index.php" class="nav-item"><span class="nav-icon">▤</span><span>Treinos</span></a>
            <a href="../index.php" class="nav-item">
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

                <form method="post" action="logout.php"><?php campoCsrf(); ?><button class="logout-button" type="submit" aria-label="Sair">
                    ↪
                </button></form>
            </div>

            <div class="sidebar-version">
                NOX GYM SYSTEM <span>•</span> V1.0
            </div>

        </div>

    </aside>

    <!-- MAIN -->
    <main class="admin-main">

        <!-- TOPBAR -->
        <header class="admin-topbar">

            <div class="topbar-left">
                <span class="breadcrumb-muted">Administração</span>
                <span class="breadcrumb-separator">/</span>
                <strong>Dashboard</strong>
            </div>

            <div class="topbar-right">

                <div class="system-status">
                    <span class="status-dot"></span>
                    Sistema operacional
                </div>

                <div class="topbar-divider"></div>

                <div class="topbar-date">
                    <span class="date-icon">◷</span>
                    <?= escapar($dataFormatada) ?>
                </div>

                <div class="topbar-avatar">
                    <?= strtoupper(substr($primeiroNome, 0, 1)) ?>
                </div>

            </div>

        </header>
        <?php require __DIR__ . '/../includes/menu_painel_mobile.php'; ?>

        <!-- CONTENT -->
        <div class="dashboard-content">

            <!-- WELCOME -->
            <section class="welcome-section">

                <div class="welcome-content">
                    <span class="section-tag">
                        <span class="tag-line"></span>
                        PAINEL DE CONTROLE
                    </span>

                    <h1>
                        Olá, <?= escapar($primeiroNome) ?>.
                        <span>Vamos evoluir?</span>
                    </h1>

                    <p>
                        Aqui está o resumo da operação da sua academia.
                        Gerencie sua equipe e acompanhe a Nox Gym.
                    </p>
                </div>

                <div class="welcome-decoration">
                    <div class="decoration-circle circle-one"></div>
                    <div class="decoration-circle circle-two"></div>
                    <span class="decoration-letter">N</span>
                    <span class="decoration-caption">NO EXCUSES.</span>
                </div>

            </section>

            <!-- SECTION TITLE -->
            <section class="section-heading">
                <div>
                    <span class="section-overline">VISÃO GERAL</span>
                    <h2>Resumo da academia</h2>
                </div>

                <span class="updated-label">
                    Dados atuais do banco
                </span>
            </section>

            <!-- STATS -->
            <section class="stats-grid">

                <article class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-green">♙</div>
                        <span class="stat-category">ALUNOS</span>
                    </div>

                    <strong class="stat-number">
                        <?= number_format($totalAlunos, 0, ',', '.') ?>
                    </strong>

                    <span class="stat-description">
                        Usuários cadastrados
                    </span>

                    <div class="stat-bottom-line green-line"></div>
                </article>

                <article class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-cyan">♟</div>
                        <span class="stat-category">EQUIPE</span>
                    </div>

                    <strong class="stat-number">
                        <?= number_format($totalInstrutores, 0, ',', '.') ?>
                    </strong>

                    <span class="stat-description">
                        Instrutores cadastrados
                    </span>

                    <div class="stat-bottom-line cyan-line"></div>
                </article>

                <article class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-purple">✉</div>
                        <span class="stat-category">COMUNICAÇÃO</span>
                    </div>

                    <strong class="stat-number">
                        <?= number_format($totalMensagens, 0, ',', '.') ?>
                    </strong>

                    <span class="stat-description">
                        Mensagens recebidas
                    </span>

                    <div class="stat-bottom-line purple-line"></div>
                </article>

                <article class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-orange">▤</div>
                        <span class="stat-category">CONTEÚDO</span>
                    </div>

                    <strong class="stat-number">
                        <?= number_format($totalConteudos, 0, ',', '.') ?>
                    </strong>

                    <span class="stat-description">
                        Conteúdos cadastrados
                    </span>

                    <div class="stat-bottom-line orange-line"></div>
                </article>

            </section>

            <!-- LOWER CONTENT -->
            <section class="dashboard-grid">

                <div class="dashboard-panel quick-panel">

                    <div class="panel-heading">
                        <div>
                            <span class="section-overline">ACESSO RÁPIDO</span>
                            <h2>Gerencie sua academia</h2>
                        </div>

                        <span class="panel-decoration">↗</span>
                    </div>

                    <div class="quick-actions">

                        <a href="administradores/index.php" class="quick-action">
                            <div class="quick-icon green-bg">♙</div>

                            <div class="quick-info">
                                <strong>Administradores</strong>
                                <small>Gerencie os acessos administrativos</small>
                            </div>

                            <span class="quick-arrow">→</span>
                        </a>

                        <a href="instrutores/index.php" class="quick-action">
                            <div class="quick-icon cyan-bg">♟</div>

                            <div class="quick-info">
                                <strong>Instrutores</strong>
                                <small>Gerencie os profissionais da academia</small>
                            </div>

                            <span class="quick-arrow">→</span>
                        </a>

                        <a href="mensagens/index.php" class="quick-action">
                            <div class="quick-icon purple-bg">✉</div>

                            <div class="quick-info">
                                <strong>Mensagens</strong>
                                <small>Consulte os contatos recebidos</small>
                            </div>

                            <span class="quick-arrow">→</span>
                        </a>

                        <a href="conteudos/index.php" class="quick-action">
                            <div class="quick-icon orange-bg">▤</div>

                            <div class="quick-info">
                                <strong>Conteúdos</strong>
                                <small>Atualize os conteúdos do site</small>
                            </div>

                            <span class="quick-arrow">→</span>
                        </a>

                    </div>

                </div>

                <div class="dashboard-panel system-panel">

                    <div class="panel-heading">
                        <div>
                            <span class="section-overline">SISTEMA</span>
                            <h2>Informações gerais</h2>
                        </div>

                        <span class="system-icon">◈</span>
                    </div>

                    <div class="system-highlight">
                        <div class="system-highlight-top">
                            <span class="system-indicator"></span>
                            <span>PAINEL ADMINISTRATIVO</span>
                        </div>

                        <strong>Operacional</strong>

                        <p>
                            O acesso administrativo está ativo e pronto para gerenciamento.
                        </p>
                    </div>

                    <div class="system-detail">
                        <span>Administrador conectado</span>
                        <strong><?= escapar($emailAdmin) ?></strong>
                    </div>

                    <div class="system-detail">
                        <span>Administradores cadastrados</span>
                        <strong><?= number_format($totalAdministradores, 0, ',', '.') ?></strong>
                    </div>

                    <div class="system-detail">
                        <span>Versão do painel</span>
                        <strong>1.0.0</strong>
                    </div>

                </div>

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
