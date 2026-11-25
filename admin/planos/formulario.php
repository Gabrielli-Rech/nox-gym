<?php
if (!isset($editar)) {
    http_response_code(404);
    exit;
}
require_once __DIR__ . '/../inicio.php';
$tabela = 'planos';
$menu = 'Planos';
$titulo = $editar ? 'Editar plano' : 'Novo plano';
$padroes = ['status' => 'ativo'];
$campos = [
    'nome' => ['Nome', 'text', true, 100],
    'valor' => ['Valor (R$)', 'money', true, 0],
    'duracao_dias' => ['Duração em dias', 'number', true, 1],
    'descricao' => ['Descrição', 'textarea', false, 16000],
    'beneficios' => ['Benefícios (um por linha)', 'textarea', false, 16000],
    'status' => ['Status', 'select', true, opcoes(['ativo', 'inativo'])],
];
require __DIR__ . '/../formulario.php';
