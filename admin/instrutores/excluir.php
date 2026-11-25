<?php
require_once __DIR__ . '/../inicio.php';
$id = idConsulta();
$registro = exigirRegistro(consulta($pdo, 'SELECT nome FROM instrutores WHERE id = ?', [$id])->fetch());
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificarCsrf();
    $pdo->beginTransaction();
    gravarRegistro($pdo, 'instrutores', ['status' => 'inativo'], $id);
    logAdmin($pdo, 'instrutor_desativado', $id);
    $pdo->commit();
    $_SESSION['aviso_nox'] = 'Instrutor desativado.';
    redirecionar('index.php');
}
$titulo = 'Desativar instrutor';
$menu = 'Instrutores';
require __DIR__ . '/../../includes/painel.php';
?>
<div class="admin-form-card">
    <p>Desativar o acesso de <?= escapar($registro['nome']) ?>?</p>
    <form class="form-actions" method="post">
        <?php campoCsrf(); ?>
        <a class="form-cancel-button" href="index.php">Voltar</a>
        <button class="form-submit-button" type="submit">Desativar</button>
    </form>
</div>
<?php require __DIR__ . '/../../includes/painel_fim.php'; ?>
