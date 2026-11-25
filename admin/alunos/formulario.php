<?php
if (!isset($editar)) {
    http_response_code(404);
    exit;
}
require_once __DIR__ . '/../inicio.php';
$tabela = 'usuarios';
$menu = 'Alunos';
$titulo = $editar ? 'Consultar e editar aluno' : 'Novo aluno';
$padroes = ['status' => 'ativo'];
$campos = [
    'nome' => ['Nome', 'text', true, 120],
    'email' => ['E-mail', 'email', true, 150],
    'telefone' => ['Telefone', 'tel', false, 20],
    'data_nascimento' => ['Data de nascimento', 'date', false, 0],
    'foto' => ['Foto (caminho em assets/img)', 'imagem', false, 255],
    'senha' => [$editar ? 'Nova senha (opcional)' : 'Senha (mínimo de 8 caracteres)', 'password', !$editar, 72],
    'status' => ['Status', 'select', true, opcoes(['ativo', 'inativo', 'bloqueado'])],
];
$complementar = static function (array $dados): array {
    if ($dados['data_nascimento'] !== null && $dados['data_nascimento'] > date('Y-m-d')) {
        throw new InvalidArgumentException('A data de nascimento deve ser anterior ou igual a hoje.');
    }
    return $dados;
};
require __DIR__ . '/../formulario.php';
