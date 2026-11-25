<?php
if (!isset($tabela, $colunas, $titulo)) {
    http_response_code(404);
    exit;
}
$selecionar = implode(', ', array_keys($colunas));
$registros = consulta($pdo, $sqlLista ?? "SELECT id, $selecionar FROM $tabela ORDER BY id DESC")->fetchAll();
require __DIR__ . '/../includes/painel.php';
?>
<?php if (!empty($criar)): ?>
    <p><a class="instructor-create-button" href="criar.php">+ <?= escapar($criar) ?></a></p>
<?php endif; ?>
<div class="instructor-table-card">
    <div class="instructor-table-heading"><p><?= count($registros) ?> registro(s)</p></div>
    <div class="instructor-table-wrapper">
        <table class="instructor-table">
            <thead><tr>
                <?php foreach ($colunas as $rotulo): ?><th scope="col"><?= escapar($rotulo) ?></th><?php endforeach; ?>
                <th scope="col">Ações</th>
            </tr></thead>
            <tbody>
            <?php foreach ($registros as $registro): ?>
                <tr>
                    <?php foreach ($colunas as $campo => $rotulo): ?>
                        <td><?= escapar((string) ($registro[$campo] ?? '—')) ?></td>
                    <?php endforeach; ?>
                    <td><a class="form-cancel-button" href="<?= escapar($destino ?? 'editar.php') ?>?id=<?= (int) $registro['id'] ?>"><?= escapar($acao ?? 'Editar') ?></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/../includes/painel_fim.php'; ?>
