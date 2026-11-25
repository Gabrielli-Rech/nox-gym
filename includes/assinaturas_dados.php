<?php
function assinaturasAluno(PDO $pdo, int $alunoId): array
{
    return consulta($pdo, "SELECT a.data_inicio, a.data_fim, a.valor_contratado, a.status,
        p.nome, p.descricao, p.beneficios,
        CASE WHEN a.status IN ('pendente', 'ativa') AND a.data_fim < CURRENT_DATE THEN 'vencida'
             WHEN a.status = 'ativa' AND a.data_inicio > CURRENT_DATE THEN 'agendada'
             ELSE a.status END AS situacao
        FROM assinaturas a JOIN planos p ON p.id = a.plano_id WHERE a.usuario_id = ?
        ORDER BY (a.status = 'ativa' AND CURRENT_DATE BETWEEN a.data_inicio AND a.data_fim) DESC,
            (a.status IN ('ativa', 'pendente') AND a.data_fim >= CURRENT_DATE) DESC, a.data_inicio DESC, a.id DESC",
        [$alunoId])->fetchAll();
}
