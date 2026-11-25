<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= escapar($titulo ?? 'Área do aluno') ?> | Nox Gym</title>
    <link rel="stylesheet" href="assets/css/perfil.css">
</head>
<body>
<header class="topbar">
    <a href="index.php" class="logo">NOX<span>GYM</span></a>
    <nav>
        <a href="perfil.php">Minha área</a>
        <a href="editar_perfil.php">Perfil</a>
        <a href="minha_assinatura.php">Assinatura</a>
        <a href="meus_treinos.php">Treinos</a>
        <form method="post" action="logout.php">
            <?php campoCsrf(); ?>
            <button type="submit" class="sair">Sair</button>
        </form>
    </nav>
</header>
<main class="perfil-container">
