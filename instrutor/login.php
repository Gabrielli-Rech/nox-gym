<?php

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../includes/formularios.php';

sessaoNox();

if (isset($_SESSION['instrutor_id'])) {
    redirecionar('dashboard.php');
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = preg_replace('/\D/', '', entrada($_POST, 'cpf'));
    $senha = entrada($_POST, 'senha');

    if (!preg_match('/^\d{11}$/', $cpf) || $senha === '') {
        $erro = 'Informe um CPF e uma senha válidos.';
    } else {
        $stmt = consulta(
            $pdo,
            "SELECT id, nome, cpf, senha, status
            FROM instrutores
            WHERE cpf = ?
            LIMIT 1",
            [$cpf]
        );

        $instrutor = $stmt->fetch(PDO::FETCH_ASSOC);

        if (
            $instrutor &&
            $instrutor['status'] === 'ativo' &&
            password_verify($senha, $instrutor['senha'])
        ) {
            session_regenerate_id(true);

            $_SESSION['instrutor_id'] = $instrutor['id'];
            $_SESSION['instrutor_nome'] = $instrutor['nome'];
            $_SESSION['instrutor_cpf'] = $instrutor['cpf'];

            consulta(
                $pdo,
                "UPDATE instrutores SET ultimo_acesso = NOW() WHERE id = ?",
                [$instrutor['id']]
            );

            redirecionar('dashboard.php');
        }

        $erro = 'CPF ou senha incorretos, ou conta inativa.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Instrutor | Nox Gym</title>

    <link rel="stylesheet" href="../assets/css/instrutor-login.css">
</head>

<body>

<main class="login-container">
    <div class="login-card">

        <h1>NOX <span>GYM</span></h1>
        <p class="subtitulo">Área do instrutor</p>

        <?php if ($erro !== ''): ?>
            <div class="mensagem-erro">
                <?= escapar($erro) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="campo">
                <label for="cpf">CPF</label>
                <input
                    type="text"
                    id="cpf"
                    name="cpf"
                    maxlength="14"
                    placeholder="000.000.000-00"
                    required
                >
            </div>

            <div class="campo">
                <label for="senha">Senha</label>
                <input
                    type="password"
                    id="senha"
                    name="senha"
                    placeholder="Digite sua senha"
                    required
                >
            </div>

            <button type="submit">Entrar</button>

        </form>

        <a href="../index.php" class="voltar">Voltar ao site</a>

    </div>
</main>

</body>
</html>

