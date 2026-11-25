<?php
require_once __DIR__ . '/funcoes.php';

function entrada(array $origem, string $campo): string
{
    return is_string($origem[$campo] ?? null) ? $origem[$campo] : '';
}

function csrf(): string
{
    return $_SESSION['csrf_formulario'] ??= bin2hex(random_bytes(32));
}

function campoCsrf(): void
{
    echo '<input type="hidden" name="csrf_token" value="' . escapar(csrf()) . '">';
}

function verificarCsrf(): void
{
    if (!hash_equals(csrf(), entrada($_POST, 'csrf_token'))) {
        http_response_code(403);
        exit('Formulário inválido ou expirado.');
    }
}

function consulta(PDO $pdo, string $sql, array $parametros = []): PDOStatement
{
    $stmt = $pdo->prepare($sql);
    $stmt->execute($parametros);
    return $stmt;
}

function idConsulta(string $campo = 'id'): int
{
    $id = filter_var(entrada($_GET, $campo), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($id === false) {
        http_response_code(400);
        exit('Identificador inválido.');
    }
    return $id;
}

function exigirRegistro(array|false $registro): array
{
    if (!$registro) {
        http_response_code(404);
        exit('Registro indisponível.');
    }
    return $registro;
}

function logAdmin(PDO $pdo, string $acao, int $id): void
{
    consulta($pdo, 'INSERT INTO logs_admin (administrador_id, acao, descricao) VALUES (?, ?, ?)',
        [$_SESSION['admin_id'], $acao, 'Registro #' . $id]);
}

function cpfValido(string $cpf): bool
{
    if (!preg_match('/^\d{11}$/D', $cpf) || $cpf === str_repeat($cpf[0], 11)) {
        return false;
    }

    for ($posicao = 9; $posicao <= 10; $posicao++) {
        $soma = 0;
        for ($i = 0; $i < $posicao; $i++) {
            $soma += (int) $cpf[$i] * ($posicao + 1 - $i);
        }
        if ((int) $cpf[$posicao] !== ($soma * 10 % 11) % 10) {
            return false;
        }
    }
    return true;
}

function caminhoImagem(string $valor): bool
{
    return preg_match('~^assets/img/(?:[\pL\pN _-]+/)*[\pL\pN _.()-]+\.(?:png|jpe?g|webp|gif)$~iuD', $valor)
        && is_file(__DIR__ . '/../' . $valor);
}

// Cada campo informa rótulo, tipo, obrigatoriedade e limite ou opções.
function lerFormulario(array $campos): array
{
    $dados = [];
    foreach ($campos as $nome => [$rotulo, $tipo, $obrigatorio, $limite]) {
        $valor = entrada($_POST, $nome);
        if ($tipo === 'imagem' && ($_FILES[$nome . '_arquivo']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $dados[$nome] = null;
            continue;
        }
        if ($tipo !== 'password') {
            $valor = trim($valor);
        }
        if ($valor === '' && !$obrigatorio) {
            $dados[$nome] = null;
            continue;
        }
        $valido = $valor !== '' && match ($tipo) {
            'select' => array_key_exists($valor, $limite),
            'tel' => strlen($valor) <= $limite && preg_match('/^\+?[0-9() .-]+$/D', $valor)
                && strlen(preg_replace('/\D/', '', $valor)) >= 8,
            'email' => strlen($valor) <= $limite && filter_var($valor, FILTER_VALIDATE_EMAIL),
            'date' => preg_match('/^[1-9]\d{3}-\d{2}-\d{2}$/D', $valor)
                && checkdate((int) substr($valor, 5, 2), (int) substr($valor, 8, 2), (int) substr($valor, 0, 4)),
            'number' => ctype_digit($valor) && (float) $valor <= 4294967295 && (int) $valor >= $limite,
            'money' => preg_match('/^\d{1,8}(?:[.,]\d{1,2})?$/D', $valor),
            'password' => strlen($valor) >= 8 && strlen($valor) <= 72 && !str_contains($valor, "\0"),
            'imagem' => strlen($valor) <= $limite && caminhoImagem($valor),
            'cpf' => preg_match('/^\d{11}$|^\d{3}\.\d{3}\.\d{3}-\d{2}$/D', $valor)
                && cpfValido(preg_replace('/\D/', '', $valor)),
            default => mb_check_encoding($valor, 'UTF-8') && mb_strlen($valor) <= $limite && strlen($valor) <= 65535,
        };
        if (!$valido) {
            throw new InvalidArgumentException('Confira o campo ' . $rotulo . '.');
        }
        $dados[$nome] = match ($tipo) {
            'cpf' => preg_replace('/\D/', '', $valor),
            'money' => str_replace(',', '.', $valor),
            'password' => password_hash($valor, PASSWORD_DEFAULT),
            default => $valor,
        };
    }
    return $dados;
}

function receberImagens(array $campos, array $dados, array &$novasImagens): array
{
    foreach ($campos as $campo => [, $tipo]) {
        $arquivo = $_FILES[$campo . '_arquivo'] ?? null;
        if ($tipo !== 'imagem' || $arquivo === null || $arquivo['error'] === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        if ($arquivo['error'] !== UPLOAD_ERR_OK || !is_string($arquivo['tmp_name'])
            || !is_uploaded_file($arquivo['tmp_name']) || $arquivo['size'] > 2 * 1024 * 1024) {
            throw new InvalidArgumentException('Envie uma imagem de até 2 MB.');
        }
        $imagem = @getimagesize($arquivo['tmp_name']);
        $extensao = match ($imagem[2] ?? null) {
            IMAGETYPE_JPEG => 'jpg',
            IMAGETYPE_PNG => 'png',
            IMAGETYPE_GIF => 'gif',
            IMAGETYPE_WEBP => 'webp',
            default => null,
        };
        if ($extensao === null || $imagem[0] > 5000 || $imagem[1] > 5000) {
            throw new InvalidArgumentException('Use uma imagem JPG, PNG, GIF ou WebP de até 5000 × 5000 pixels.');
        }
        $caminho = 'assets/img/nox-' . bin2hex(random_bytes(16)) . '.' . $extensao;
        if (!@move_uploaded_file($arquivo['tmp_name'], __DIR__ . '/../' . $caminho)) {
            throw new InvalidArgumentException('Não foi possível gravar a imagem. Confira a permissão da pasta assets/img.');
        }
        $novasImagens[] = $caminho;
        $dados[$campo] = $caminho;
    }
    return $dados;
}

function limparImagensNaoSalvas(array $imagens): void
{
    foreach ($imagens as $imagem) {
        @unlink(__DIR__ . '/../' . $imagem);
    }
}

function exibirCampos(array $campos, array $dados): void
{
    foreach ($campos as $nome => [$rotulo, $tipo, $obrigatorio, $limite]) {
        $valor = $tipo === 'password' ? '' : (is_scalar($dados[$nome] ?? null) ? (string) $dados[$nome] : '');
        $required = $obrigatorio ? ' required' : '';
        echo '<div class="form-field"><label for="' . escapar($nome) . '">' . escapar($rotulo) . '</label>';
        if ($tipo === 'select') {
            echo '<select id="' . escapar($nome) . '" name="' . escapar($nome) . '"' . $required . '>';
            echo '<option value="">Selecione</option>';
            foreach ($limite as $chave => $opcao) {
                echo '<option value="' . escapar((string) $chave) . '"' . ($valor === (string) $chave ? ' selected' : '') . '>' . escapar($opcao) . '</option>';
            }
            echo '</select>';
        } elseif ($tipo === 'textarea') {
            echo '<textarea id="' . escapar($nome) . '" name="' . escapar($nome) . '"' . $required . '>' . escapar($valor) . '</textarea>';
        } else {
            $tipoHtml = in_array($tipo, ['email', 'date', 'number', 'password', 'tel'], true) ? $tipo : 'text';
            echo '<input id="' . escapar($nome) . '" name="' . escapar($nome) . '" type="' . $tipoHtml . '" value="' . escapar($valor) . '"' . $required . '>';
        }
        if ($tipo === 'imagem') {
            $arquivo = escapar($nome . '_arquivo');
            echo '<label for="' . $arquivo . '">Ou envie uma imagem (até 2 MB)</label>';
            echo '<input type="file" id="' . $arquivo . '" name="' . $arquivo . '" accept="image/jpeg,image/png,image/gif,image/webp">';
        }
        echo '</div>';
    }
}

function opcoes(array $valores): array
{
    return array_combine($valores, array_map('ucfirst', $valores));
}

function gravarRegistro(PDO $pdo, string $tabela, array $dados, ?int $id = null): int
{
    // Os identificadores SQL vêm apenas das definições dos formulários no PHP.
    $campos = array_keys($dados);
    if ($id === null) {
        $marcadores = implode(', ', array_fill(0, count($campos), '?'));
        consulta($pdo, "INSERT INTO $tabela (" . implode(', ', $campos) . ") VALUES ($marcadores)", array_values($dados));
        return (int) $pdo->lastInsertId();
    }
    $atribuicoes = implode(', ', array_map(static fn ($campo) => "$campo = ?", $campos));
    consulta($pdo, "UPDATE $tabela SET $atribuicoes WHERE id = ?", [...array_values($dados), $id]);
    return $id;
}

function tratarErrosBanco(): void
{
    set_exception_handler(static function (Throwable $erro): void {
        http_response_code(500);
        error_log('NOX GYM: falha ao processar a solicitação.');
        echo 'Não foi possível processar a solicitação.';
    });
}

function sessaoNox(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start([
            'use_strict_mode' => true,
            'cookie_httponly' => true,
            'cookie_samesite' => 'Lax',
            'cookie_secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        ]);
    }
}
