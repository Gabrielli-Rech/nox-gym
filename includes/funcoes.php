<?php

function limparEntrada(string $valor): string
{
    return trim($valor);
}

function escapar(string $valor): string
{
    return htmlspecialchars(
        $valor,
        ENT_QUOTES,
        'UTF-8'
    );
}

function redirecionar(string $destino): never
{
    header("Location: $destino");
    exit;
}
