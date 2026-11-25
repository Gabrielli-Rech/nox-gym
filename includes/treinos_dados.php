<?php
function treinoVisivel(PDO $pdo, int $id, ?int $instrutorId = null, ?int $alunoId = null): array
{
    $sql = 'SELECT t.*, u.nome AS aluno, i.nome AS instrutor FROM treinos t
        JOIN usuarios u ON u.id = t.usuario_id JOIN instrutores i ON i.id = t.instrutor_id WHERE t.id = ?';
    $params = [$id];
    if ($instrutorId !== null) {
        $sql .= ' AND t.instrutor_id = ?';
        $params[] = $instrutorId;
    }
    if ($alunoId !== null) {
        $sql .= " AND t.usuario_id = ? AND t.status <> 'rascunho'";
        $params[] = $alunoId;
    }
    return exigirRegistro(consulta($pdo, $sql, $params)->fetch());
}

function exerciciosTreino(PDO $pdo, int $id): array
{
    return consulta($pdo, 'SELECT te.*, e.nome, e.grupo_muscular, e.descricao, e.instrucoes, e.imagem
        FROM treino_exercicios te JOIN exercicios e ON e.id = te.exercicio_id
        WHERE te.treino_id = ? ORDER BY te.ordem', [$id])->fetchAll();
}

function camposTreino(): array
{
    return [
        'nome' => ['Nome', 'text', true, 120],
        'objetivo' => ['Objetivo', 'text', false, 150],
        'descricao' => ['Descrição', 'textarea', false, 16000],
        'data_inicio' => ['Início', 'date', true, 0],
        'data_fim' => ['Término', 'date', false, 0],
        'status' => ['Status', 'select', true, [
            'rascunho' => 'Rascunho', 'ativo' => 'Ativo', 'concluido' => 'Concluído', 'arquivado' => 'Arquivado',
        ]],
    ];
}

function validarDatasTreino(array $dados): array
{
    if ($dados['data_fim'] !== null && $dados['data_fim'] < $dados['data_inicio']) {
        throw new InvalidArgumentException('O término deve ser igual ou posterior ao início.');
    }
    return $dados;
}
