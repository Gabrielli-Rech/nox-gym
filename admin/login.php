
<?php

require_once __DIR__ . '/../config/conexao.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    $sql = $pdo->prepare(
        "SELECT id, nome, email, senha, foto
         FROM administradores
         WHERE email = ?
         AND status = 'ativo'
         LIMIT 1"
    );

    $sql->execute([$email]);

    $admin = $sql->fetch();

    if ($admin && password_verify($senha, $admin['senha'])) {

        session_regenerate_id(true);

        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_nome'] = $admin['nome'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_foto'] = $admin['foto'];

        header('Location: dashboard.php');
        exit;

    } else {
        $erro = 'Credenciais inválidas.';
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Acesso Administrativo | Nox Gym</title>

    <link rel="stylesheet" href="../assets/css/admin/login.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>

    <main class="login-container">

        <section class="login-visual">

            <div class="visual-content">

                <div class="brand">
                    <span class="brand-icon">N</span>
                    <span>NOX<span class="brand-highlight">GYM</span></span>
                </div>

                <div class="visual-message">

                    <span class="eyebrow">NOX GYM MANAGEMENT SYSTEM</span>

                    <h1>
                        PERFORMANCE<br>
                        <span>BEYOND LIMITS.</span>
                    </h1>

                    <p>
                        Gestão inteligente, controle completo e
                        evolução constante. O centro de comando
                        da Nox Gym começa aqui.
                    </p>

                </div>

                <div class="visual-footer">
                    <span class="status-dot"></span>
                    ADMINISTRATIVE ENVIRONMENT
                </div>

            </div>

            <div class="visual-decoration decoration-one"></div>
            <div class="visual-decoration decoration-two"></div>

        </section>

        <section class="login-form-section">

            <div class="login-card">

                <div class="mobile-brand">
                    <span class="brand-icon">N</span>
                    <span>NOX<span class="brand-highlight">GYM</span></span>
                </div>

                <div class="login-heading">

                    <span class="eyebrow">RESTRICTED AREA / 01</span>

                    <h2>Acesso administrativo</h2>

                    <p>
                        Identifique-se para acessar o painel
                        de gerenciamento da academia.
                    </p>

                </div>

                <?php if ($erro): ?>
                    <div class="error-message">
                        <span>!</span>
                        <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="login-form">

                    <div class="input-group">

                        <label for="email">E-mail administrativo</label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="admin@noxgym.com"
                            autocomplete="username"
                            required
                        >

                    </div>

                    <div class="input-group">

                        <label for="senha">Senha de acesso</label>

                        <input
                            type="password"
                            id="senha"
                            name="senha"
                            placeholder="Digite sua senha"
                            autocomplete="current-password"
                            required
                        >

                    </div>

                    <button type="submit" class="login-button">
                        ACESSAR SISTEMA
                        <span>→</span>
                    </button>

                </form>

                <div class="login-bottom">
                    <span class="security-icon">◆</span>
                    Acesso protegido para administradores.
                </div>

                <a href="../index.php" class="back-link">
                    ← Voltar para o site
                </a>

            </div>

        </section>

    </main>

</body>
</html>
