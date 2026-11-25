<?php
require_once __DIR__ . '/includes/autenticacao.php';
require_once __DIR__ . '/includes/assinaturas_dados.php';
$usuario = exigirLogin();
$assinatura = assinaturasAluno($pdo, (int) $usuario['id'])[0] ?? null;
$treinoAtual = consulta($pdo, "SELECT id, nome FROM treinos
    WHERE usuario_id = ? AND status = 'ativo'
    ORDER BY (data_inicio <= CURRENT_DATE AND (data_fim IS NULL OR data_fim >= CURRENT_DATE)) DESC, data_inicio DESC, id DESC LIMIT 1",
    [$usuario['id']])->fetch();
$titulo = 'Meu Perfil';
require __DIR__ . '/includes/aluno_cabecalho.php';
?>
    <section class="boas-vindas">
        <div>
            <span class="tag">ÁREA DO ALUNO</span>

            <h1>Olá, <?= htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8') ?>!</h1>

            <p>Bem-vindo à sua área exclusiva da Nox Gym.</p>
        </div>
    </section>

    <section class="perfil-card">

        <div class="perfil-avatar">
            <?php if (caminhoImagem($usuario['foto'] ?? '')): ?>
                <img
                    src="<?= htmlspecialchars($usuario['foto'], ENT_QUOTES, 'UTF-8') ?>"
                    alt="Foto do aluno"
                >
            <?php else: ?>
                <span>
                    <?= escapar(mb_strtoupper(mb_substr($usuario['nome'], 0, 1))) ?>
                </span>
            <?php endif; ?>
        </div>

        <div class="dados">
            <h2><?= htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8') ?></h2>

            <p>
                <strong>E-mail:</strong>
                <?= htmlspecialchars($usuario['email'], ENT_QUOTES, 'UTF-8') ?>
            </p>

            <p>
                <strong>Telefone:</strong>
                <?= htmlspecialchars($usuario['telefone'] ?: 'Não informado', ENT_QUOTES, 'UTF-8') ?>
            </p>

            <p>
                <strong>Nascimento:</strong>
                <?= escapar($usuario['data_nascimento'] ?? 'Não informado') ?>
            </p>
            <p>
                <strong>Membro desde:</strong>
                <?= date('d/m/Y', strtotime($usuario['criado_em'])) ?>
            </p>
        </div>

    </section>

    <section class="atalhos">

        <a href="minha_assinatura.php" class="atalho">
            <span class="icone">✦</span>
            <h3>Minha assinatura</h3>
            <?php if ($assinatura): ?>
                <p><?= escapar($assinatura['nome']) ?> · <?= escapar($assinatura['situacao']) ?> · Até <?= escapar($assinatura['data_fim']) ?></p>
            <?php else: ?>
                <p>Nenhuma assinatura cadastrada.</p>
            <?php endif; ?>
        </a>

        <a href="meus_treinos.php<?= $treinoAtual ? '?id=' . (int) $treinoAtual['id'] : '' ?>" class="atalho">
            <span class="icone">✦</span>
            <h3>Treinos</h3>
            <p><?= escapar($treinoAtual['nome'] ?? 'Consulte seus treinos personalizados.') ?></p>
        </a>

        <a href="calculadora.php" class="atalho">
            <span class="icone">✦</span>
            <h3>Calculadora</h3>
            <p>Acompanhe seus indicadores.</p>
        </a>

        <a href="contato.php" class="atalho">
            <span class="icone">✦</span>
            <h3>Suporte</h3>
            <p>Entre em contato conosco.</p>
        </a>

    </section>

<?php require __DIR__ . '/includes/aluno_rodape.php'; ?>
