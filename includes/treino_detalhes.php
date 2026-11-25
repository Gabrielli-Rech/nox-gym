<h2><?= escapar($treino['nome']) ?></h2>
<p>Aluno <?= escapar($treino['aluno']) ?> · Instrutor <?= escapar($treino['instrutor']) ?></p>
<p><?= escapar($treino['data_inicio']) ?> a <?= escapar($treino['data_fim'] ?? 'sem data de término') ?> · <?= escapar(camposTreino()['status'][3][$treino['status']]) ?></p>
<p><?= escapar($treino['objetivo'] ?? '') ?></p>
<p><?= nl2br(escapar($treino['descricao'] ?? '')) ?></p>
<h3>Exercícios</h3>
<?php if (!$exercicios): ?><p>Este treino ainda não tem exercícios cadastrados.</p><?php endif; ?>
<ol>
<?php foreach ($exercicios as $exercicio): ?>
    <li value="<?= (int) $exercicio['ordem'] ?>">
        <strong><?= escapar($exercicio['nome']) ?></strong> · <?= escapar($exercicio['grupo_muscular']) ?>
        <p><?= (int) $exercicio['series'] ?> séries · <?= escapar($exercicio['repeticoes']) ?> repetições</p>
        <p>Carga <?= escapar($exercicio['carga_sugerida'] ?? '—') ?> · Descanso <?= escapar(isset($exercicio['descanso_segundos']) ? $exercicio['descanso_segundos'] . ' s' : '—') ?></p>
        <p><?= nl2br(escapar($exercicio['instrucoes'] ?? '')) ?></p>
        <p><?= nl2br(escapar($exercicio['observacoes'] ?? '')) ?></p>
        <?php if (caminhoImagem($exercicio['imagem'] ?? '')): ?>
            <img src="<?= escapar(($raizImagens ?? '') . $exercicio['imagem']) ?>" alt="<?= escapar($exercicio['nome']) ?>" width="180" loading="lazy">
        <?php endif; ?>
        <?php if (!empty($editarExercicios)): ?>
            <a class="form-cancel-button" href="exercicio.php?treino=<?= (int) $treino['id'] ?>&amp;id=<?= (int) $exercicio['id'] ?>">Editar ou remover</a>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
</ol>
