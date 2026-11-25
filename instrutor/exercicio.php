<?php
require_once __DIR__ . '/inicio.php';
require_once __DIR__ . '/../includes/treinos_dados.php';
$treinoId = idConsulta('treino');
$treino = treinoVisivel($pdo, $treinoId, $instrutorId);
$id = isset($_GET['id']) ? idConsulta() : null;
$registro = $id === null ? [] : exigirRegistro(consulta($pdo,
    'SELECT * FROM treino_exercicios WHERE id = ? AND treino_id = ?', [$id, $treinoId])->fetch());
$opcoes = consulta($pdo, "SELECT id, nome FROM exercicios WHERE status = 'ativo' OR id = ? ORDER BY nome", [$registro['exercicio_id'] ?? null])->fetchAll(PDO::FETCH_KEY_PAIR);
$campos = [
    'exercicio_id' => ['Exercício', 'select', true, $opcoes],
    'ordem' => ['Ordem', 'number', true, 1],
    'series' => ['Séries', 'number', true, 1],
    'repeticoes' => ['Repetições', 'text', true, 30],
    'carga_sugerida' => ['Carga sugerida', 'text', false, 60],
    'descanso_segundos' => ['Descanso em segundos', 'number', false, 0],
    'observacoes' => ['Observações', 'textarea', false, 16000],
];
$voltar = 'treino.php?id=' . $treinoId;
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificarCsrf();
    try {
        if ($id !== null && entrada($_POST, 'acao') === 'remover') {
            consulta($pdo, 'DELETE FROM treino_exercicios WHERE id = ? AND treino_id = ?', [$id, $treinoId]);
            $_SESSION['aviso_nox'] = 'Exercício removido do treino.';
        } else {
            $dados = lerFormulario($campos);
            $dados['treino_id'] = $treinoId;
            gravarRegistro($pdo, 'treino_exercicios', $dados, $id);
            $_SESSION['aviso_nox'] = 'Exercício salvo no treino.';
        }
        redirecionar($voltar);
    } catch (InvalidArgumentException | PDOException $e) {
        $erro = $e instanceof InvalidArgumentException ? $e->getMessage() :
            (($e->errorInfo[1] ?? null) === 1062 ? 'Já existe um exercício nessa posição do treino.' : 'Não foi possível salvar o exercício.');
        $registro = $_POST;
    }
}
$titulo = $id === null ? 'Adicionar exercício' : 'Editar exercício do treino';
$menu = 'Treinos';
require __DIR__ . '/../includes/painel.php';
require __DIR__ . '/../includes/formulario_painel.php';
?>
<?php if ($id !== null): ?>
    <form class="form-actions" method="post">
        <?php campoCsrf(); ?>
        <button class="form-cancel-button" type="submit" name="acao" value="remover">Remover exercício deste treino</button>
    </form>
<?php endif; ?>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
