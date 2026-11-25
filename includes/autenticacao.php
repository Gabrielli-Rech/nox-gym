<?php
require_once __DIR__ . '/formularios.php';
sessaoNox();

function exigirLogin(): array
{
    global $pdo;
    if (empty($_SESSION['usuario_id'])) {
        redirecionar('login.php');
    }
    tratarErrosBanco();
    require_once __DIR__ . '/../config/conexao.php';
    $usuario = consulta($pdo, "SELECT id, nome, email, telefone, foto, data_nascimento, criado_em
        FROM usuarios WHERE id = ? AND status = 'ativo'", [$_SESSION['usuario_id']])->fetch();
    if (!$usuario) {
        unset($_SESSION['usuario_id'], $_SESSION['usuario_nome'], $_SESSION['usuario_email']);
        redirecionar('login.php');
    }
    return $usuario;
}
