<?php
require_once __DIR__ . '/formularios.php';
sessaoNox();

function exigirAdmin(): void
{
    global $pdo;
    $raizAdmin = preg_replace('~/admin/.*$~', '/admin/', $_SERVER['SCRIPT_NAME']);
    if (empty($_SESSION['admin_id'])) {
        redirecionar($raizAdmin . 'login.php');
    }
    tratarErrosBanco();
    require_once __DIR__ . '/../config/conexao.php';
    if (!consulta($pdo, "SELECT id FROM administradores WHERE id = ? AND status = 'ativo'", [$_SESSION['admin_id']])->fetchColumn()) {
        unset($_SESSION['admin_id'], $_SESSION['admin_nome'], $_SESSION['admin_email'], $_SESSION['admin_foto']);
        redirecionar($raizAdmin . 'login.php');
    }
}
