<?php
require_once __DIR__ . '/inicio.php';
$alunos = consulta($pdo, 'SELECT u.id, u.nome FROM usuarios u WHERE EXISTS
    (SELECT 1 FROM treinos t WHERE t.usuario_id = u.id AND t.instrutor_id = ?) ORDER BY u.nome', [$instrutorId])->fetchAll();
$titulo = 'Alunos';
require __DIR__ . '/../includes/painel.php';
?>
<div class="dashboard-panel quick-actions">
    <?php if (!$alunos): ?><p>A administração pode criar o primeiro treino para vincular um aluno a você.</p><?php endif; ?>
    <?php foreach ($alunos as $aluno): ?>
        <a class="quick-action" href="aluno.php?id=<?= (int) $aluno['id'] ?>"><?= escapar($aluno['nome']) ?></a>
    <?php endforeach; ?>
</div>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
