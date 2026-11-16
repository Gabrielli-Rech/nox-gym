<?php
$pageTitle = $pageTitle ?? 'Nox Gym | Alta performance';
$currentPage = basename($_SERVER['PHP_SELF'] ?? 'index.php');
$navItems = [
    'index.php' => 'Home',
    'planos.php' => 'Planos',
    'treinos.php' => 'Treinos',
    'loja.php' => 'Loja',
    'estrutura.php' => 'Estrutura',
    'calculadora.php' => 'IMC',
    'sobre.php' => 'Sobre',
    'contato.php' => 'Contato',
];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Nox Gym — treinamento de alta performance, estrutura premium e evolução constante.">
    <meta name="theme-color" content="#090D16">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700;800;900&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <header class="site-header">
        <div class="container nav-wrap">
            <a class="brand" href="index.php" aria-label="Nox Gym - início">
                <span class="brand-mark">N</span><span>NOX<span class="brand-accent">GYM</span></span>
            </a>
            <button class="menu-toggle" type="button" aria-label="Abrir menu" aria-expanded="false">☰</button>
            <nav class="main-nav" aria-label="Navegação principal">
                <?php foreach ($navItems as $href => $label): ?>
                    <a href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>" class="<?= $currentPage === $href ? 'active' : '' ?>">
                        <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <a class="nav-cta" href="login.php">Área do aluno <span aria-hidden="true">↗</span></a>
        </div>
    </header>
    <main>