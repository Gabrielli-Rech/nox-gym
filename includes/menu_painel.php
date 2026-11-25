<?php
$prefixo = $prefixo ?? '../';
$areaInstrutor = $areaInstrutor ?? false;
$rotas = $areaInstrutor ? [
    'index.php' => 'Dashboard', 'alunos.php' => 'Alunos', 'treinos.php' => 'Treinos',
] : [
    'dashboard.php' => 'Dashboard', 'administradores/index.php' => 'Administradores',
    'instrutores/index.php' => 'Instrutores', 'alunos/index.php' => 'Alunos',
    'planos/index.php' => 'Planos', 'assinaturas/index.php' => 'Assinaturas',
    'exercicios/index.php' => 'Exercícios', 'treinos/index.php' => 'Treinos',
    'mensagens/index.php' => 'Mensagens', 'conteudos/index.php' => 'Conteúdos',
];
