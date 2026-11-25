<?php
require_once __DIR__ . '/includes/formularios.php';
$peso = entrada($_GET, 'peso');
$altura = entrada($_GET, 'altura');
$resultado = '';
if (isset($_GET['peso'], $_GET['altura'])) {
    $pesoKg = filter_var($peso, FILTER_VALIDATE_FLOAT);
    $metros = filter_var($altura, FILTER_VALIDATE_FLOAT);
    if ($metros >= 50) {
        $metros /= 100;
    }
    if ($pesoKg < 1 || $pesoKg > 500 || $metros < 0.5 || $metros > 2.8) {
        $resultado = 'Informe peso e altura válidos.';
    } else {
        $imc = $pesoKg / ($metros ** 2);
        $classificacao = match (true) {
            $imc < 18.5 => 'Abaixo do peso',
            $imc < 25 => 'Peso adequado',
            $imc < 30 => 'Sobrepeso',
            default => 'Obesidade',
        };
        $resultado = 'Seu IMC é ' . number_format($imc, 1, ',', '.') . ' · ' . $classificacao;
    }
}
$pageTitle = 'Nox Gym | Calculadora de IMC';
require_once __DIR__ . '/includes/header.php'; ?>

<section class="page-hero">
    <div class="container"><span class="eyebrow">Conheça seu indicador</span>
        <h1>Calculadora<br>de IMC.</h1>
        <p>Informe peso e altura para obter uma estimativa do Índice de Massa Corporal.</p>
    </div>
</section>
<section class="section">
    <div class="container">
        <form class="form-card" method="get">
            <div class="form-grid">
                <div class="field"><label for="weight">Peso (kg)</label><input id="weight" name="peso" value="<?= escapar($peso) ?>" type="number" min="1" max="500" step="0.1" placeholder="Ex.: 72,5" required></div>
                <div class="field"><label for="height">Altura (cm ou m)</label><input id="height" name="altura" value="<?= escapar($altura) ?>" type="number" min="0.5" max="280" step="0.01" placeholder="Ex.: 1,75" required></div>
                <div class="field full"><button class="btn" type="submit">Calcular meu IMC ↗</button></div>
            </div>
            <?php if ($resultado): ?><div class="result-box" style="display:block" role="status"><?= escapar($resultado) ?></div><?php endif; ?>
            <p class="notice">O IMC é um indicador geral e não avalia composição corporal. Procure um profissional de saúde para interpretação individualizada.</p>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>