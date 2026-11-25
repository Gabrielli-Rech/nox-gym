<?php
require_once __DIR__ . '/../inicio.php';
$titulo = 'Mensagens';
$tabela = 'mensagens_contato';
$sqlLista = "SELECT id, nome, email, status, criado_em FROM mensagens_contato ORDER BY (status = 'nova') DESC, criado_em DESC";
$colunas = ['nome' => 'Remetente', 'email' => 'E-mail', 'status' => 'Status', 'criado_em' => 'Recebida em'];
$destino = 'viasualizar.php';
$acao = 'Visualizar';
require __DIR__ . '/../listagem.php';
