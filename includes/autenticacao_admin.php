
<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function exigirAdmin(): void
{
    if (empty($_SESSION['admin_id'])) {
        header('Location: /nox-gym/admin/login.php');
        exit;
    }
}
