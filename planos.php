<?php $pageTitle = 'Nox Gym | Planos';
require_once __DIR__ . '/includes/formularios.php';
tratarErrosBanco();
require_once __DIR__ . '/config/conexao.php';
$planos = consulta($pdo, "SELECT nome, valor, duracao_dias, descricao, beneficios FROM planos WHERE status = 'ativo' ORDER BY valor, id")->fetchAll();
require_once __DIR__ . '/includes/header.php'; ?>

<section class="page-hero">
    <div class="container"><span class="eyebrow">Escolha sua jornada</span>
        <h1>Planos feitos<br>para evoluir.</h1>
        <p>Encontre a opção que combina com sua rotina e transforme consistência em resultado.</p>
    </div>
</section>
<section class="section">
    <div class="container grid">
        <?php if (!$planos): ?><p>Nenhum plano disponível no momento.</p><?php endif; ?>
        <?php foreach ($planos as $indice => $plano): ?>
            <article class="card<?= $indice === 1 ? ' featured' : '' ?>">
                <div class="card-body">
                    <h3><?= escapar($plano['nome']) ?></h3>
                    <div class="price">R$ <?= number_format((float) $plano['valor'], 2, ',', '.') ?><small>/<?= (int) $plano['duracao_dias'] ?> dias</small></div>
                    <?php if ($plano['descricao']): ?><p><?= nl2br(escapar($plano['descricao'])) ?></p><?php endif; ?>
                    <ul class="feature-list">
                        <?php foreach (preg_split('/\R/u', $plano['beneficios'] ?? '', -1, PREG_SPLIT_NO_EMPTY) as $beneficio): ?>
                            <li><?= escapar($beneficio) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <a class="btn<?= $indice === 1 ? '' : ' btn-outline' ?>" href="contato.php">Tenho interesse</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>