<?php
require_once __DIR__ . '/../inicio.php';
$titulo = 'Treinos';
$tabela = 'treinos';
$criar = 'Novo treino e vínculo';
$destino = 'visualizar.php';
$acao = 'Consultar';
$sqlLista = 'SELECT t.id, t.nome, t.status, u.nome AS aluno, i.nome AS instrutor
    FROM treinos t JOIN usuarios u ON u.id = t.usuario_id JOIN instrutores i ON i.id = t.instrutor_id ORDER BY t.id DESC';
$colunas = ['nome' => 'Treino', 'aluno' => 'Aluno', 'instrutor' => 'Instrutor', 'status' => 'Status'];
require __DIR__ . '/../listagem.php';
