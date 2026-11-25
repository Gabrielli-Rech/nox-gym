<?php
require_once __DIR__ . '/formularios.php';
tratarErrosBanco();
require_once __DIR__ . '/../config/conexao.php';
$conteudos = consulta($pdo, "SELECT chave, titulo, subtitulo, texto, imagem FROM conteudos
    WHERE chave IN ('home_banner', 'home_sobre', 'home_diferenciais', 'sobre_historia', 'estrutura_apresentacao')")
    ->fetchAll(PDO::FETCH_UNIQUE | PDO::FETCH_ASSOC);

function conteudo(string $chave, string $campo, string $padrao = ''): string
{
    global $conteudos;
    $valor = $conteudos[$chave][$campo] ?? '';
    return $valor === '' ? $padrao : escapar($valor);
}

function imagemConteudo(string $chave, string $padrao): string
{
    global $conteudos;
    $imagem = $conteudos[$chave]['imagem'] ?? '';
    return escapar(caminhoImagem($imagem) ? $imagem : $padrao);
}
