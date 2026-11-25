<?php
require_once __DIR__ . '/includes/autenticacao.php';
$usuario = exigirLogin();
$campos = [
    'nome' => ['Nome', 'text', true, 120],
    'email' => ['E-mail', 'email', true, 150],
    'telefone' => ['Telefone', 'tel', false, 20],
    'data_nascimento' => ['Data de nascimento', 'date', false, 0],
    'foto' => ['Foto (caminho em assets/img)', 'imagem', false, 255],
];
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificarCsrf();
    $novasImagens = [];
    try {
        $dados = lerFormulario($campos);
        if ($dados['data_nascimento'] !== null && $dados['data_nascimento'] > date('Y-m-d')) {
            throw new InvalidArgumentException('Confira a data de nascimento.');
        }
        $dados = receberImagens($campos, $dados, $novasImagens);
        consulta($pdo, "UPDATE usuarios SET nome = ?, email = ?, telefone = ?, data_nascimento = ?, foto = ?
            WHERE id = ? AND status = 'ativo'", [...array_values($dados), $usuario['id']]);
        $_SESSION['usuario_nome'] = $dados['nome'];
        $_SESSION['usuario_email'] = $dados['email'];
        $_SESSION['perfil_salvo'] = true;
        redirecionar('editar_perfil.php');
    } catch (InvalidArgumentException | PDOException $e) {
        limparImagensNaoSalvas($novasImagens);
        $erro = $e instanceof InvalidArgumentException ? $e->getMessage() :
            (($e->errorInfo[1] ?? null) === 1062 ? 'Este e-mail já está cadastrado.' : 'Não foi possível atualizar o perfil.');
        $usuario = $_POST;
    }
}
$titulo = 'Editar perfil';
require __DIR__ . '/includes/aluno_cabecalho.php';
?>
<section class="atalho">
    <h1>Editar perfil</h1>
    <?php if ($erro): ?><p role="alert"><?= escapar($erro) ?></p><?php endif; ?>
    <?php if (!empty($_SESSION['perfil_salvo'])): ?>
        <p role="status">Perfil salvo com sucesso.</p>
        <?php unset($_SESSION['perfil_salvo']); ?>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data" class="perfil-form">
        <?php campoCsrf(); exibirCampos($campos, $usuario); ?>
        <button type="submit" class="sair">Salvar</button>
    </form>
</section>
<?php require __DIR__ . '/includes/aluno_rodape.php'; ?>
