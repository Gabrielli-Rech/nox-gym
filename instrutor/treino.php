<?php
require_once __DIR__ . '/inicio.php';
require_once __DIR__ . '/../includes/treinos_dados.php';
$id = idConsulta();
$treino = treinoVisivel($pdo, $id, $instrutorId);
$exercicios = exerciciosTreino($pdo, $id);
$titulo = 'Detalhes do treino';
$menu = 'Treinos';
$raizImagens = '../';
$editarExercicios = true;
require __DIR__ . '/../includes/painel.php';
?>
<div class="dashboard-panel">
    <?php require __DIR__ . '/../includes/treino_detalhes.php'; ?>
    <div class="form-actions">
        <a class="form-cancel-button" href="editar_treino.php?id=<?= $id ?>">Editar treino</a>
        <a class="form-submit-button" href="exercicio.php?treino=<?= $id ?>">Adicionar exercício</a>
    </div>
</div>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
