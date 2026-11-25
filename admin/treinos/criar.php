<?php
require_once __DIR__ . '/../inicio.php';
require_once __DIR__ . '/../../includes/treinos_dados.php';
$tabela = 'treinos';
$editar = false;
$titulo = 'Novo treino e vínculo';
$menu = 'Treinos';
$padroes = ['status' => 'rascunho', 'data_inicio' => date('Y-m-d')];
$campos = [
    'usuario_id' => ['Aluno', 'select', true, consulta($pdo, "SELECT id, CONCAT(nome, ' — ', email) FROM usuarios WHERE status = 'ativo' ORDER BY nome")->fetchAll(PDO::FETCH_KEY_PAIR)],
    'instrutor_id' => ['Instrutor', 'select', true, consulta($pdo, "SELECT id, nome FROM instrutores WHERE status = 'ativo' ORDER BY nome")->fetchAll(PDO::FETCH_KEY_PAIR)],
] + camposTreino();
$complementar = static function (array $dados) use ($pdo): array {
    $ativo = consulta($pdo, "SELECT id FROM usuarios WHERE id = ? AND status = 'ativo' FOR UPDATE", [$dados['usuario_id']])->fetchColumn();
    $instrutor = consulta($pdo, "SELECT id FROM instrutores WHERE id = ? AND status = 'ativo' FOR UPDATE", [$dados['instrutor_id']])->fetchColumn();
    if (!$ativo || !$instrutor) {
        throw new InvalidArgumentException('Selecione um aluno e um instrutor ativos.');
    }
    return validarDatasTreino($dados);
};
require __DIR__ . '/../formulario.php';
