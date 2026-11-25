<?php
require_once __DIR__ . '/../inicio.php';
$titulo = 'Alunos';
$tabela = 'usuarios';
$criar = 'Novo aluno';
$colunas = ['nome' => 'Aluno', 'email' => 'E-mail', 'telefone' => 'Telefone', 'status' => 'Status'];
require __DIR__ . '/../listagem.php';
