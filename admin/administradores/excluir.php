
<?php

require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../includes/autenticacao_admin.php';

exigirAdmin();

if (!function_exists('escapar')) {
    function escapar($valor): string {
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header('Location: index.php?erro=id_invalido');
    exit;
}

if (empty($_SESSION['csrf_admin'])) {
    $_SESSION['csrf_admin'] = bin2hex(random_bytes(32));
}

$erro = '';

$consulta = $pdo->prepare(
    "SELECT id, nome, email, status
     FROM administradores
     WHERE id = ?
     LIMIT 1"
);

$consulta->execute([$id]);
$administrador = $consulta->fetch();

if (!$administrador) {
    header('Location: index.php?erro=nao_encontrado');
    exit;
}

// Impede que o administrador exclua a própria conta.
if ((int) $administrador['id'] === (int) $_SESSION['admin_id']) {
    header('Location: index.php?erro=autoexclusao');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = $_POST['csrf_token'] ?? '';

    if (
        !is_string($token) ||
        !hash_equals($_SESSION['csrf_admin'], $token)
    ) {
        $erro = 'Solicitação inválida. Atualize a página e tente novamente.';

    } else {

        try {

            $pdo->beginTransaction();

            // Bloqueia o registro que será excluído.
            $consultaAdmin = $pdo->prepare(
                "SELECT id, nome, email, status
                 FROM administradores
                 WHERE id = ?
                 FOR UPDATE"
            );

            $consultaAdmin->execute([$id]);
            $adminExcluir = $consultaAdmin->fetch();

            if (!$adminExcluir) {
                throw new RuntimeException(
                    'Administrador não encontrado.'
                );
            }

            // Confere novamente se não é a própria conta.
            if ((int) $adminExcluir['id'] === (int) $_SESSION['admin_id']) {
                throw new RuntimeException(
                    'Você não pode excluir sua própria conta.'
                );
            }

            // Bloqueia e consulta os administradores ativos.
            $consultaAtivos = $pdo->query(
                "SELECT id
                 FROM administradores
                 WHERE status = 'ativo'
                 FOR UPDATE"
            );

            $administradoresAtivos = $consultaAtivos->fetchAll();
            $totalAtivos = count($administradoresAtivos);

            // Impede excluir o último administrador ativo.
            if (
                $adminExcluir['status'] === 'ativo' &&
                $totalAtivos <= 1
            ) {
                throw new RuntimeException(
                    'Não é possível excluir o último administrador ativo.'
                );
            }

            $nomeExcluido = $adminExcluir['nome'];
            $emailExcluido = $adminExcluir['email'];

            // Preserva os logs, removendo apenas a referência ao administrador.
            $desvincularLogs = $pdo->prepare(
                "UPDATE logs_admin
                 SET administrador_id = NULL
                 WHERE administrador_id = ?"
            );

            $desvincularLogs->execute([$id]);

            // Registra a exclusão no histórico.
            $registrarLog = $pdo->prepare(
                "INSERT INTO logs_admin
                    (administrador_id, acao, descricao)
                 VALUES
                    (NULL, ?, ?)"
            );

            $registrarLog->execute([
                'administrador_excluido',
                'O administrador ' . $nomeExcluido .
                ' (' . $emailExcluido . ') foi excluído por ' .
                ($_SESSION['admin_nome'] ?? 'Administrador') . '.'
            ]);

            // Exclui o administrador.
            $excluir = $pdo->prepare(
                "DELETE FROM administradores
                 WHERE id = ?"
            );

            $excluir->execute([$id]);

            $pdo->commit();

            // Atualiza o token após a operação.
            $_SESSION['csrf_admin'] = bin2hex(random_bytes(32));

            header('Location: index.php?sucesso=excluido');
            exit;

        } catch (RuntimeException $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $erro = $e->getMessage();

        } catch (PDOException $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            error_log($e->getMessage());

            $erro = 'Não foi possível excluir o administrador. Verifique as dependências do banco de dados e tente novamente.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Excluir Administrador | Nox Gym</title>

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
                <h2>Excluir administrador</h2>
            </div>

            <div>
                <span>
                    <?= escapar($_SESSION['admin_nome'] ?? 'Administrador') ?>
                </span>
            </div>
        </header>

        <section class="delete-page">

            <a href="index.php" class="management-back-button">
                ← Voltar para administradores
            </a>

            <div class="delete-card">

                <div class="delete-icon">!</div>

                <h1>Confirmar exclusão</h1>

                <p class="delete-description">
                    Você está prestes a excluir permanentemente este
                    administrador da Nox Gym.
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
                    essa ação não poderá ser desfeita. Os registros
                    anteriores de auditoria serão preservados, mas
                    deixarão de estar vinculados a este administrador.
                </div>

                <?php if ($erro !== ''): ?>
                    <div class="delete-error">
                        <?= escapar($erro) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="?id=<?= (int) $id ?>">

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= escapar($_SESSION['csrf_admin']) ?>"
                    >

                    <div class="delete-actions">

                        <a href="index.php" class="delete-cancel">
                            Cancelar
                        </a>

                        <button type="submit" class="delete-button">
                            Confirmar exclusão
                        </button>

                    </div>

                </form>

            </div>

        </section>

    </main>

</div>

</body>
</html>
