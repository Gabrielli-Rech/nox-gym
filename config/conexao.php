<?php

// Selecione o ambiente: 'local' ou 'remoto'
$ambiente = 'local';

if ($ambiente === 'local') {

    // Conexão local - XAMPP
    $host = 'localhost';
    $porta = 3306;
    $banco = 'nox_gym';
    $usuario = 'root';
    $senha = '';

} else {

    // Conexão remota - Servidor Tailscale
    $host = '100.117.144.65';
    $porta = 3306;
    $banco = 'nox_gym';
    $usuario = 'TailServer';
    $senha = 'ServerAdmin@123';

}

try {

    $pdo = new PDO(
        "mysql:host=$host;port=$porta;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );

} catch (PDOException $e) {

    error_log($e->getMessage());

    http_response_code(500);
    exit('Não foi possível conectar ao banco de dados.');

}