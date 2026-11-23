
<?php

require_once __DIR__ . '/config/conexao.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $telefone = trim($_POST['telefone'] ?? '');

    if (
        $nome === '' ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        strlen($senha) < 8
    ) {
        $erro = 'Preencha os dados corretamente. A senha deve ter pelo menos 8 caracteres.';
    } else {

        $consulta = $pdo->prepare(
            'SELECT id FROM usuarios WHERE email = ?'
        );

        $consulta->execute([$email]);

        if ($consulta->fetch()) {
            $erro = 'Este e-mail já está cadastrado.';
        } else {

            $hash = password_hash(
                $senha,
                PASSWORD_DEFAULT
            );

            $sql = $pdo->prepare(
                'INSERT INTO usuarios
                (nome, email, senha, telefone)
                VALUES (?, ?, ?, ?)'
            );

            $sql->execute([
                $nome,
                $email,
                $hash,
                $telefone ?: null
            ]);

            header('Location: login.php?cadastro=sucesso');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro | Nox Gym</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>

<h1>Criar conta</h1>

<?php if ($erro): ?>
    <p><?= htmlspecialchars($erro) ?></p>
<?php endif; ?>

<form method="POST">

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

    <button type="submit">Cadastrar</button>

</form>

</body>
</html>
