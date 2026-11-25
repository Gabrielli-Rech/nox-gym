<?php
require_once __DIR__ . '/../inicio.php';
$editar = true;
$tabela = 'conteudos';
$titulo = 'Editar conteúdo';
$menu = 'Conteúdos';
$campos = [
    'titulo' => ['Título', 'text', false, 200],
    'subtitulo' => ['Subtítulo', 'text', false, 255],
    'texto' => ['Texto', 'textarea', false, 16000],
    'imagem' => ['Imagem (caminho em assets/img)', 'imagem', false, 255],
];
require __DIR__ . '/../formulario.php';
