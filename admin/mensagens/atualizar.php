<?php
require_once __DIR__ . '/../inicio.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}
verificarCsrf();
$id = idConsulta();
$status = entrada($_POST, 'status');
if (!in_array($status, ['lida', 'respondida'], true)) {
    http_response_code(400);
    exit('Status inválido.');
}
$pdo->beginTransaction();
$atual = exigirRegistro(consulta($pdo, 'SELECT status FROM mensagens_contato WHERE id = ? FOR UPDATE', [$id])->fetch());
if ($atual['status'] !== 'respondida' && $atual['status'] !== $status) {
    gravarRegistro($pdo, 'mensagens_contato', ['status' => $status], $id);
    logAdmin($pdo, 'mensagem_' . $status, $id);
}
$pdo->commit();
$_SESSION['aviso_nox'] = 'Situação da mensagem atualizada.';
redirecionar('viasualizar.php?id=' . $id);
