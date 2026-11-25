<?php
require_once __DIR__ . '/inicio.php';
$treinos = consulta($pdo, 'SELECT t.id, t.nome, t.status, u.nome AS aluno FROM treinos t
    JOIN usuarios u ON u.id = t.usuario_id WHERE t.instrutor_id = ? ORDER BY t.id DESC', [$instrutorId])->fetchAll();
$titulo = 'Treinos';
require __DIR__ . '/../includes/painel.php';
?>
<a class="instructor-create-button" href="editar_treino.php">+ Novo treino</a>
<div class="dashboard-panel quick-actions">
    <?php if (!$treinos): ?><p>Nenhum treino cadastrado.</p><?php endif; ?>
    <?php foreach ($treinos as $treino): ?>
        <a class="quick-action" href="treino.php?id=<?= (int) $treino['id'] ?>"><?= escapar($treino['nome']) ?> · <?= escapar($treino['aluno']) ?> · <?= escapar($treino['status']) ?></a>
    <?php endforeach; ?>
</div>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
