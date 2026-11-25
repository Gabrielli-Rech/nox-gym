<?php $pageTitle = 'Nox Gym | Alta performance';
require_once __DIR__ . '/includes/conteudos_publicos.php';
require_once __DIR__ . '/includes/imagem_publica.php';
require_once __DIR__ . '/includes/header.php'; ?>

<section class="hero" style="--hero-image: url('<?= imagemConteudo('home_banner', is_file(__DIR__ . '/assets/img/Banner.png') ? 'assets/img/Banner.png' : 'assets/img/academia.jpg') ?>')">
    <div class="container hero-content">
        <span class="eyebrow"><?= conteudo('home_banner', 'subtitulo', 'Treinamento de alta performance') ?></span>
        <?php
        $tituloBanner = conteudo('home_banner', 'titulo');
        $linhasBanner = $tituloBanner !== '' ? preg_split('/\s+(?=\S+$)/u', $tituloBanner, 2) : [];
        ?>
        <h1><?php if (count($linhasBanner) === 2): ?>
            <?= $linhasBanner[0] ?><br><span><?= $linhasBanner[1] ?></span>
        <?php elseif ($linhasBanner): ?>
            <?= $linhasBanner[0] ?>
        <?php else: ?>
            Supere seus<br><span>limites.</span>
        <?php endif; ?></h1>
        <p><?= conteudo('home_banner', 'texto', 'Uma estrutura preparada para quem leva evolução a sério. Treine com propósito, constância e acompanhamento.') ?></p>
        <div class="hero-actions">
            <a class="btn" href="planos.php">Conheça os planos ↗</a>
            <a class="btn btn-outline" href="estrutura.php">Explore a estrutura</a>
        </div>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="section-head">
            <div><span class="section-kicker"><?= conteudo('home_sobre', 'subtitulo', 'Performance é construção') ?></span>
                <h2><?= conteudo('home_sobre', 'titulo', 'Seu próximo nível<br>começa aqui.') ?></h2>
            </div>
            <p><?= conteudo('home_sobre', 'texto', 'Treinamento inteligente, equipamentos de qualidade e um ambiente pensado para transformar esforço em resultado.') ?></p>
        </div>
        <?php if ($imagem = imagemConteudo('home_sobre', '')): ?>
            <img class="imagem-conteudo" src="<?= $imagem ?>" alt="Nox Gym">
        <?php endif; ?>
        <div class="stats">
            <div class="stat"><strong>24/7</strong><span>Compromisso com sua evolução</span></div>
            <div class="stat"><strong>100%</strong><span>Foco em performance</span></div>
            <div class="stat"><strong>08+</strong><span>Áreas de treinamento</span></div>
            <div class="stat"><strong>01</strong><span>Objetivo: ir além</span></div>
        </div>
    </div>
</section>
<section class="section" style="padding-top:0">
    <div class="container">
        <div class="section-head">
            <div><span class="section-kicker"><?= conteudo('home_diferenciais', 'subtitulo', 'Conheça a Nox') ?></span>
                <h2><?= conteudo('home_diferenciais', 'titulo', 'Treine do seu jeito.') ?></h2>
            <?php if ($texto = conteudo('home_diferenciais', 'texto')): ?><p><?= $texto ?></p><?php endif; ?>
            </div><a class="btn btn-outline" href="treinos.php">Ver modalidades ↗</a>
        </div>
        <div class="grid">
            <article class="card">
                <?php if ($imagem = imagemConteudo('home_diferenciais', '')): ?>
                    <img src="<?= $imagem ?>" alt="Modalidades da Nox Gym">
                <?php else: imagemPublica('assets/img/musc.jpg', 'Treinamento de musculação', 'assets/img/academia.jpg'); endif; ?>
                <div class="card-body"><span class="card-tag">Força</span>
                    <h3>Musculação</h3>
                    <p>Treinos estruturados para desenvolver força, resistência e consistência.</p>
                </div>
            </article>
            <article class="card"><?php imagemPublica('assets/img/Cardio.jpg', 'Área de cardio', 'assets/img/academ.jpg'); ?>
                <div class="card-body"><span class="card-tag">Condicionamento</span>
                    <h3>Cardio</h3>
                    <p>Melhore seu condicionamento e aumente sua capacidade física.</p>
                </div>
            </article>
            <article class="card"><?php imagemPublica('assets/img/Alimentacao.jpg', 'Alimentação saudável', 'assets/img/academ.jpg'); ?>
                <div class="card-body"><span class="card-tag">Hábitos</span>
                    <h3>Vida ativa</h3>
                    <p>Performance também é construída fora da academia, todos os dias.</p>
                </div>
            </article>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>