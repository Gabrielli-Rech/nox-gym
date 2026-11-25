<?php
require_once __DIR__ . '/../inicio.php';
$titulo = 'Planos';
$tabela = 'planos';
$criar = 'Novo plano';
$colunas = ['nome' => 'Plano', 'valor' => 'Valor (R$)', 'duracao_dias' => 'Duração (dias)', 'status' => 'Status'];
require __DIR__ . '/../listagem.php';
