<?php

require_once __DIR__ . '/../inicio.php';

if (!function_exists('escapar')) {
    function escapar($valor): string
    {
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }
}


$busca = trim(entrada($_GET, 'busca'));
$status = entrada($_GET, 'status');

if (!in_array($status, ['', 'ativo', 'inativo'], true)) {
    $status = '';
}


$totalInstrutores = (int) $pdo->query(
    "SELECT COUNT(*) FROM instrutores"
)->fetchColumn();

$totalAtivos = (int) $pdo->query(
    "SELECT COUNT(*) FROM instrutores WHERE status = 'ativo'"
)->fetchColumn();

$totalInativos = (int) $pdo->query(
    "SELECT COUNT(*) FROM instrutores WHERE status = 'inativo'"
)->fetchColumn();


$sql = "SELECT
            id,
            nome,
            cpf,
            email,
            telefone,
            status,
            criado_em
        FROM instrutores
        WHERE 1 = 1";

$parametros = [];

if ($busca !== '') {
    $sql .= " AND (
        nome LIKE ?
        OR cpf LIKE ?
        OR email LIKE ?
    )";

    $termo = '%' . $busca . '%';

    $parametros[] = $termo;
    $parametros[] = $termo;
    $parametros[] = $termo;
}

if ($status !== '') {
    $sql .= " AND status = ?";
    $parametros[] = $status;
}

$sql .= " ORDER BY criado_em DESC";

$consulta = $pdo->prepare($sql);
$consulta->execute($parametros);

