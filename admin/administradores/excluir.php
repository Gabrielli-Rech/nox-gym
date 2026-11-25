<?php
require_once __DIR__ . '/../inicio.php';
$id = idConsulta();
$administrador = exigirRegistro(consulta($pdo, 'SELECT nome, email, status FROM administradores WHERE id = ?', [$id])->fetch());
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificarCsrf();
    if ($id === (int) $_SESSION['admin_id']) {
        $erro = 'Você não pode desativar a conta com a qual está conectado.';
    } else {
        $pdo->beginTransaction();
        $ativos = consulta($pdo, "SELECT id FROM administradores WHERE status = 'ativo' ORDER BY id FOR UPDATE")->fetchAll(PDO::FETCH_COLUMN);
        if (in_array($id, $ativos, true) && count($ativos) === 1) {
            $pdo->rollBack();
            $erro = 'Mantenha pelo menos um administrador ativo.';
        } else {
            gravarRegistro($pdo, 'administradores', ['status' => 'inativo'], $id);
            logAdmin($pdo, 'administrador_desativado', $id);
            $pdo->commit();
            $_SESSION['aviso_nox'] = 'Administrador desativado.';
            redirecionar('index.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Desativar Administrador | Nox Gym</title>

    <link rel="stylesheet" href="../../assets/css/admin/admin.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../assets/css/admin/admin.css">

</head>

<body>

<div class="admin-layout">

    <aside class="admin-sidebar">

        <div class="sidebar-logo">
            <h2>NOX<span>GYM</span></h2>
        </div>

        <nav class="sidebar-nav">
            <a href="../dashboard.php">Dashboard</a>
            <a href="index.php" class="active">Administradores</a>
            <a href="../instrutores/index.php">Instrutores</a>
            <a href="../mensagens/index.php">Mensagens</a>
            <a href="../conteudos/index.php">Conteúdos</a>
            <a href="../../index.php" target="_blank">Visualizar site</a>
        </nav>

    </aside>

    <main class="admin-main">

        <header class="admin-topbar">
            <div>
                <h2>Desativar administrador</h2>
            </div>

            <div>
                <span>
                    <?= escapar($_SESSION['admin_nome'] ?? 'Administrador') ?>
                </span>
            </div>
        </header>
        <?php require __DIR__ . '/../../includes/menu_painel_mobile.php'; ?>

        <section class="delete-page">

            <a href="index.php" class="management-back-button">
                ← Voltar para administradores
            </a>

            <div class="delete-card">

                <div class="delete-icon">!</div>

                <h1>Confirmar desativação</h1>

                <p class="delete-description">
                    Você está prestes a desativar o acesso deste administrador.
                </p>

                <div class="delete-user">

                    <strong>
                        <?= escapar($administrador['nome']) ?>
                    </strong>

                    <span>
                        <?= escapar($administrador['email']) ?>
                    </span>

                    <br><br>

                    <span>
                        Status:
                        <?= escapar(ucfirst($administrador['status'])) ?>
                    </span>

                </div>

                <div class="delete-warning">
                    <strong>Atenção:</strong>
                    A conta perderá acesso ao painel. Seus vínculos e registros de auditoria serão preservados.
                </div>

                <?php if ($erro !== ''): ?>
                    <div class="delete-error">
                        <?= escapar($erro) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="?id=<?= (int) $id ?>">

                    <?php campoCsrf(); ?>

                    <div class="delete-actions">

                        <a href="index.php" class="delete-cancel">
                            Cancelar
                        </a>

                        <button type="submit" class="delete-button">
                            Confirmar desativação
                        </button>

                    </div>

                </form>

            </div>

        </section>

    </main>

</div>

</body>
</html>
