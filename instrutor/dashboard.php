
<?php

require_once __DIR__ . '/inicio.php';

// Resumo geral do instrutor.
$resumo = consulta(
    $pdo,
    "SELECT
        COUNT(DISTINCT usuario_id) AS alunos,
        COUNT(*) AS treinos,
        COUNT(CASE WHEN status = 'ativo' THEN 1 END) AS ativos,
        COUNT(CASE WHEN status = 'rascunho' THEN 1 END) AS rascunhos
     FROM treinos
     WHERE instrutor_id = ?",
    [$instrutorId]
)->fetch(PDO::FETCH_ASSOC);

// Treinos ativos.
$ativos = consulta(
    $pdo,
    "SELECT
        t.id,
        t.nome,
        t.data_inicio,
        u.nome AS aluno
     FROM treinos t
     INNER JOIN usuarios u ON u.id = t.usuario_id
     WHERE t.instrutor_id = ?
       AND t.status = 'ativo'
     ORDER BY t.data_inicio DESC, t.id DESC
     LIMIT 5",
    [$instrutorId]
)->fetchAll(PDO::FETCH_ASSOC);

// Treinos cadastrados recentemente.
$recentes = consulta(
    $pdo,
    "SELECT
        t.id,
        t.nome,
        t.status,
        t.data_inicio,
        u.nome AS aluno
     FROM treinos t
     INNER JOIN usuarios u ON u.id = t.usuario_id
     WHERE t.instrutor_id = ?
     ORDER BY t.id DESC
     LIMIT 5",
    [$instrutorId]
)->fetchAll(PDO::FETCH_ASSOC);

$titulo = 'Dashboard';
$menu = 'Dashboard';

// CSS adicional carregado dentro do HEAD do painel.
$cssExtra = '../assets/css/admin/dashboard-instrutor.css';

require __DIR__ . '/../includes/painel.php';

?>

<section class="welcome-section">
    <div class="welcome-content">
        <span class="welcome-label">PAINEL DO INSTRUTOR</span>

        <h1>
            Olá, <?= escapar($instrutor['nome']) ?>!
        </h1>

        <p>
            Aqui você acompanha seus alunos e gerencia seus treinos.
        </p>

        <div class="welcome-contact">
            <?php if (!empty($instrutor['email'])): ?>
                <span>✉ <?= escapar($instrutor['email']) ?></span>
            <?php endif; ?>

            <?php if (!empty($instrutor['telefone'])): ?>
                <span>☎ <?= escapar($instrutor['telefone']) ?></span>
            <?php endif; ?>
        </div>
    </div>

    <div class="welcome-icon">N</div>
</section>

<section class="stats-grid">

    <article class="stat-card">
        <div class="stat-icon">♟</div>

        <div class="stat-info">
            <span class="stat-description">Alunos relacionados</span>
            <strong class="stat-number">
                <?= (int) ($resumo['alunos'] ?? 0) ?>
            </strong>
        </div>
    </article>

    <article class="stat-card">
        <div class="stat-icon">▤</div>

        <div class="stat-info">
            <span class="stat-description">Treinos cadastrados</span>
            <strong class="stat-number">
                <?= (int) ($resumo['treinos'] ?? 0) ?>
            </strong>
        </div>
    </article>

    <article class="stat-card">
        <div class="stat-icon">✓</div>

        <div class="stat-info">
            <span class="stat-description">Treinos ativos</span>
            <strong class="stat-number">
                <?= (int) ($resumo['ativos'] ?? 0) ?>
            </strong>
        </div>
    </article>

    <article class="stat-card">
        <div class="stat-icon">◷</div>

        <div class="stat-info">
            <span class="stat-description">Treinos em rascunho</span>
            <strong class="stat-number">
                <?= (int) ($resumo['rascunhos'] ?? 0) ?>
            </strong>
        </div>
    </article>

</section>

