
<?php

require __DIR__ . '/menu_painel.php';

$area = $areaInstrutor ? 'Instrutor' : 'Administração';

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="<?= $prefixo ?>../assets/css/admin/admin.css">

    <link rel="stylesheet" href="<?= $prefixo ?>../assets/css/admin/instrutores.css">

    <?php if (!empty($cssExtra)): ?>
        <link rel="stylesheet" href="<?= escapar($cssExtra) ?>">
    <?php endif; ?>

</head>

<body>

<div class="admin-layout">

    <aside class="admin-sidebar">

        <a class="admin-brand" href="<?= $prefixo . array_key_first($rotas) ?>">

            <div class="brand-symbol">N</div>

            <div class="brand-text">
                <strong>NOX<span>GYM</span></strong>
                <small><?= escapar($area) ?></small>
            </div>

        </a>

        <div class="sidebar-section-label">
            MENU PRINCIPAL
        </div>

        <nav class="sidebar-nav">

            <?php foreach ($rotas as $rota => $nomeRota): ?>

                <a class="nav-item <?= basename($_SERVER['PHP_SELF']) === $rota ? 'active' : '' ?>"
                href="<?= $prefixo . $rota ?>">
                    <span class="nav-icon">•</span>
                    <?= escapar($nomeRota) ?>
                </a>

            <?php endforeach; ?>

            <a class="nav-item" href="<?= $prefixo ?>../index.php">
                <span class="nav-icon">↗</span>
                Visualizar site
            </a>

        </nav>

        <div class="sidebar-bottom">

            <form method="post" action="<?= $prefixo ?>logout.php">

                <?php campoCsrf(); ?>

                <button class="form-cancel-button" type="submit">
                    ↪ Sair do painel
                </button>

            </form>

        </div>

    </aside>

    <main class="admin-main">

        <header class="admin-topbar">

            <div class="topbar-left">

                <span class="breadcrumb-muted">
                    <?= escapar($area) ?>
                </span>

                <span class="breadcrumb-separator">/</span>

                

            </div>

            <span class="topbar-date">
                <?= date('d/m/Y') ?>
            </span>

        </header>

        <?php require __DIR__ . '/menu_painel_mobile.php'; ?>

        <div class="dashboard-content">

            

            <?php if (isset($_SESSION['aviso_nox'])): ?>

                <p class="dashboard-panel" role="status">
                    <?= escapar($_SESSION['aviso_nox']) ?>
                </p>

                <?php unset($_SESSION['aviso_nox']); ?>

            <?php endif; ?>
