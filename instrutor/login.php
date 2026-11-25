<?php
require_once __DIR__ . '/../includes/formularios.php';
sessaoNox();
tratarErrosBanco();
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificarCsrf();
    $cpf = preg_replace('/[.\-]/', '', trim(entrada($_POST, 'cpf')));
    $senha = entrada($_POST, 'senha');
    if (cpfValido($cpf) && $senha !== '') {
        require_once __DIR__ . '/../config/conexao.php';
        $conta = consulta($pdo, "SELECT id, senha FROM instrutores WHERE cpf = ? AND status = 'ativo'", [$cpf])->fetch();
        if ($conta && password_verify($senha, $conta['senha'])) {
            consulta($pdo, "UPDATE instrutores SET ultimo_acesso = NOW() WHERE id = ? AND status = 'ativo'", [$conta['id']]);
            session_regenerate_id(true);
            $_SESSION = ['instrutor_id' => $conta['id']];
            redirecionar('index.php');
        }
    }
    $erro = 'CPF ou senha incorretos, ou acesso indisponível.';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instrutor | Nox Gym</title>
    <link rel="stylesheet" href="../assets/css/admin/admin.css">
</head>
<body>
<main class="dashboard-content">
    <a href="../index.php" class="form-cancel-button">Voltar ao site</a>
    <div class="admin-form-card">
        <div class="admin-form-heading"><div class="form-heading-icon">N</div><h1>Área do instrutor</h1></div>
        <?php if ($erro): ?><p class="form-error-message" role="alert"><?= escapar($erro) ?></p><?php endif; ?>
        <form class="admin-create-form" method="post">
            <?php campoCsrf(); ?>
            <div class="form-field"><label for="cpf">CPF</label><input id="cpf" name="cpf" inputmode="numeric" maxlength="14" autocomplete="username" required></div>
            <div class="form-field"><label for="senha">Senha</label><input id="senha" name="senha" type="password" autocomplete="current-password" required></div>
            <div class="form-actions"><button class="form-submit-button">Entrar</button></div>
        </form>
    </div>
</main>
</body>
</html>
