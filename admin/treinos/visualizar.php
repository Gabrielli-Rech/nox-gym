<?php
require_once __DIR__ . '/../inicio.php';
require_once __DIR__ . '/../../includes/treinos_dados.php';
$id = idConsulta();
$treino = treinoVisivel($pdo, $id);
$campos = ['status' => camposTreino()['status']];
$registro = $treino;
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificarCsrf();
    try {
        $dados = lerFormulario($campos);
        $pdo->beginTransaction();
        gravarRegistro($pdo, 'treinos', $dados, $id);
        logAdmin($pdo, 'treino_status', $id);
        $pdo->commit();
        $_SESSION['aviso_nox'] = 'Status do treino atualizado.';
        redirecionar('visualizar.php?id=' . $id);
    } catch (InvalidArgumentException $e) {
        $erro = $e->getMessage();
    }
}
$exercicios = exerciciosTreino($pdo, $id);
$raizImagens = '../../';
$titulo = 'Detalhes do treino';
$menu = 'Treinos';
require __DIR__ . '/../../includes/painel.php';
?>
<div class="dashboard-panel"><?php require __DIR__ . '/../../includes/treino_detalhes.php'; ?></div>
<?php
require __DIR__ . '/../../includes/formulario_painel.php';
require __DIR__ . '/../../includes/painel_fim.php';
