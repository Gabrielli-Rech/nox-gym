<?php
if (!isset($editar)) {
    http_response_code(404);
    exit;
}
require_once __DIR__ . '/../inicio.php';
$tabela = 'exercicios';
$menu = 'Exercícios';
$titulo = $editar ? 'Editar exercício' : 'Novo exercício';
$padroes = ['status' => 'ativo'];
$campos = [
    'nome' => ['Nome', 'text', true, 120],
    'grupo_muscular' => ['Grupo muscular', 'text', true, 100],
    'descricao' => ['Descrição', 'textarea', false, 16000],
    'instrucoes' => ['Instruções', 'textarea', false, 16000],
    'imagem' => ['Imagem (caminho em assets/img)', 'imagem', false, 255],
    'status' => ['Status', 'select', true, opcoes(['ativo', 'inativo'])],
];
require __DIR__ . '/../formulario.php';
