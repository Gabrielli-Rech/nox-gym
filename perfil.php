
<?php

require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/includes/autenticacao.php';

exigirLogin();

$sql = $pdo->prepare(
    "SELECT id, nome, email, telefone, foto, criado_em
     FROM usuarios
     WHERE id = ?
     AND status = 'ativo'
     LIMIT 1"
);

$sql->execute([$_SESSION['usuario_id']]);

$usuario = $sql->fetch();

if (!$usuario) {
    $_SESSION = [];
    session_destroy();

    header('Location: login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Meu Perfil | Nox Gym</title>

    <link rel="stylesheet" href="assets/css/perfil.css">
</head>

<body>

<header class="topbar">
    <a href="index.php" class="logo">NOX<span>GYM</span></a>

    <nav>
        <a href="index.php">Início</a>
        <a href="planos.php">Planos</a>
        <a href="treinos.php">Treinos</a>
        <a href="logout.php" class="sair">Sair</a>
    </nav>
</header>

<main class="perfil-container">

    <section class="boas-vindas">
        <div>
            <span class="tag">ÁREA DO ALUNO</span>

            <h1>Olá, <?= htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8') ?>!</h1>

            <p>Bem-vindo à sua área exclusiva da Nox Gym.</p>
        </div>
    </section>

    <section class="perfil-card">

        <div class="perfil-avatar">
            <?php if (!empty($usuario['foto'])): ?>
                <img
                    src="<?= htmlspecialchars($usuario['foto'], ENT_QUOTES, 'UTF-8') ?>"
                    alt="Foto do aluno"
                >
            <?php else: ?>
                <span>
                    <?= strtoupper(substr($usuario['nome'], 0, 1)) ?>
                </span>
            <?php endif; ?>
        </div>

        <div class="dados">
            <h2><?= htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8') ?></h2>

            <p>
                <strong>E-mail:</strong>
                <?= htmlspecialchars($usuario['email'], ENT_QUOTES, 'UTF-8') ?>
            </p>

            <p>
                <strong>Telefone:</strong>
                <?= htmlspecialchars($usuario['telefone'] ?: 'Não informado', ENT_QUOTES, 'UTF-8') ?>
            </p>

            <p>
                <strong>Membro desde:</strong>
                <?= date('d/m/Y', strtotime($usuario['criado_em'])) ?>
            </p>
        </div>

    </section>

    <section class="atalhos">

        <a href="planos.php" class="atalho">
            <span class="icone">✦</span>
            <h3>Planos</h3>
            <p>Conheça nossos planos.</p>
        </a>

        <a href="treinos.php" class="atalho">
            <span class="icone">✦</span>
            <h3>Treinos</h3>
            <p>Explore opções de treinamento.</p>
        </a>

        <a href="calculadora.php" class="atalho">
            <span class="icone">✦</span>
            <h3>Calculadora</h3>
            <p>Acompanhe seus indicadores.</p>
        </a>

        <a href="contato.php" class="atalho">
            <span class="icone">✦</span>
            <h3>Suporte</h3>
            <p>Entre em contato conosco.</p>
        </a>

    </section>

</main>

<footer>
    <p>© <?= date('Y') ?> NOX GYM — Supere seus limites.</p>
</footer>

</body>
</html>
