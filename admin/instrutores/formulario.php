<?php
if (!isset($editar)) {
    http_response_code(404);
    exit;
}
require_once __DIR__ . '/../inicio.php';
$tabela = 'instrutores';
$menu = 'Instrutores';
$titulo = $editar ? 'Editar instrutor' : 'Novo instrutor';
$padroes = ['status' => 'ativo'];
$campos = [
    'nome' => ['Nome', 'text', true, 120],
    'cpf' => ['CPF', 'cpf', true, 11],
    'email' => ['E-mail', 'email', false, 150],
    'telefone' => ['Telefone', 'tel', false, 20],
    'senha' => [$editar ? 'Nova senha (opcional)' : 'Senha (mínimo de 8 caracteres)', 'password', !$editar, 72],
    'status' => ['Status', 'select', true, opcoes(['ativo', 'inativo'])],
];
if (!$editar) {
    $complementar = static fn (array $dados): array => $dados + ['criado_por_admin' => $_SESSION['admin_id']];
}
require __DIR__ . '/../formulario.php';
