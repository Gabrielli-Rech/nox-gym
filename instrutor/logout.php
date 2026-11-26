
<?php

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../includes/formularios.php';

sessaoNox();

unset(
    $_SESSION['instrutor_id'],
    $_SESSION['instrutor_nome'],
    $_SESSION['instrutor_cpf']
);

session_regenerate_id(true);

redirecionar('login.php');
