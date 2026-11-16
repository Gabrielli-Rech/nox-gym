<?php $pageTitle = 'Nox Gym | Acesso do aluno'; ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800;900&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>
    <a class="back-home" href="index.php">← Voltar ao site</a>
    <div class="auth-shell" id="auth-shell">
        <section class="auth-form login-form">
            <form action="#" method="post">
                <span class="mini-brand">NOX<span>GYM</span></span>
                <p class="eyebrow">Sua jornada continua</p>
                <h1>Bem-vindo<br>de volta.</h1>
                <label>Usuário ou e-mail<input type="text" name="usuario" placeholder="Digite seu usuário" autocomplete="username" required></label>
                <label>Senha<input type="password" name="senha" placeholder="Digite sua senha" autocomplete="current-password" required></label>
                <button class="auth-button" type="submit">Entrar na conta ↗</button>
                <small>Autenticação será conectada ao banco de dados na etapa 2.</small>
            </form>
        </section>
        <section class="auth-form register-form">
            <form action="#" method="post">
                <span class="mini-brand">NOX<span>GYM</span></span>
                <p class="eyebrow">Faça parte do movimento</p>
                <h1>Crie sua<br>conta.</h1>
                <label>Nome completo<input type="text" name="nome" placeholder="Seu nome" autocomplete="name" required></label>
                <label>E-mail<input type="email" name="email" placeholder="voce@email.com" autocomplete="email" required></label>
                <label>Senha<input type="password" name="senha" placeholder="Crie uma senha" autocomplete="new-password" required></label>
                <button class="auth-button cyan" type="submit">Criar cadastro ↗</button>
                <small>Cadastro demonstrativo de interface nesta etapa.</small>
            </form>
        </section>
        <div class="slide-panel">
            <div class="slide-content slide-left"><span class="panel-mark">N</span>
                <p class="eyebrow">Disciplina. Foco. Evolução.</p>
                <h2>Seu próximo<br>nível espera.</h2>
                <p>Ainda não faz parte da Nox?</p><button class="switch-button register-switch" type="button">Criar minha conta</button>
            </div>
            <div class="slide-content slide-right"><span class="panel-mark">N</span>
                <p class="eyebrow">Bom ter você de volta.</p>
                <h2>Continue<br>em movimento.</h2>
                <p>Já possui cadastro?</p><button class="switch-button login-switch" type="button">Fazer login</button>
            </div>
        </div>
    </div>
    <script src="assets/js/login.js" defer></script>
</body>

</html>