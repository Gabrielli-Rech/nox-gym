<?php
function imagemPublica(string $arquivo, string $descricao, ?string $alternativa = null): void
{
    if (!is_file(__DIR__ . '/../' . $arquivo)) {
        if ($alternativa === null) {
            echo '<div class="produto-sem-imagem" aria-hidden="true"></div>';
            return;
        }
        $arquivo = $alternativa;
        $descricao = 'Ambiente da academia';
    }
    echo '<img src="' . htmlspecialchars($arquivo, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($descricao, ENT_QUOTES, 'UTF-8') . '">';
}
