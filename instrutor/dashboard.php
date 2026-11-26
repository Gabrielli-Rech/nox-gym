<?php
require_once __DIR__ . '/inicio.php';
$resumo = consulta($pdo, "SELECT COUNT(DISTINCT usuario_id) AS alunos, COUNT(*) AS treinos,
    COUNT(CASE WHEN status = 'ativo' THEN 1 END) AS ativos FROM treinos WHERE instrutor_id = ?", [$instrutorId])->fetch();
$ativos = consulta($pdo, "SELECT id, nome FROM treinos WHERE instrutor_id = ? AND status = 'ativo' ORDER BY data_inicio DESC, id DESC", [$instrutorId])->fetchAll();
$titulo = 'Dashboard';
require __DIR__ . '/../includes/painel.php';
?>
<link rel="stylesheet" href="../assets/css/admin/dashboard-instrutor.css">
<section class="welcome-section">
    <div class="welcome-content">
        <h1>Olá, <?= escapar($instrutor['nome']) ?>.</h1>
        <p><?= escapar($instrutor['email'] ?? '') ?> <?= escapar($instrutor['telefone'] ?? '') ?></p>
    </div>
</section>
<section class="stats-grid">
    <?php foreach (['alunos' => 'Alunos relacionados', 'treinos' => 'Treinos cadastrados', 'ativos' => 'Treinos ativos'] as $chave => $rotulo): ?>
        <article class="stat-card"><strong class="stat-number"><?= (int) $resumo[$chave] ?></strong><span class="stat-description"><?= $rotulo ?></span></article>
    <?php endforeach; ?>
</section>
<section class="dashboard-panel">
    <h2>Treinos ativos</h2>
    <div class="quick-actions">
        <?php if (!$ativos): ?><p>Nenhum treino ativo.</p><?php endif; ?>
        <?php foreach ($ativos as $ativo): ?>
            <a class="quick-action" href="treino.php?id=<?= (int) $ativo['id'] ?>"><?= escapar($ativo['nome']) ?></a>
        <?php endforeach; ?>
    </div>
</section>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>