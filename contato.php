<?php
require_once __DIR__ . '/includes/formularios.php';
sessaoNox();
tratarErrosBanco();
$erro = '';
$sucesso = !empty($_SESSION['contato_enviado']);
unset($_SESSION['contato_enviado']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificarCsrf();
    try {
        $dados = lerFormulario([
            'nome' => ['Nome', 'text', true, 120],
            'telefone' => ['Telefone', 'tel', true, 20],
            'email' => ['E-mail', 'email', true, 150],
            'mensagem' => ['Mensagem', 'textarea', true, 16000],
        ]);
        require_once __DIR__ . '/config/conexao.php';
        gravarRegistro($pdo, 'mensagens_contato', $dados);
        $_SESSION['contato_enviado'] = true;
        redirecionar('contato.php');
    } catch (InvalidArgumentException | PDOException $e) {
        $erro = $e instanceof InvalidArgumentException ? $e->getMessage() : 'Não foi possível enviar sua mensagem.';
    }
}
$pageTitle = 'Nox Gym | Contato';
require_once __DIR__ . '/includes/header.php'; ?>

<section class="page-hero">
    <div class="container"><span class="eyebrow">Estamos aqui para ajudar</span>
        <h1>Vamos conversar?</h1>
        <p>Envie sua dúvida ou solicite informações sobre planos, horários e estrutura.</p>
    </div>
</section>
<section class="section">
    <div class="container">
        <form class="form-card" method="post">
            <?php campoCsrf(); ?>
            <?php if ($sucesso): ?><p role="status">Mensagem enviada com sucesso.</p><?php endif; ?>
            <?php if ($erro): ?><p role="alert"><?= escapar($erro) ?></p><?php endif; ?>
            <div class="form-grid">
                <div class="field"><label for="nome">Nome</label><input id="nome" name="nome" maxlength="120" value="<?= escapar(entrada($_POST, 'nome')) ?>" type="text" autocomplete="name" required></div>
                <div class="field"><label for="telefone">Telefone</label><input id="telefone" name="telefone" maxlength="20" value="<?= escapar(entrada($_POST, 'telefone')) ?>" type="tel" autocomplete="tel" required></div>
                <div class="field full"><label for="email">E-mail</label><input id="email" name="email" maxlength="150" value="<?= escapar(entrada($_POST, 'email')) ?>" type="email" autocomplete="email" required></div>
                <div class="field full"><label for="mensagem">Mensagem</label><textarea id="mensagem" name="mensagem" maxlength="16000" required><?= escapar(entrada($_POST, 'mensagem')) ?></textarea></div>
                <div class="field full"><button class="btn" type="submit">Enviar mensagem ↗</button></div>
            </div>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>