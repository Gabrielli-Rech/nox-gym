<?php $pageTitle = 'Nox Gym | Estrutura';
require_once __DIR__ . '/includes/conteudos_publicos.php';
require_once __DIR__ . '/includes/imagem_publica.php';
require_once __DIR__ . '/includes/header.php'; ?>

<section class="page-hero">
    <div class="container"><span class="eyebrow"><?= conteudo('estrutura_apresentacao', 'subtitulo', 'Ambiente de alta performance') ?></span>
        <h1><?= conteudo('estrutura_apresentacao', 'titulo', 'Estrutura que<br>acompanha você.') ?></h1>
        <p><?= conteudo('estrutura_apresentacao', 'texto', 'Espaços planejados para oferecer conforto, segurança e variedade durante sua jornada.') ?></p>
    </div>
</section>
<section class="section">
    <div class="container grid">
        <article class="card"><img src="<?= imagemConteudo('estrutura_apresentacao', 'assets/img/academia.jpg') ?>" alt="Área de musculação">
            <div class="card-body">
                <h3>Área de musculação</h3>
                <p>Espaço para exercícios de força e desenvolvimento muscular.</p>
            </div>
        </article>
        <article class="card"><?php imagemPublica('assets/img/Cardio.jpg', 'Área de cardio', 'assets/img/academ.jpg'); ?>
            <div class="card-body">
                <h3>Área de cardio</h3>
                <p>Equipamentos para condicionamento e resistência.</p>
            </div>
        </article>
        <article class="card"><?php imagemPublica('assets/img/vestiario.jpg', 'Vestiário', 'assets/img/academ.jpg'); ?>
            <div class="card-body">
                <h3>Vestiários</h3>
                <p>Praticidade e conforto para antes e depois do treino.</p>
            </div>
        </article>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>