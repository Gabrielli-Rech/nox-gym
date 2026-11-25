<?php
require_once __DIR__ . '/inicio.php';
$id = idConsulta();
$aluno = exigirRegistro(consulta($pdo, 'SELECT u.nome, u.email, u.telefone FROM usuarios u WHERE u.id = ? AND EXISTS
    (SELECT 1 FROM treinos t WHERE t.usuario_id = u.id AND t.instrutor_id = ?)', [$id, $instrutorId])->fetch());
$treinos = consulta($pdo, 'SELECT id, nome, status FROM treinos WHERE usuario_id = ? AND instrutor_id = ? ORDER BY id DESC', [$id, $instrutorId])->fetchAll();
$titulo = $aluno['nome'];
$menu = 'Alunos';
require __DIR__ . '/../includes/painel.php';
?>
<div class="dashboard-panel">
    <p><?= escapar($aluno['email']) ?> · <?= escapar($aluno['telefone'] ?? 'Sem telefone') ?></p>
    <h2>Treinos do aluno</h2>
    <div class="quick-actions">
    <?php foreach ($treinos as $treino): ?>
        <a class="quick-action" href="treino.php?id=<?= (int) $treino['id'] ?>"><?= escapar($treino['nome']) ?> · <?= escapar($treino['status']) ?></a>
    <?php endforeach; ?>
    </div>
</div>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
