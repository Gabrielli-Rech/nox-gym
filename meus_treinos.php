<?php
require_once __DIR__ . '/includes/autenticacao.php';
require_once __DIR__ . '/includes/treinos_dados.php';
$usuario = exigirLogin();
$id = isset($_GET['id']) ? idConsulta() : null;
if ($id !== null) {
    $treino = treinoVisivel($pdo, $id, null, (int) $usuario['id']);
    $exercicios = exerciciosTreino($pdo, $id);
} else {
    $treinos = consulta($pdo, "SELECT t.id, t.nome, t.objetivo, t.status, i.nome AS instrutor FROM treinos t
        JOIN instrutores i ON i.id = t.instrutor_id WHERE t.usuario_id = ? AND t.status <> 'rascunho'
        ORDER BY (t.status = 'ativo') DESC, t.data_inicio DESC", [$usuario['id']])->fetchAll();
}
$titulo = 'Meus treinos';
require __DIR__ . '/includes/aluno_cabecalho.php';
?>
<h1>Meus treinos</h1>
<?php if ($id !== null): ?>
    <a href="meus_treinos.php">Todos os treinos</a>
    <section class="atalho"><?php require __DIR__ . '/includes/treino_detalhes.php'; ?></section>
<?php else: ?>
    <?php if (!$treinos): ?><p>Nenhum treino publicado para você.</p><?php endif; ?>
    <section class="atalhos">
        <?php foreach ($treinos as $treino): ?>
            <a class="atalho" href="?id=<?= (int) $treino['id'] ?>">
                <h2><?= escapar($treino['nome']) ?></h2>
                <p><?= escapar($treino['objetivo'] ?? '') ?></p>
                <p><?= escapar($treino['instrutor']) ?> · <?= escapar(camposTreino()['status'][3][$treino['status']]) ?></p>
            </a>
        <?php endforeach; ?>
    </section>
<?php endif; ?>
<?php require __DIR__ . '/includes/aluno_rodape.php'; ?>
