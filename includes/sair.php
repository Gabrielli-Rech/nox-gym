<?php
require_once __DIR__ . '/formularios.php';
sessaoNox();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Use o botão Sair na sua área.');
}
verificarCsrf();
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $opcoes = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires' => time() - 3600,
        'path' => $opcoes['path'],
        'domain' => $opcoes['domain'],
        'secure' => $opcoes['secure'],
        'httponly' => $opcoes['httponly'],
        'samesite' => $opcoes['samesite'],
    ]);
}
session_destroy();
redirecionar('login.php');
