<?php $pageTitle = 'Nox Gym | Calculadora de IMC';
require_once __DIR__ . '/includes/header.php'; ?>

<section class="page-hero">
    <div class="container"><span class="eyebrow">Conheça seu indicador</span>
        <h1>Calculadora<br>de IMC.</h1>
        <p>Informe peso e altura para obter uma estimativa do Índice de Massa Corporal.</p>
    </div>
</section>
<section class="section">
    <div class="container">
        <form class="form-card" id="bmi-form">
            <div class="form-grid">
                <div class="field"><label for="weight">Peso (kg)</label><input id="weight" name="peso" type="number" min="1" max="500" step="0.1" placeholder="Ex.: 72,5" required></div>
                <div class="field"><label for="height">Altura (cm ou m)</label><input id="height" name="altura" type="number" min="0.5" max="2.8" step="0.01" placeholder="Ex.: 1,75" required></div>
                <div class="field full"><button class="btn" type="submit">Calcular meu IMC ↗</button></div>
            </div>
            <div class="result-box" id="bmi-result" role="status" aria-live="polite"></div>
            <p class="notice">O IMC é um indicador geral e não avalia composição corporal. Procure um profissional de saúde para interpretação individualizada.</p>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>