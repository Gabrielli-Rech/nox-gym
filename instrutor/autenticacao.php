
<?php

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../includes/formularios.php';

sessaoNox();

if (!isset($_SESSION['instrutor_id'])) {
    redirecionar('login.php');
}

$stmt = consulta(
    $pdo,
    "SELECT id, nome, cpf, email, telefone, status
    FROM instrutores
    WHERE id = ?
    LIMIT 1",
    [$_SESSION['instrutor_id']]
);

$instrutor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$instrutor || $instrutor['status'] !== 'ativo') {
    unset(
        $_SESSION['instrutor_id'],
        $_SESSION['instrutor_nome'],
        $_SESSION['instrutor_cpf']
    );

    session_regenerate_id(true);

    redirecionar('login.php');
}

$_SESSION['instrutor_nome'] = $instrutor['nome'];