<section class="dashboard-actions">

    <a href="alunos.php" class="dashboard-action">
        <span class="action-icon">♟</span>

        <div>
            <strong>Meus alunos</strong>
            <small>Consulte os alunos vinculados a você.</small>
        </div>

        <span class="action-arrow">→</span>
    </a>

    <a href="treinos.php" class="dashboard-action">
        <span class="action-icon">▤</span>

        <div>
            <strong>Gerenciar treinos</strong>
            <small>Consulte e organize seus treinos.</small>
        </div>

        <span class="action-arrow">→</span>
    </a>

    <a href="editar_treino.php" class="dashboard-action">
        <span class="action-icon">＋</span>

        <div>
            <strong>Criar treino</strong>
            <small>Monte um novo treino para um aluno vinculado.</small>
        </div>

        <span class="action-arrow">→</span>
    </a>

</section>

<section class="dashboard-panel">

    <div class="panel-heading">
        <div>
            <span class="panel-eyebrow">ACOMPANHAMENTO</span>
            <h2>Treinos ativos</h2>
            <p>Treinos atualmente disponíveis para seus alunos.</p>
        </div>

        <a href="treinos.php" class="panel-link">
            Ver todos →
        </a>
    </div>

    <?php if (!$ativos): ?>

        <div class="empty-state">
            <span>▤</span>
            <strong>Nenhum treino ativo</strong>
            <p>Quando você tiver treinos ativos, eles aparecerão aqui.</p>
        </div>

    <?php else: ?>

        <div class="active-workouts">

            <?php foreach ($ativos as $ativo): ?>

                <a
                    href="treino.php?id=<?= (int) $ativo['id'] ?>"
                    class="workout-item"
                >
                    <div class="workout-icon">▤</div>

                    <div class="workout-info">
                        <strong><?= escapar($ativo['nome']) ?></strong>

                        <span>
                            Aluno: <?= escapar($ativo['aluno']) ?>
                        </span>
                    </div>

                    <span class="status-badge ativo">Ativo</span>

                    <span class="workout-arrow">→</span>
                </a>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</section>

<section class="dashboard-panel">

    <div class="panel-heading">
        <div>
            <span class="panel-eyebrow">ATIVIDADE</span>
            <h2>Treinos recentes</h2>
            <p>Últimos treinos cadastrados por você.</p>
        </div>

        <a href="treinos.php" class="panel-link">
            Ver todos →
        </a>
    </div>

    <?php if (!$recentes): ?>

        <div class="empty-state">
            <span>◷</span>
            <strong>Nenhum treino cadastrado</strong>
            <p>Seus novos treinos aparecerão nesta seção.</p>
        </div>

    <?php else: ?>

        <div class="recent-workouts">

            <?php foreach ($recentes as $recente): ?>

                <?php
                $statusTexto = [
                    'ativo' => 'Ativo',
                    'rascunho' => 'Rascunho',
                    'concluido' => 'Concluído',
                    'arquivado' => 'Arquivado'
                ];

                $statusClasse = [
                    'ativo' => 'ativo',
                    'rascunho' => 'rascunho',
                    'concluido' => 'concluido',
                    'arquivado' => 'arquivado'
                ];

                $status = $recente['status'];
                ?>

                <a
                    href="treino.php?id=<?= (int) $recente['id'] ?>"
                    class="recent-item"
                >
                    <div class="recent-main">
                        <strong><?= escapar($recente['nome']) ?></strong>

                        <span>
                            <?= escapar($recente['aluno']) ?>
                        </span>
                    </div>

                    <span class="recent-date">
                        <?= date('d/m/Y', strtotime($recente['data_inicio'])) ?>
                    </span>

                    <span class="status-badge <?= escapar($statusClasse[$status] ?? 'rascunho') ?>">
                        <?= escapar($statusTexto[$status] ?? $status) ?>
                    </span>
                </a>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</section>

<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
