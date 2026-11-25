<?php
require_once __DIR__ . '/includes/formularios.php';
sessaoNox();
tratarErrosBanco();
$campos = [
    'nome' => ['Nome', 'text', true, 120],
    'email' => ['E-mail', 'email', true, 150],
    'telefone' => ['Telefone', 'tel', false, 20],
    'senha' => ['Senha (8 a 72 caracteres)', 'password', true, 72],
];
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificarCsrf();
    try {
        $dados = lerFormulario($campos);
        require_once __DIR__ . '/config/conexao.php';
        gravarRegistro($pdo, 'usuarios', $dados);
        $_SESSION['cadastro_sucesso'] = true;
        redirecionar('login.php');
    } catch (InvalidArgumentException | PDOException $e) {
        $erro = $e instanceof InvalidArgumentException ? $e->getMessage() :
            (($e->errorInfo[1] ?? null) === 1062 ? 'Este e-mail já está cadastrado.' : 'Não foi possível cadastrar sua conta.');
        $_SESSION['cadastro_erro'] = $erro;
        redirecionar('login.php?cadastro=1');
    }
}
redirecionar('login.php?cadastro=1');
