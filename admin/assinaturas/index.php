<?php
require_once __DIR__ . '/../inicio.php';
$titulo = 'Assinaturas';
$tabela = 'assinaturas';
$criar = 'Nova assinatura';
$sqlLista = "SELECT a.id, u.nome AS aluno, p.nome AS plano, a.data_inicio, a.data_fim, a.valor_contratado,
    CASE WHEN a.status IN ('pendente', 'ativa') AND a.data_fim < CURRENT_DATE THEN 'vencida' ELSE a.status END AS situacao
    FROM assinaturas a JOIN usuarios u ON u.id = a.usuario_id JOIN planos p ON p.id = a.plano_id ORDER BY a.id DESC";
$colunas = ['aluno' => 'Aluno', 'plano' => 'Plano', 'data_inicio' => 'Início', 'data_fim' => 'Vencimento',
    'valor_contratado' => 'Valor (R$)', 'situacao' => 'Situação'];
require __DIR__ . '/../listagem.php';
