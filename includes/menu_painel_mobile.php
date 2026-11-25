<?php require __DIR__ . '/menu_painel.php'; ?>
<details class="painel-menu-mobile">
    <summary class="form-cancel-button">Menu do painel</summary>
    <nav class="sidebar-nav" aria-label="Menu do painel">
        <?php foreach ($rotas as $rota => $nomeRota): ?>
            <a class="nav-item" href="<?= $prefixo . $rota ?>"><?= escapar($nomeRota) ?></a>
        <?php endforeach; ?>
        <a class="nav-item" href="<?= $prefixo ?>../index.php">Visualizar site</a>
        <form method="post" action="<?= $prefixo ?>logout.php">
            <?php campoCsrf(); ?>
            <button class="form-cancel-button" type="submit">Sair do painel</button>
        </form>
    </nav>
</details>
