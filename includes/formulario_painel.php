<div class="admin-form-card">
    <?php if (!empty($contexto)): ?><p><?= escapar($contexto) ?></p><?php endif; ?>
    <?php if (!empty($erro)): ?>
        <p class="form-error-message" role="alert"><?= escapar($erro) ?></p>
    <?php endif; ?>
    <form class="admin-create-form" method="post" enctype="multipart/form-data">
        <?php campoCsrf(); exibirCampos($campos, $registro); ?>
        <div class="form-actions">
            <a class="form-cancel-button" href="<?= escapar($voltar ?? 'index.php') ?>">Voltar</a>
            <button class="form-submit-button" type="submit"><?= escapar($botao ?? 'Salvar') ?></button>
        </div>
    </form>
</div>
