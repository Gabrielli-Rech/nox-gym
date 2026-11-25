<?php
require_once __DIR__ . '/../includes/formularios.php';
sessaoNox();
tratarErrosBanco();
if (empty($_SESSION['instrutor_id'])) {
    redirecionar('login.php');
}
require_once __DIR__ . '/../config/conexao.php';
$instrutor = consulta($pdo, "SELECT id, nome, email, telefone FROM instrutores WHERE id = ? AND status = 'ativo'", [$_SESSION['instrutor_id']])->fetch();
if (!$instrutor) {
    unset($_SESSION['instrutor_id']);
    redirecionar('login.php');
}
$instrutorId = (int) $instrutor['id'];
$areaInstrutor = true;
$prefixo = './';
