<?php

require_once __DIR__ . '/../inicio.php';

if (!function_exists('escapar')) {
    function escapar($valor): string
    {
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }
}

$nomeAdmin = $_SESSION['admin_nome'] ?? 'Administrador';
$emailAdmin = $_SESSION['admin_email'] ?? '';
$primeiroNome = explode(' ', trim($nomeAdmin))[0];

$pesquisa = trim(entrada($_GET, 'pesquisa'));
$filtroStatus = entrada($_GET, 'status');

$totalAdministradores = 0;
$totalAtivos = 0;
$totalInativos = 0;
$administradores = [];

try {
    $totalAdministradores = (int) $pdo
        ->query("SELECT COUNT(*) FROM administradores")
        ->fetchColumn();

    $totalAtivos = (int) $pdo
        ->query("SELECT COUNT(*) FROM administradores WHERE status = 'ativo'")
        ->fetchColumn();

    $totalInativos = (int) $pdo
        ->query("SELECT COUNT(*) FROM administradores WHERE status = 'inativo'")
        ->fetchColumn();

    $sql = "SELECT id, nome, email, foto, status, criado_em
            FROM administradores
            WHERE 1 = 1";

    $parametros = [];

    if ($pesquisa !== '') {
        $sql .= " AND (nome LIKE :pesquisa OR email LIKE :pesquisa_email)";
        $parametros['pesquisa'] = '%' . $pesquisa . '%';
        $parametros['pesquisa_email'] = $parametros['pesquisa'];
    }

    if (in_array($filtroStatus, ['ativo', 'inativo'], true)) {
        $sql .= " AND status = :status";
        $parametros['status'] = $filtroStatus;
    }

    $sql .= " ORDER BY nome ASC";

    $consulta = $pdo->prepare($sql);
    $consulta->execute($parametros);

    $administradores = $consulta->fetchAll();

} catch (PDOException $e) {
    error_log('NOX GYM: falha em operação administrativa.');
    $erroConsulta = 'Não foi possível carregar os administradores.';
}

$dataFormatada = date('d/m/Y');

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Administradores | Nox Gym Admin</title>

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

                <form method="post" action="../logout.php"><?php campoCsrf(); ?><button class="logout-button" type="submit" aria-label="Sair">
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
                <strong>Administradores</strong>
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
        <?php require __DIR__ . '/../../includes/menu_painel_mobile.php'; ?>

        <!-- CONTENT -->
        <div class="dashboard-content">
<?php if (isset($_SESSION['aviso_nox'])): ?>
    <p class="dashboard-panel" role="status"><?= escapar($_SESSION['aviso_nox']) ?></p>
    <?php unset($_SESSION['aviso_nox']); ?>