$instrutores = $consulta->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Instrutores | Nox Gym</title>

    <link rel="stylesheet" href="../../assets/css/admin/admin.css">
    <link rel="stylesheet" href="../../assets/css/admin/instrutores.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>

    <div class="admin-layout">

        

        <aside class="admin-sidebar">

            <a href="../dashboard.php" class="admin-brand">

                <div class="brand-symbol">
                    N
                </div>

                <div class="brand-text">
                    <strong>NOX<span>GYM</span></strong>
                    <small>ADMINISTRATION</small>
                </div>

            </a>

            <div class="sidebar-section-label">
                MENU PRINCIPAL
            </div>

            <nav class="sidebar-nav">

                <a href="../dashboard.php" class="nav-item">

                    <span class="nav-icon">▦</span>

                    <span>Dashboard</span>

                </a>

                <a href="../administradores/index.php" class="nav-item">

                    <span class="nav-icon">♙</span>

                    <span>Administradores</span>

                </a>

                <a href="index.php" class="nav-item active">

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

                <a href="../../index.php" class="nav-item" target="_blank">

                    <span class="nav-icon">↗</span>

                    <span>Visualizar site</span>

                </a>

            </nav>

            

            <div class="sidebar-bottom">

                <div class="sidebar-user">

                    <div class="user-avatar">
                        <?= escapar(mb_strtoupper(mb_substr($_SESSION['admin_nome'] ?? 'A', 0, 1, 'UTF-8'), 'UTF-8')) ?>
                    </div>

                    <div class="user-details">

                        <strong>
                            <?= escapar($_SESSION['admin_nome'] ?? 'Administrador') ?>
                        </strong>

                        <span>Administrador</span>

                    </div>

                </div>

                <form method="post" action="../logout.php"><?php campoCsrf(); ?><button class="logout-button" type="submit" aria-label="Sair">
                        <span>↪</span>
                        Sair do painel
                    </button></form>

                <div class="sidebar-version">
                    NOX GYM SYSTEM • V1.0
                </div>

            </div>

        </aside>

        
        <main class="admin-main">

            <header class="admin-topbar">

                <div>
                    <h2>Gerenciamento de instrutores</h2>
                </div>

                <div class="topbar-right">

                    <div class="system-status">
                        <span></span>
                        Sistema operacional
                    </div>

                    <span class="topbar-date">
                        <?= date('d/m/Y') ?>
                    </span>

                </div>

            </header>
            <?php require __DIR__ . '/../../includes/menu_painel_mobile.php'; ?>

            <section class="instructors-content">
                <?php if (isset($_SESSION['aviso_nox'])): ?>
                    <p role="status"><?= escapar($_SESSION['aviso_nox']) ?></p>
                <?php unset($_SESSION['aviso_nox']);
                endif; ?>

                

                <div class="instructors-heading">

                    <div>

                        <span class="instructors-eyebrow">
                            GESTÃO DA ACADEMIA
                        </span>

                        <h1>Instrutores</h1>

                        <p>
                            Gerencie os profissionais responsáveis pelos
                            treinos e acompanhamento dos alunos.
                        </p>

                    </div>

                    <a href="criar.php" class="instructor-create-button">
                        <i data-lucide="plus"></i>
                        Novo instrutor
                    </a>

                </div>

                

                <div class="instructor-stats">

                    <article class="instructor-stat-card">

                        <div class="instructor-stat-icon total">
                            <i data-lucide="users"></i>
                        </div>

                        <div>
                            <span>Total de instrutores</span>
                            <strong><?= $totalInstrutores ?></strong>
                        </div>

                    </article>

                    <article class="instructor-stat-card">

                        <div class="instructor-stat-icon active">
                            <i data-lucide="user-check"></i>
                        </div>

                        <div>
                            <span>Instrutores ativos</span>
                            <strong><?= $totalAtivos ?></strong>
                        </div>

                    </article>

                    <article class="instructor-stat-card">

                        <div class="instructor-stat-icon inactive">
                            <i data-lucide="user-x"></i>
                        </div>

                        <div>
                            <span>Instrutores inativos</span>
                            <strong><?= $totalInativos ?></strong>
                        </div>

                    </article>

                </div>

            

                <div class="instructor-toolbar">

                    <form method="GET" class="instructor-filter-form">

                        <div class="instructor-search">

                            <i data-lucide="search"></i>

                            <input
                                type="text"
                                name="busca"
                                placeholder="Pesquisar por nome, CPF ou e-mail..."
                                value="<?= escapar($busca) ?>">

                        </div>

                        <select name="status">

                            <option value="">Todos os status</option>

                            <option value="ativo"
                                <?= $status === 'ativo' ? 'selected' : '' ?>>
                                Ativos
                            </option>

                            <option value="inativo"
                                <?= $status === 'inativo' ? 'selected' : '' ?>>
                                Inativos
                            </option>

                        </select>

                        <button type="submit" class="instructor-filter-button">
                            <i data-lucide="filter"></i>
                            Filtrar
                        </button>

                        <a href="index.php" class="instructor-clear-button">
                            Limpar
                        </a>

                    </form>

                </div>

                <!-- TABELA -->

                <div class="instructor-table-card">

                    <div class="instructor-table-heading">

                        <div>
                            <h2>Equipe de instrutores</h2>

                            <p>
                                <?= count($instrutores) ?>
                                resultado(s) encontrado(s)
                            </p>
                        </div>

                    </div>

                    <div class="instructor-table-wrapper">

                        <table class="instructor-table">

                            <thead>

                                <tr>
                                    <th>Instrutor</th>
                                    <th>CPF</th>
                                    <th>Contato</th>
                                    <th>Status</th>
                                    <th>Cadastro</th>
                                    <th>Ações</th>
                                </tr>

                            </thead>

                            <tbody>

                                <?php if (!empty($instrutores)): ?>

                                    <?php foreach ($instrutores as $instrutor): ?>

                                        <tr>

                                            <td>

                                                <div class="instructor-identity">

                                                    <div class="instructor-avatar">
                                                        <?= escapar(mb_strtoupper(mb_substr($instrutor['nome'], 0, 1, 'UTF-8'), 'UTF-8')) ?>
                                                    </div>

                                                    <strong>
                                                        <?= escapar($instrutor['nome']) ?>
                                                    </strong>

                                                </div>

                                            </td>

                                            <td>
                                                <?= escapar($instrutor['cpf']) ?>
                                            </td>

                                            <td>

                                                <div class="instructor-contact">

                                                    <span>
                                                        <?= escapar($instrutor['email'] ?: 'Não informado') ?>
                                                    </span>

                                                    <small>
                                                        <?= escapar($instrutor['telefone'] ?: 'Sem telefone') ?>
                                                    </small>

                                                </div>

                                            </td>

                                            <td>

                                                <span class="instructor-status <?= escapar($instrutor['status']) ?>">
                                                    <?= $instrutor['status'] === 'ativo' ? 'Ativo' : 'Inativo' ?>
                                                </span>

                                            </td>

                                            <td>
                                                <?= !empty($instrutor['criado_em'])
                                                    ? date('d/m/Y', strtotime($instrutor['criado_em']))
                                                    : '—' ?>
                                            </td>

                                            <td>

                                                <div class="instructor-actions">

                                                    <a
                                                        href="editar.php?id=<?= (int) $instrutor['id'] ?>"
                                                        class="instructor-action edit"
                                                        title="Editar instrutor">
                                                        <i data-lucide="pencil"></i>
                                                    </a>

                                                    <a
                                                        href="excluir.php?id=<?= (int) $instrutor['id'] ?>"
                                                        class="instructor-action delete"
                                                        title="Desativar instrutor">
                                                        <i data-lucide="trash-2"></i>
                                                    </a>

                                                </div>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>

                                        <td colspan="6">

                                            <div class="instructor-empty">

                                                <i data-lucide="users"></i>

                                                <strong>
                                                    Nenhum instrutor encontrado
                                                </strong>

                                                <span>
                                                    Tente alterar os filtros ou cadastre um novo instrutor.
                                                </span>

                                            </div>

                                        </td>

                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </section>

        </main>

    </div>

    <script>
        window.lucide?.createIcons();
    </script>

</body>

</html>