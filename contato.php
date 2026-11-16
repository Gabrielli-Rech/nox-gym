<?php $pageTitle = 'Nox Gym | Contato';
require_once __DIR__ . '/includes/header.php'; ?>

<section class="page-hero">
    <div class="container"><span class="eyebrow">Estamos aqui para ajudar</span>
        <h1>Vamos conversar?</h1>
        <p>Envie sua dúvida ou solicite informações sobre planos, horários e estrutura.</p>
    </div>
</section>
<section class="section">
    <div class="container">
        <form class="form-card" action="#" method="post">
            <div class="form-grid">
                <div class="field"><label for="nome">Nome</label><input id="nome" name="nome" type="text" autocomplete="name" required></div>
                <div class="field"><label for="telefone">Telefone</label><input id="telefone" name="telefone" type="tel" autocomplete="tel" required></div>
                <div class="field full"><label for="email">E-mail</label><input id="email" name="email" type="email" autocomplete="email" required></div>
                <div class="field full"><label for="mensagem">Mensagem</label><textarea id="mensagem" name="mensagem" required></textarea></div>
                <div class="field full"><button class="btn" type="submit">Enviar mensagem ↗</button></div>
            </div>
            
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>