<?php endif; ?>

            <!-- PAGE HEADER -->
            <section class="management-header">

                <div>
                    <span class="section-overline">CONTROLE DE ACESSO</span>

                    <h1>Administradores</h1>

                    <p>
                        Gerencie os usuários que possuem acesso ao painel administrativo da Nox Gym.
                    </p>
                </div>

                <a href="criar.php" class="management-primary-button">
                    <span>+</span>
                    Novo administrador
                </a>

            </section>

            <!-- STATISTICS -->
            <section class="management-stats">

                <article class="management-stat-card">
                    <div class="management-stat-icon stat-icon-total">♙</div>

                    <div>
                        <span>Total de administradores</span>
                        <strong><?= number_format($totalAdministradores, 0, ',', '.') ?></strong>
                    </div>
                </article>

                <article class="management-stat-card">
                    <div class="management-stat-icon stat-icon-active">✓</div>

                    <div>
                        <span>Administradores ativos</span>
                        <strong><?= number_format($totalAtivos, 0, ',', '.') ?></strong>
                    </div>
                </article>

                <article class="management-stat-card">
                    <div class="management-stat-icon stat-icon-inactive">○</div>

                    <div>
                        <span>Administradores inativos</span>
                        <strong><?= number_format($totalInativos, 0, ',', '.') ?></strong>
                    </div>
                </article>

            </section>

            <!-- TABLE PANEL -->
            <section class="management-panel">

                <div class="management-panel-heading">

                    <div>
                        <span class="section-overline">EQUIPE ADMINISTRATIVA</span>
                        <h2>Administradores cadastrados</h2>
                        <p>Consulte e gerencie os acessos ao sistema.</p>
                    </div>

                    <span class="management-total">
                        <?= count($administradores) ?> resultado(s)
                    </span>

                </div>

                <!-- FILTERS -->
                <form method="GET" class="management-filters">

                    <div class="management-search">
                        <span>⌕</span>

                        <input
                            type="search"
                            name="pesquisa"
                            placeholder="Pesquisar por nome ou e-mail..."
                            value="<?= escapar($pesquisa) ?>"
                        >
                    </div>

                    <select name="status" class="management-select">
                        <option value="">Todos os status</option>

                        <option value="ativo" <?= $filtroStatus === 'ativo' ? 'selected' : '' ?>>
                            Ativos
                        </option>

                        <option value="inativo" <?= $filtroStatus === 'inativo' ? 'selected' : '' ?>>
                            Inativos
                        </option>
                    </select>

                    <button type="submit" class="management-filter-button">
                        Filtrar
                    </button>

                    <?php if ($pesquisa !== '' || $filtroStatus !== ''): ?>
                        <a href="index.php" class="management-clear-button">
                            Limpar
                        </a>
                    <?php endif; ?>

                </form>

                <?php if (!empty($erroConsulta)): ?>

                    <div class="management-error">
                        <?= escapar($erroConsulta) ?>
                    </div>

                <?php endif; ?>

                <!-- TABLE -->
                <div class="management-table-wrapper">

                    <table class="management-table">

                        <thead>
                            <tr>
                                <th>ADMINISTRADOR</th>
                                <th>E-MAIL</th>
                                <th>STATUS</th>
                                <th>CADASTRO</th>
                                <th class="actions-column">AÇÕES</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (empty($administradores)): ?>

                                <tr>
                                    <td colspan="5" class="management-empty">
                                        <div class="empty-icon">♙</div>

                                        <strong>Nenhum administrador encontrado</strong>

                                        <span>
                                            Tente alterar os filtros ou cadastre um novo administrador.
                                        </span>
                                    </td>
                                </tr>

                            <?php else: ?>

                                <?php foreach ($administradores as $admin): ?>

                                    <tr>

                                        <td>
                                            <div class="admin-person">

                                                <div class="admin-person-avatar">
                                                    <?php if (!empty($admin['foto']) && basename($admin['foto']) === $admin['foto'] && is_file(__DIR__ . '/../../uploads/' . $admin['foto'])): ?>
                                                        <img
                                                            src="../../uploads/<?= escapar($admin['foto']) ?>"
                                                            alt="Foto de <?= escapar($admin['nome']) ?>"
                                                        >
                                                    <?php else: ?>
                                                        <?= strtoupper(substr($admin['nome'], 0, 1)) ?>
                                                    <?php endif; ?>
                                                </div>

                                                <div>
                                                    <strong><?= escapar($admin['nome']) ?></strong>
                                                    <small>ID #<?= (int) $admin['id'] ?></small>
                                                </div>

                                            </div>
                                        </td>

                                        <td class="admin-email">
                                            <?= escapar($admin['email']) ?>
                                        </td>

                                        <td>
                                            <span class="status-badge <?= $admin['status'] === 'ativo' ? 'status-active' : 'status-inactive' ?>">
                                                <span></span>
                                                <?= $admin['status'] === 'ativo' ? 'Ativo' : 'Inativo' ?>
                                            </span>
                                        </td>

                                        <td class="admin-date">
                                            <?= !empty($admin['criado_em']) ? date('d/m/Y', strtotime($admin['criado_em'])) : '—' ?>
                                        </td>

                                        <td>
                                            <div class="admin-actions">

                                                <a
                                                    href="editar.php?id=<?= (int) $admin['id'] ?>"
                                                    class="action-button edit-action"
                                                    title="Editar administrador"
                                                >
                                                    ✎
                                                </a>

                                                <a
                                                    href="excluir.php?id=<?= (int) $admin['id'] ?>"
                                                    class="action-button delete-action"
                                                    title="Desativar administrador"
                                                >
                                                    ×
                                                </a>

                                            </div>
                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

                <div class="management-table-footer">
                    <span>
                        Exibindo <?= count($administradores) ?> administrador(es).
                    </span>

                    <span>
                        NOX GYM <span class="footer-dot">•</span> GESTÃO ADMINISTRATIVA
                    </span>
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
