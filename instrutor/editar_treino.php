<?php
require_once __DIR__ . '/inicio.php';
require_once __DIR__ . '/../includes/treinos_dados.php';

$id = isset($_GET['id']) ? idConsulta() : null;
$campos = camposTreino();
$registro = $id === null ? ['data_inicio' => date('Y-m-d'), 'status' => 'rascunho'] : treinoVisivel($pdo, $id, $instrutorId);
$voltar = $id === null ? 'treinos.php' : 'treino.php?id=' . $id;
$titulo = $id === null ? 'Novo treino' : 'Editar treino';
$menu = 'Treinos';
$erro = '';
if ($id === null) {
    $alunos = consulta($pdo, "SELECT u.id, CONCAT(u.nome, ' — ', u.email) FROM usuarios u WHERE u.status = 'ativo'
        AND EXISTS (SELECT 1 FROM treinos t WHERE t.usuario_id = u.id AND t.instrutor_id = ?) ORDER BY u.nome", [$instrutorId])->fetchAll(PDO::FETCH_KEY_PAIR);
    $campos = ['usuario_id' => ['Aluno', 'select', true, $alunos]] + $campos;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificarCsrf();
    try {
        $dados = validarDatasTreino(lerFormulario($campos));
        if ($id === null) {
            $dados['instrutor_id'] = $instrutorId;
        }
        $idSalvo = gravarRegistro($pdo, 'treinos', $dados, $id);
        $_SESSION['aviso_nox'] = 'Treino salvo com sucesso.';
        redirecionar('treino.php?id=' . $idSalvo);
    } catch (InvalidArgumentException | PDOException $e) {
        $erro = $e instanceof InvalidArgumentException ? $e->getMessage() : 'Não foi possível salvar o treino.';
        $registro = $_POST;
    }
}
require __DIR__ . '/../includes/painel.php';
if ($id === null && !$alunos): ?>
    <p class="dashboard-panel">Peça à administração que crie o primeiro treino para vincular um aluno ativo à sua conta.</p>
<?php endif;
require __DIR__ . '/../includes/formulario_painel.php';
require __DIR__ . '/../includes/painel_fim.php';
