<?php
require_once __DIR__ . '/includes/autenticacao.php';
require_once __DIR__ . '/includes/assinaturas_dados.php';
$usuario = exigirLogin();
$assinaturas = assinaturasAluno($pdo, (int) $usuario['id']);
$titulo = 'Minha assinatura';
require __DIR__ . '/includes/aluno_cabecalho.php';
?>
<h1>Minhas assinaturas</h1>
<?php if (!$assinaturas): ?><p>Nenhuma assinatura cadastrada.</p><?php endif; ?>
<section class="atalhos">
<?php foreach ($assinaturas as $assinatura): ?>
    <article class="atalho">
        <h2><?= escapar($assinatura['nome']) ?></h2>
        <p><?= nl2br(escapar($assinatura['descricao'] ?? '')) ?></p>
        <p><?= nl2br(escapar($assinatura['beneficios'] ?? '')) ?></p>
        <p>Valor contratado R$ <?= number_format((float) $assinatura['valor_contratado'], 2, ',', '.') ?></p>
        <p>De <?= escapar($assinatura['data_inicio']) ?> até <?= escapar($assinatura['data_fim']) ?></p>
        <p>Situação <?= escapar($assinatura['situacao']) ?></p>
    </article>
<?php endforeach; ?>
</section>
<?php require __DIR__ . '/includes/aluno_rodape.php'; ?>
