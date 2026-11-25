<?php
if (!isset($tabela, $campos, $editar)) {
    http_response_code(404);
    exit;
}
// Usado pelas páginas que definem os campos e a tabela de um cadastro.
$id = $editar ? idConsulta() : null;
$registro = $id === null ? ($padroes ?? []) :
    exigirRegistro(consulta($pdo, "SELECT * FROM $tabela WHERE id = ?", [$id])->fetch());
$contexto = isset($mostrarContexto) ? $mostrarContexto($registro) : '';
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificarCsrf();
    $novasImagens = [];
    try {
        $dados = receberImagens($campos, lerFormulario($campos), $novasImagens);
        if (array_key_exists('senha', $dados) && $dados['senha'] === null) {
            unset($dados['senha']);
        }
        $pdo->beginTransaction();
        if (isset($complementar)) {
            $dados = $complementar($dados, $registro);
        }
        $salvo = gravarRegistro($pdo, $tabela, $dados, $id);
        logAdmin($pdo, $tabela . ($editar ? '_editado' : '_criado'), $salvo);
        $pdo->commit();
        $_SESSION['aviso_nox'] = 'Registro salvo com sucesso.';
        redirecionar('index.php');
    } catch (InvalidArgumentException | PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        limparImagensNaoSalvas($novasImagens);
        $erro = $e instanceof InvalidArgumentException ? $e->getMessage() :
            (($e->errorInfo[1] ?? null) === 1062 ? 'Já existe um registro com esse identificador (CPF ou e-mail).' : 'Não foi possível salvar o registro.');
        $registro = array_intersect_key($_POST, $campos);
    }
}
require __DIR__ . '/../includes/painel.php';
require __DIR__ . '/../includes/formulario_painel.php';
require __DIR__ . '/../includes/painel_fim.php';
