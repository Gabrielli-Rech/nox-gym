
<?php

require_once __DIR__ . '/config/conexao.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$erro = '';
$sucesso = '';

if (isset($_GET['cadastro']) && $_GET['cadastro'] === 'sucesso') {
    $sucesso = 'Cadastro realizado! Faça login para continuar.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    $sql = $pdo->prepare(
        "SELECT id, nome, email, senha
         FROM usuarios
         WHERE email = ?
         AND status = 'ativo'
         LIMIT 1"
    );

    $sql->execute([$email]);
    $usuario = $sql->fetch();

    if ($usuario && password_verify($senha, $usuario['senha'])) {

        session_regenerate_id(true);

        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];

        header('Location: perfil.php');
        exit;

    } else {
        $erro = 'E-mail ou senha incorretos.';
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Nox Gym</title>

    <link rel="stylesheet" href="assets/css/login.css">
    <script src="assets/js/login.js" defer></script>
</head>

<body>

<div class="container" id="container">

    <!-- CADASTRO -->
    <div class="form-container sign-up-container">

        <form action="cadastro.php" method="POST">

            <h1>Criar conta</h1>

            <p>Comece sua evolução com a Nox Gym.</p>

            <input
                type="text"
                name="nome"
                placeholder="Nome completo"
                maxlength="120"
                required
            >

            <input
                type="email"
                name="email"
                placeholder="E-mail"
                maxlength="150"
                required
            >

            <input
                type="tel"
                name="telefone"
                placeholder="Telefone"
                maxlength="20"
            >

            <input
                type="password"
                name="senha"
                placeholder="Senha (mínimo 8 caracteres)"
                minlength="8"
                required
            >

            <button type="submit">CADASTRAR</button>

        </form>

    </div>

    <!-- LOGIN -->
    <div class="form-container sign-in-container">

        <form action="login.php" method="POST">

            <h1>Bem-vindo de volta</h1>

            <p>Acesse sua conta e continue sua jornada.</p>

            <?php if ($erro): ?>
                <div class="mensagem erro">
                    <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if ($sucesso): ?>
                <div class="mensagem sucesso">
                    <?= htmlspecialchars($sucesso, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <input
                type="email"
                name="email"
                placeholder="E-mail"
                required
            >

            <input
                type="password"
                name="senha"
                placeholder="Senha"
                required
            >

            <button type="submit">ENTRAR</button>

        </form>

    </div>

    <!-- PAINEL ANIMADO -->
    <div class="overlay-container">

        <div class="overlay">

            <div class="overlay-panel overlay-left">

                <h1>Você já faz parte?</h1>

                <p>
                    Entre na sua conta e continue superando seus limites.
                </p>

                <button class="ghost" id="signIn" type="button">
                    FAZER LOGIN
                </button>

            </div>

            <div class="overlay-panel overlay-right">

                <h1>Supere seus limites!</h1>

                <p>
                    Crie sua conta e comece sua transformação com a Nox Gym.
                </p>

                <button class="ghost" id="signUp" type="button">
                    CRIAR CONTA
                </button>

            </div>

        </div>

    </div>

</div>

</body>
</html>
