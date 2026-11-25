<?php
if (!isset($editar)) {
    http_response_code(404);
    exit;
}
require_once __DIR__ . '/../inicio.php';
$tabela = 'assinaturas';
$menu = 'Assinaturas';
$titulo = $editar ? 'Consultar e editar assinatura' : 'Nova assinatura';
$padroes = ['data_inicio' => date('Y-m-d'), 'status' => 'pendente'];
$campos = [
    'data_inicio' => ['Data de início', 'date', true, 0],
    'data_fim' => [$editar ? 'Data de término' : 'Data de término (opcional, use a duração do plano)', 'date', $editar, 0],
    'valor_contratado' => [$editar ? 'Valor contratado (R$)' : 'Valor contratado (opcional, use o preço do plano)', 'money', $editar, 0],
    'status' => ['Status', 'select', true, opcoes(['pendente', 'ativa', 'expirada', 'cancelada'])],
];
if ($editar) {
    $mostrarContexto = static function (array $registro) use ($pdo): string {
        $vinculo = consulta($pdo, 'SELECT u.nome AS aluno, p.nome AS plano FROM usuarios u JOIN planos p ON p.id = ? WHERE u.id = ?',
            [$registro['plano_id'], $registro['usuario_id']])->fetch();
        return $vinculo ? $vinculo['aluno'] . ' · ' . $vinculo['plano'] : '';
    };
} else {
    $campos = [
        'usuario_id' => ['Aluno', 'select', true, consulta($pdo, "SELECT id, CONCAT(nome, ' — ', email) FROM usuarios WHERE status = 'ativo' ORDER BY nome")->fetchAll(PDO::FETCH_KEY_PAIR)],
        'plano_id' => ['Plano', 'select', true, consulta($pdo, "SELECT id, CONCAT(nome, ' — R$ ', valor) FROM planos WHERE status = 'ativo' ORDER BY nome")->fetchAll(PDO::FETCH_KEY_PAIR)],
    ] + $campos;
}
$complementar = static function (array $dados) use ($pdo, $editar): array {
    if (!$editar) {
        $plano = consulta($pdo, "SELECT valor, duracao_dias FROM planos WHERE id = ? AND status = 'ativo' FOR UPDATE", [$dados['plano_id']])->fetch();
        $alunoAtivo = consulta($pdo, "SELECT id FROM usuarios WHERE id = ? AND status = 'ativo' FOR UPDATE", [$dados['usuario_id']])->fetchColumn();
        if (!$plano || !$alunoAtivo) {
            throw new InvalidArgumentException('Selecione um aluno e um plano ativos.');
        }
        $dados['valor_contratado'] ??= $plano['valor'];
        if ($dados['data_fim'] === null) {
            $inicio = new DateTimeImmutable($dados['data_inicio']);
            $dados['data_fim'] = $inicio->modify('+' . max(0, (int) $plano['duracao_dias'] - 1) . ' days')->format('Y-m-d');
        }
        $dados['criado_por_admin'] = $_SESSION['admin_id'];
    }
    if ($dados['data_fim'] < $dados['data_inicio'] || strlen($dados['data_fim']) !== 10) {
        throw new InvalidArgumentException('Confira a data de término da assinatura.');
    }
    return $dados;
};
require __DIR__ . '/../formulario.php';
