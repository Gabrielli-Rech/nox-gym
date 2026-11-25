<?php
require_once __DIR__ . '/../inicio.php';
$id = idConsulta();
$mensagem = exigirRegistro(consulta($pdo, 'SELECT * FROM mensagens_contato WHERE id = ?', [$id])->fetch());
$titulo = 'Mensagem recebida';
$menu = 'Mensagens';
require __DIR__ . '/../../includes/painel.php';
?>
<div class="admin-form-card">
    <h2><?= escapar($mensagem['nome']) ?></h2>
    <p><?= escapar($mensagem['email']) ?> · <?= escapar($mensagem['telefone']) ?></p>
    <p><?= escapar($mensagem['criado_em']) ?> · <?= escapar($mensagem['status']) ?></p>
    <p><?= nl2br(escapar($mensagem['mensagem'])) ?></p>
    <form class="form-actions" method="post" action="atualizar.php?id=<?= $id ?>">
        <?php campoCsrf(); ?>
        <a class="form-cancel-button" href="index.php">Voltar</a>
        <?php if ($mensagem['status'] === 'nova'): ?>
            <button class="form-submit-button" name="status" value="lida">Marcar como lida</button>
        <?php endif; ?>
        <?php if ($mensagem['status'] !== 'respondida'): ?>
            <button class="form-submit-button" name="status" value="respondida">Marcar como respondida</button>
        <?php endif; ?>
    </form>
</div>
<?php require __DIR__ . '/../../includes/painel_fim.php'; ?>
